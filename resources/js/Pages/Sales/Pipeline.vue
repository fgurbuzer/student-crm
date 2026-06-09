<script setup lang="ts">
import axios from "axios";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { onMounted, ref } from "vue";

import PipelineColumn from "@/Components/Sales/PipelineColumn.vue";

const columns = ref({});

const stages = [
    "new_lead",
    "contacted",
    "consultation_booked",
    "documents_requested",
    "application_started",
    "submitted",
    "won",
    "lost",
];

const loadPipeline = async () => {
    const { data } = await axios.get("/pipeline/get");

    console.log(data);
    columns.value = data.columns;
};

onMounted(loadPipeline);
</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Pipeline
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="d-flex ga-4 overflow-x-auto">
                    <PipelineColumn
                        v-for="stage in stages"
                        :key="stage"
                        :title="stage"
                        :deals="columns[stage] || []"
                    />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
