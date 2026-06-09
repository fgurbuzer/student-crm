<script setup lang="ts">
import { useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import axios from "axios";

const emit = defineEmits(["close"]);

const props = defineProps<{
    thread: Object;
}>();

const title = ref("");
const value = ref(null);
const loading = ref(false);
const dialog = ref(false);

const snackbarQueue = ref();
const messages = ref([]);

function addMessage(color, text) {
    messages.value.push({
        text: text,
        color,
    });
}

const createDeal = async () => {
    loading.value = true;
    try {
        const { data } = await axios.post(
            `/sales/threads/${props.thread.id}/deals`,
            {
                title: title.value,
                value_amount: value.value,
            },
        );
        title.value = "";
        value.value = "";
        loading.value = false;
        //emit("close");
        dialog.value = false;
        addMessage("success", "Deal created successfully!");
    } catch (error) {
        console.error(error);
    }
};
</script>

<template>
    <v-dialog v-model="dialog" max-width="600">
        <template v-slot:activator="{ props: activatorProps }">
            <v-btn
                class="text-none text-white font-weight-regular mt-5"
                text="Create Deal"
                color="#5865f2"
                width="200"
                variant="flat"
                v-bind="activatorProps"
            ></v-btn>
        </template>

        <v-card color="white" title="Create Deal">
            <v-card-text>
                <v-row density="comfortable">
                    <v-col cols="12" md="6" sm="6">
                        <v-text-field
                            label="Deal title"
                            v-model="title"
                            required
                        ></v-text-field>
                    </v-col>

                    <v-col cols="12" md="6" sm="6">
                        <v-text-field
                            v-model="value"
                            type="number"
                            label="Value"
                        ></v-text-field>
                    </v-col>
                </v-row>

                <small class="text-body-small text-medium-emphasis"
                    >*indicates required field</small
                >
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
                    text="Create"
                    class="text-white"
                    color="#5865f2"
                    width="150"
                    variant="flat"
                    @click="createDeal"
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
