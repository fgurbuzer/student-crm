<script setup lang="ts">
import ChangeStage from "./ChangeStage.vue";
import { router } from "@inertiajs/vue3";

defineProps<{
    deal: any;
}>();
</script>

<template>
    <v-card class="mb-2">
        <v-card-text
            class="cursor-pointer"
            @click="router.visit(route('deals.show', deal.id))"
        >
            <div class="font-weight-bold">
                {{ deal.title }}
            </div>

            <div class="text-caption">
                {{ deal.user.name }}
            </div>

            <div class="text-caption">
                {{ deal.owner?.name }}
            </div>
        </v-card-text>
        <v-card-actions>
            <change-stage :deal="deal"></change-stage>
        </v-card-actions>
    </v-card>
</template>
