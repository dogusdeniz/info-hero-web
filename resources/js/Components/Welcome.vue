<script setup>
import { DocumentChartBarIcon } from "@heroicons/vue/24/outline";

</script>
<template>
    <div class="p-3 text-base md:px-4 m-auto h-full lg:px-1 xl:px-5">
        <div
            class="mx-auto flex flex-col gap-3 h-full text-center items-center justify-center md:max-w-3xl lg:max-w-[40rem] xl:max-w-[48rem]"
        >
            <div class="mb-3 h-12 w-12">
                <div
                    class="hermit-shadow-stroke relative flex h-full items-center justify-center rounded-full bg-token-main-surface-primary text-token-text-primary"
                >
                    <DocumentChartBarIcon class="h-6 w-6" />
                </div>
            </div>
            <p class="mb-5 text-2xl font-medium">How can I help you today?</p>
        </div>
    </div>
</template>
