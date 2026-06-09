<script setup lang="ts">
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, usePage, Link } from "@inertiajs/vue3";
import { onMounted, onUnmounted, ref, computed } from "vue";
import { useAuth } from "@/composables/useAuth";
import axios from "axios";

const props = defineProps<{
    stats: Array<any>;
    unassignedThreads: Array<any>;
    myThreads: Array<any>;
    myDeals: Array<any>;
}>();

const page = usePage();
const currentUser = computed(() => page.props.auth.user);

const unassignedThreadsTemp = ref(props.unassignedThreads);
const myThreadsTemp = ref(props.myThreads);

const statsTemp = ref(props.stats);

const assign = async (thread) => {
    try {
        const { data } = await axios.post(
            `/user/threads/${thread.id}/assign`,
            {},
        );

        unassignedThreadsTemp.value = unassignedThreadsTemp.value.filter(
            (t) => t.id !== thread.id,
        );
        statsTemp.value.unassigned_threads--;
        myThreadsTemp.value.unshift({
            ...thread,
            assigned_to: currentUser.value.id,
        });

        statsTemp.value.my_threads++;

        //messages.value.push(data.message);
    } catch (error) {
        console.error(error);
    }
};

const { hasRole } = useAuth();
onMounted(() => {
    //console.log(props.unassignedThreads);
});
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">You're logged in!</div>
                </div>
                <div class="space-y-6 mt-5">
                    <!-- Stats -->
                    <div class="grid grid-cols-3 gap-4">
                        <div
                            class="overflow-hidden bg-white shadow-sm sm:rounded-lg p-4"
                        >
                            <div class="text-sm text-gray-500">Unassigned</div>

                            <div class="text-2xl font-bold">
                                {{ statsTemp.unassigned_threads }}
                            </div>
                        </div>

                        <div
                            class="overflow-hidden bg-white shadow-sm sm:rounded-lg p-4"
                        >
                            <div class="text-sm text-gray-500">
                                My Conversations
                            </div>

                            <div class="text-2xl font-bold">
                                {{ statsTemp.my_threads }}
                            </div>
                        </div>

                        <div
                            class="overflow-hidden bg-white shadow-sm sm:rounded-lg p-4"
                        >
                            <div class="text-sm text-gray-500">My Deals</div>

                            <div class="text-2xl font-bold">
                                {{ statsTemp.my_deals }}
                            </div>
                        </div>
                    </div>

                    <!-- Unassigned -->
                    <div>
                        <h2 class="mb-3 text-xl text-gray-800 font-semibold">
                            Unassigned Conversations
                        </h2>

                        <div
                            v-for="thread in unassignedThreadsTemp"
                            :key="thread.id"
                            class="flex items-start justify-between mb-2 overflow-hidden bg-white shadow-sm sm:rounded-lg p-4"
                        >
                            <div>
                                <div>
                                    {{ thread.subject }}
                                </div>

                                <div class="text-sm text-gray-500">
                                    {{ thread.user.name }}
                                </div>
                            </div>

                            <button
                                v-if="!hasRole('client')"
                                class="rounded bg-orange-600 px-4 py-2 text-white"
                                @click="assign(thread)"
                            >
                                Assign
                            </button>
                        </div>
                    </div>

                    <!-- My Threads -->
                    <div>
                        <h2 class="mb-3 text-xl text-gray-800 font-semibold">
                            My Conversations
                        </h2>

                        <Link
                            :href="route('sales.threads.show', thread.id)"
                            v-for="thread in myThreadsTemp"
                            :key="thread.id"
                            class="block mb-2 overflow-hidden bg-white shadow-sm sm:rounded-lg p-4"
                        >
                            <div class="text-gray-800">
                                {{ thread.subject }}
                            </div>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
