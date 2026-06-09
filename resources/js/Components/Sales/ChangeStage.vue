<script setup lang="ts">
import { useForm } from "@inertiajs/vue3";
import { ref } from "vue";
const props = defineProps<{
    deal: any;
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
const snackbarQueue = ref();
const messages = ref([]);
const dialog = ref(false);
const loading = ref(false);
const stage = ref("");

function addMessage(color, text) {
    messages.value.push({
        text: text,
        color,
    });
}

const submit = async () => {
    loading.value = true;
    try {
        const { data } = await axios.patch(`/deals/${props.deal.id}/stage`, {
            deal: props.deal.id,
            stage: stage.value,
        });

        dialog.value = false;
        loading.value = false;
        addMessage("success", "Deal stage changed successfully!");
        window.location.reload();
    } catch (error) {
        console.error(error);
    }
};
</script>

<template>
    <v-dialog v-model="dialog" max-width="600">
        <template v-slot:activator="{ props: activatorProps }">
            <v-btn
                text="Stage"
                color="#5865f2"
                class="text-white text-none font-weight-regular"
                variant="flat"
                block
                v-bind="activatorProps"
            ></v-btn>
        </template>

        <v-card color="white" title="Change stage">
            <v-card-text>
                <v-select
                    v-model="stage"
                    :items="DEAL_STAGES"
                    label="Stage"
                    item-title="text"
                    item-value="id"
                    density="compact"
                />
            </v-card-text>

            <v-divider></v-divider>

            <v-card-actions>
                <v-spacer></v-spacer>

                <v-btn
                    text="Close"
                    variant="plain"
                    @click="dialog = false"
                ></v-btn>

                <v-btn
                    :loading="loading"
                    text="Change"
                    color="#5865f2"
                    width="150"
                    class="text-white"
                    variant="flat"
                    @click="submit"
                ></v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
    <v-snackbar-queue
        ref="snackbarQueue"
        v-model="messages"
        :total-visible="5"
        closable
    ></v-snackbar-queue>
</template>
