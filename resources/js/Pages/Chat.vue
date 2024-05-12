<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, usePage } from "@inertiajs/vue3";
import UploadFile from "@/Components/UploadFile.vue";
import Chat from "@/Components/Chat.vue";
import { computed, nextTick } from "vue";

const page = usePage();

const documents = page.props.documents ?? [];

const anyFiles = computed(() => documents.length > 0);

</script>

<template>
    <Head title="Chat" />

    <AuthenticatedLayout>
        <div class="pt-12 h-full">
            <div class="max-w-7xl h-full mx-auto sm:px-6 lg:px-8">
                <Chat v-if="anyFiles" />
                <UploadFile v-else />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
