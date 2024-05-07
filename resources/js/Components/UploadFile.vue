<script setup>
import { ArrowUpTrayIcon } from "@heroicons/vue/24/solid";
</script>

<template>
    <div
        class="bg-primary justify-center dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-xl"
    >
        <div
            class="p-3 text-white dark:text-gray-100 text-center h-60 cursor-pointer"
        >
            <div
                class="border-dashed border-2 border-neutral-500 p-6 h-full rounded-md select-none"
            >
                <div class="text-sm">Upload your document here</div>
                <div class="text-xs">.doc .docx .pdf .csv</div>
                <div class="p-6">
                    <ArrowUpTrayIcon class="h-24 w-24 mx-auto text-zinc-900" />
                </div>
            </div>
        </div>
    </div>
</template>
