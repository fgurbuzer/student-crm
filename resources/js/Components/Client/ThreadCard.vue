<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
import { useAuth } from "@/composables/useAuth";
import { onMounted, onUnmounted, ref } from "vue";

const { hasRole } = useAuth();
interface Thread {
    id: number;
    subject: string;
    status: "open" | "pending" | "closed";
    last_message_at: string;
    assigned_user?: {
        id: number;
        name: string;
    } | null;
}

defineProps<{
    thread: Thread;
}>();

const statusClasses = {
    open: "bg-green-100 text-green-800",
    pending: "bg-yellow-100 text-yellow-800",
    closed: "bg-gray-100 text-gray-800",
};

onMounted(() => {});
</script>

<template>
    <Link
        :href="route('client.threads.show', thread.id)"
        class="block rounded-lg border bg-white p-4 transition"
    >
        <div class="flex items-start justify-between">
            <div>
                <h3 class="font-semibold text-gray-500">
                    {{ thread.subject }}
                </h3>
                <span
                    v-if="thread.assigned_to === null"
                    class="rounded-full px-2 py-1 text-xs font-medium bg-red-100 text-red-800"
                >
                    Unassigned
                </span>

                <p
                    v-if="thread.assigned_user"
                    class="mt-1 text-sm text-gray-500"
                >
                    Assigned to {{ thread.assigned_user.name }}
                </p>
            </div>

            <div>
                <span
                    class="rounded-full px-2 py-1 text-xs font-medium"
                    :class="statusClasses[thread.status]"
                >
                    {{ thread.status }}
                </span>
            </div>
        </div>

        <div class="mt-3 text-sm text-gray-500">
            Last activity:
            {{ new Date(thread.last_message_at).toLocaleString() }}
        </div>
    </Link>
</template>
