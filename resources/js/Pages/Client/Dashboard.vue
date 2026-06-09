<script setup lang="ts">
import { ref } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import CreateThreadModal from "@/components/client/CreateThreadModal.vue";
import ThreadCard from "@/components/client/ThreadCard.vue";

import { useAuth } from "@/composables/useAuth";

defineProps<{
    threads: Array<any>;
    deals: Array<any>;
}>();
const { hasRole } = useAuth();
const DEAL_STAGES = {
    new_lead: {
        label: "New Lead",
    },
    contacted: {
        label: "Contacted",
    },
    consultation_booked: {
        label: "Consultation",
    },
    documents_requested: {
        label: "Documents",
    },
    application_started: {
        label: "Application",
    },
    submitted: {
        label: "Submitted",
    },
    won: {
        label: "Won",
    },
    lost: {
        label: "Lost",
    },
};
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                My Conversations
            </h2>
        </template>
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="space-y-6">
                    <CreateThreadModal />

                    <div class="space-y-3">
                        <h2 class="mb-4 text-grey font-semibold">
                            Conversations
                        </h2>
                        <ThreadCard
                            v-for="thread in threads"
                            :key="thread.id"
                            :thread="thread"
                        />
                    </div>
                </div>

                <h2 class="mb-4 mt-5 text-grey font-semibold">Deals</h2>
                <div
                    class="mt-5 overflow-hidden bg-white shadow-sm sm:rounded-lg p-4"
                >
                    <div v-for="deal in deals" :key="deal.id" class="mb-2">
                        {{ deal.title }}

                        <div class="flex items-start justify-between">
                            <div
                                class="grid grid-cols-3 gap-4 overflow-hidden bg-white shadow-sm sm:rounded-lg p-4 mb-4"
                            >
                                <div>
                                    <strong>Owner:</strong>
                                    {{ deal.owner.name }}
                                </div>

                                <div>
                                    <strong>Value:</strong>
                                    USD {{ deal.value_amount }}
                                </div>
                            </div>
                            <span
                                class="bg-gray-100 text-gray-800 rounded-full px-2 py-1 text-xs font-medium"
                            >
                                {{ DEAL_STAGES[deal.stage].label }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
