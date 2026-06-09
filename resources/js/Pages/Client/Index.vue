<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import api from "@/services/axios";
import {
    ref,
    computed,
    watch,
    onMounted,
    onBeforeUnmount,
    reactive,
} from "vue";

const headers = [
    { title: "ID", key: "id" },
    { title: "Client", key: "name" },
    { title: "Email", key: "email" },
    { title: "Target Country", key: "user_info.target_country" },

    { title: "Action", key: "actions" },
];
const items = ref([]);
const loading = ref(false);
const totalItems = ref(0);
const itemsPerPage = ref(5);
const search = ref("");

const options = ref({
    page: 1,
    itemsPerPage: 10,
});

const loadClients = async (newOptions?: any) => {
    if (newOptions) {
        options.value = newOptions;
    }

    loading.value = true;
    try {
        const { data } = await api.post("/clients/list", {
            page: options.value.page,
            itemsPerPage: options.value.itemsPerPage,
            search: search.value,
        });
        console.log(data.data);
        items.value = data.data;
        totalItems.value = data.total;
    } finally {
        loading.value = false;
    }
};

watch(options, loadClients, { deep: true });
onMounted(() => {
    loadClients();
});
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Clients
            </h2>
        </template>
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <v-data-table-server
                    class="bg-white text-gray-800"
                    :headers="headers"
                    :items="items"
                    :items-length="totalItems"
                    :loading="loading"
                    loading-text="Loading... Please wait"
                    :search="search"
                    item-value="id"
                    @update:options="loadClients"
                    v-model:items-per-page="itemsPerPage"
                >
                    <template v-slot:item.actions="{ item }">
                        <v-btn
                            variant="text"
                            icon
                            :href="route('clients.show', item.id)"
                        >
                            <v-icon>mdi-pencil</v-icon>
                        </v-btn>
                    </template>
                </v-data-table-server>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
