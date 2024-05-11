<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import UploadFile from "@/Components/UploadFile.vue";
import Chat from "@/Components/Chat.vue";
import { computed, nextTick } from "vue";

const { user, files } = defineProps({
    user: Object,
    files: Array,
});

const anyFiles = computed(() => files.length > 0);

</script>

<template>
    <Head title="Chat" />

    <AuthenticatedLayout>
        <div class="py-12 h-full">
            <div class="max-w-7xl h-full mx-auto sm:px-6 lg:px-8">
                <Chat v-if="anyFiles" :files="files" />
                <UploadFile v-else />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
