<script setup lang="ts">
import DealCard from "./DealCard.vue";

defineProps<{
    title: string;
    deals: any[];
}>();
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
    <div class="pipeline-column">
        <h3 class="mb-3 text-grey">
            {{ DEAL_STAGES[title].label }}
            ({{ deals.length }})
        </h3>

        <DealCard v-for="deal in deals" :key="deal.id" :deal="deal" />
    </div>
</template>
