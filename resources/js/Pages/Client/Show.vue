<script setup lang="ts">
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, usePage, Link } from "@inertiajs/vue3";
import { onMounted, onUnmounted, ref, computed } from "vue";
import { useAuth } from "@/composables/useAuth";
import axios from "axios";

const props = defineProps<{
    client: any;
    threads: any[];
    deals: any[];
    activities: any[];
}>();

const { hasRole } = useAuth();
onMounted(() => {});
</script>

<template>
    <Head title="Client" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ client.name }}
            </h2>
            <div class="mx-auto max-w-7xl">
                <v-toolbar class="bg-white">
                    <div>Email: {{ client.email }}</div>
                    <div class="ml-3">Phone: {{ client.phone }}</div>
                    <v-spacer></v-spacer>
                    <v-btn
                        class="text-none text-white text-body-large"
                        color="#5865f2"
                        size="large"
                        variant="flat"
                    >
                        New Deal
                    </v-btn>
                    <v-btn
                        class="text-none text-white text-body-large ml-3"
                        color="#5865f2"
                        size="large"
                        variant="flat"
                    >
                        New Chat
                    </v-btn>
                </v-toolbar>
            </div>
        </template>

        <div class="">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <v-row class="mt-5">
                    <v-col cols="6">
                        <div
                            class="overflow-hidden bg-white shadow-sm sm:rounded-lg p-4"
                        >
                            <h2 class="mb-4 font-semibold">Conversations</h2>

                            <div
                                v-for="thread in threads"
                                :key="thread.id"
                                class="mb-2"
                            >
                                {{ thread.subject }}

                                <span class="ml-2 text-xs">
                                    {{ thread.status }}
                                </span>
                            </div>
                        </div>
                    </v-col>
                    <v-col cols="6">
                        <div
                            class="overflow-hidden bg-white shadow-sm sm:rounded-lg p-4"
                        >
                            <h2 class="mb-4 font-semibold">Deals</h2>

                            <div
                                v-for="deal in deals"
                                :key="deal.id"
                                class="mb-2"
                            >
                                {{ deal.title }}

                                <span class="ml-2 text-xs">
                                    {{ deal.stage }}
                                </span>
                            </div>
                        </div>
                    </v-col>
                </v-row>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
