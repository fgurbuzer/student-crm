<script setup lang="ts">
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

import MessageComposer from "@/components/client/MessageComposer.vue";

defineProps<{
    thread: any;
}>();
</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ thread.subject }}
            </h2>
            <div class="text-sm text-gray-500">Status: {{ thread.status }}</div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="flex h-[calc(100vh-400px)] flex-col">
                    <MessageComposer :thread-id="thread.id" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
