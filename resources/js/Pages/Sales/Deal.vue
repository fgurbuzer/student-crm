<script setup lang="ts">
import axios from "axios";
import { onMounted, ref } from "vue";

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const props = defineProps<{
    dealId: number;
}>();

const DEAL_STAGES = [
    {
        id: "new_lead",
        text: "New Lead",
    },
    {
        id: "contacted",
        text: "Contacted",
    },
    {
        id: "consultation_booked",
        text: "Consultation",
    },
    {
        id: "documents_requested",
        text: "Documents",
    },
    {
        id: "application_started",
        text: "Application",
    },
    {
        id: "submitted",
        text: "Submitted",
    },
    {
        id: "won",
        text: "Won",
    },
    {
        id: "lost",
        text: "Lost",
    },
];

const note = ref("");

const deal = ref(null);
const dealtitle = ref(null);
const loading = ref(true);

const stage = ref("");

const loadDeal = async () => {
    const { data } = await axios.get(`/deals/${props.dealId}/get`);

    deal.value = data.deal;
    stage.value = data.deal.stage;
    dealtitle.value = data.deal.title;
    loading.value = false;
};

const addNote = async () => {
    const { data } = await axios.post(`/deals/${props.dealId}/notes`, {
        body: note.value,
    });

    deal.value.notes.push(data);
    note.value = "";
};

onMounted(loadDeal);
</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ dealtitle }}
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div v-if="deal">
                    <div
                        class="flex justify-between items-center overflow-hidden bg-white shadow-sm sm:rounded-lg p-4 mb-4"
                    >
                        <v-select
                            v-model="stage"
                            :items="DEAL_STAGES"
                            label="Stage"
                            item-title="text"
                            item-value="id"
                            density="compact"
                        />
                    </div>
                    <div
                        class="grid grid-cols-3 gap-4 overflow-hidden bg-white shadow-sm sm:rounded-lg p-4 mb-4"
                    >
                        <div>
                            <strong>Client:</strong>
                            {{ deal.user.name }}
                        </div>

                        <div>
                            <strong>Owner:</strong>
                            {{ deal.owner.name }}
                        </div>

                        <div>
                            <strong>Value:</strong>
                            USD {{ deal.value_amount }}
                        </div>
                    </div>
                    <div
                        class="overflow-hidden bg-white shadow-sm sm:rounded-lg p-4"
                    >
                        <h3>Stage History</h3>

                        <div v-for="h in deal.stage_history" :key="h.id">
                            {{ h.from_stage }} → {{ h.to_stage }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
