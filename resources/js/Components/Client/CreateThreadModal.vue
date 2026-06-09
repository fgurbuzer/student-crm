<script setup lang="ts">
import { useForm } from "@inertiajs/vue3";
import { ref } from "vue";
const emit = defineEmits(["close"]);

const form = useForm({
    subject: "",
    message: "",
});

const snackbarQueue = ref();
const messages = ref([]);

function addMessage(color, text) {
    messages.value.push({
        text: text,
        color,
    });
}

const dialog = ref(false);
const loading = ref(false);
const submit = () => {
    loading.value = true;
    form.post("/client/threads", {
        onSuccess: () => {
            loading.value = false;
        },
    });
};
</script>

<template>
    <v-dialog v-model="dialog" max-width="600">
        <template v-slot:activator="{ props: activatorProps }">
            <v-btn
                class="text-none text-white font-weight-regular mt-5"
                text="Start Conversation"
                color="#5865f2"
                width="200"
                variant="flat"
                v-bind="activatorProps"
            ></v-btn>
        </template>

        <v-card color="white" title="Start Conversation">
            <v-card-text>
                <v-row density="comfortable">
                    <v-col cols="12" md="6" sm="6">
                        <v-text-field
                            label="Subject"
                            v-model="form.subject"
                            required
                        ></v-text-field>
                    </v-col>

                    <v-col cols="12" md="6" sm="6">
                        <v-text-field
                            v-model="form.message"
                            label="Message"
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
                    text="Send"
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
