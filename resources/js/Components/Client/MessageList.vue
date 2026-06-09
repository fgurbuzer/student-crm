<script setup lang="ts">
import axios from "axios";
import { onMounted, onUnmounted, ref } from "vue";

import { supabase } from "@/lib/supabase";

const props = defineProps<{
    threadId: number;
}>();

const messages = ref([]);

const loadMessages = async () => {
    try {
        const { data } = await axios.get(
            `/client/threads/${props.threadId}/messages`,
            [],
            {
                headers: {
                    "Content-Type": "multipart/form-data",
                },
            },
        );
        messages.value = data.data;
        console.log(messages.value);
    } catch (error) {
        console.error(error);
    }
};

let channel: any;

onMounted(() => {
    channel = supabase
        .channel(`thread-${props.threadId}`)
        .on(
            "postgres_changes",
            {
                event: "INSERT",
                schema: "public",
                table: "messages",
                filter: `thread_id=eq.${props.threadId}`,
            },
            (payload) => {
                console.log(payload);

                //messages.value.push(payload.new);
            },
        )
        .subscribe((status) => {
            console.log("SUPABASE STATUS:", status);
        });
    loadMessages();
});

onUnmounted(() => {
    supabase.removeChannel(channel);
});
</script>

<template>
    <div class="flex-1 overflow-y-auto p-4">
        <div v-for="message in messages" :key="message.id" class="mb-4">
            <div class="text-xs text-gray-500">
                {{ message.sender.name }}
            </div>

            <div class="text-gray-500 rounded border p-3">
                {{ message.body }}
            </div>
        </div>
    </div>
</template>
