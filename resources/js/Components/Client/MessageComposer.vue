<script setup lang="ts">
import axios from "axios";
import { usePage } from "@inertiajs/vue3";
import { onMounted, onUnmounted, ref, computed } from "vue";

const props = defineProps<{
    threadId: number;
}>();

const page = usePage();
const currentUser = computed(() => page.props.auth.user);

const body = ref("");

const messages = ref([]);
const loading = ref(false);

const send = async () => {
    loading.value = true;
    if (!body.value.trim()) {
        return;
    }

    try {
        const { data } = await axios.post(
            `/client/threads/${props.threadId}/messages`,
            {
                body: body.value,
            },
        );
        body.value = "";
        console.log(data.message);
        //messages.value.push(data.message);
        /* messages.value.push({
            ...data.message,
            sender: currentUser, // fallback only
        });*/
    } catch (error) {
        console.error(error);
    }
};

import { supabase } from "@/lib/supabase";

const loadMessages = async () => {
    try {
        const { data } = await axios.get(
            `/client/threads/${props.threadId}/messages`,
        );
        messages.value = data.data;
        //console.log(messages.value);
    } catch (error) {
        console.error(error);
    }
};

let channel: any;

onMounted(() => {
    channel = supabase
        .channel(`thread-${props.threadId}`)
        .on(
            "postgres_changes",
            {
                event: "INSERT",
                schema: "public",
                table: "messages",
                filter: `thread_id=eq.${props.threadId}`,
            },
            (payload) => {
                console.log("Event:" + payload);
                messages.value.push({
                    ...payload.new,
                    sender: currentUser, // fallback only
                });
                loading.value = false;
            },
        )
        .subscribe((status) => {
            console.log("SUPABASE STATUS:", status);
        });

    loadMessages();
});

onUnmounted(() => {
    supabase.removeChannel(channel);
});
</script>

<template>
    <div class="flex-1 overflow-y-auto p-4">
        <div
            v-for="message in messages"
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
                class="text-white"
                variant="flat"
                height="42"
                @click="send"
            ></v-btn>
        </div>
    </div>
</template>
