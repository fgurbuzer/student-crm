<script setup lang="ts">
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import CreateDealModal from "@/components/sales/CreateDealModal.vue";
import axios from "axios";
import { Head, usePage, Link } from "@inertiajs/vue3";
import { onMounted, onUnmounted, ref, computed } from "vue";
import { supabase } from "@/lib/supabase";

const props = defineProps<{
    thread: any;
}>();

const page = usePage();
const currentUser = computed(() => page.props.auth.user);
const loading = ref(false);

const myThreadsMessages = ref(props.thread.messages);

const body = ref("");

const send = async () => {
    loading.value = true;
    if (!body.value.trim()) {
        return;
    }

    try {
        const { data } = await axios.post(
            `/sales/threads/${props.thread.id}/messages`,
            {
                body: body.value,
            },
        );
        body.value = "";
        //console.log(data.message);
        /*myThreadsMessages.value.push({
            ...data.message,
            sender: currentUser, // fallback only
        });*/
        //myThreadsMessages.value.push(data.message);
    } catch (error) {
        console.error(error);
    }
};

let channel: any;

onMounted(() => {
    channel = supabase
        .channel(`thread-${props.thread.id}`)
        .on(
            "postgres_changes",
            {
                event: "INSERT",
                schema: "public",
                table: "messages",
                filter: `thread_id=eq.${props.thread.id}`,
            },
            (payload) => {
                myThreadsMessages.value.push({
                    ...payload.new,
                    sender: currentUser, // fallback only
                });
                loading.value = false;
            },
        )
        .subscribe((status) => {
            console.log("SUPABASE STATUS:", status);
        });
});
onUnmounted(() => {
    supabase.removeChannel(channel);
});
</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ thread.subject }}
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="grid grid-cols-3 gap-6">
                    <!-- Messages -->

                    <div class="col-span-2 flex h-[calc(100vh-400px)] flex-col">
                        <div class="flex-1 overflow-y-auto">
                            <div
                                v-for="message in myThreadsMessages"
                                :key="message.id"
                                class="mb-4 overflow-hidden bg-white shadow-sm sm:rounded-lg p-3"
                            >
                                <div class="text-xs text-gray-500">
                                    {{ message.sender.name }}
                                </div>

                                <div>
                                    {{ message.body }}
                                </div>
                            </div>
                        </div>

                        <div class="border-t p-4">
                            <div class="flex gap-2">
                                <input
                                    v-model="body"
                                    class="flex-1 text-gray-500 rounded border p-2"
                                    placeholder="Write a message..."
                                />

                                <v-btn
                                    :loading="loading"
                                    text="Send"
                                    color="#5865f2"
                                    height="42"
                                    class="text-white"
                                    variant="flat"
                                    @click="send"
                                ></v-btn>
                            </div>
                        </div>
                    </div>

                    <!-- Client sidebar -->
                    <div
                        class="overflow-hidden bg-white shadow-sm sm:rounded-lg p-4"
                    >
                        <h2 class="font-semibold">Client</h2>

                        <p>{{ thread.user.name }}</p>
                        <p>{{ thread.user.email }}</p>

                        <CreateDealModal :thread="thread" />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
