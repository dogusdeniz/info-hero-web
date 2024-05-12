<script setup>
import { ArrowUpTrayIcon } from "@heroicons/vue/24/solid";
import axios from "axios";
import { ref } from "vue";
import Loading from "./Loading.vue";

const isLoading = ref(false);

// select file and upload
const uploadDocument = () => {
    const input = document.createElement("input");
    input.type = "file";
    input.accept = ".doc,.docx,.pdf,.csv";
    input.onchange = (e) => {
        isLoading.value = true;
        const file = e.target.files[0];

        const formData = new FormData();
        formData.append("file", file);
        axios
            .post(route("documents.store"), formData, {
                headers: {
                    "Content-Type": "multipart/form-data",
                },
            })
            .then((response) => {
                console.log(response);
                // reload the page
                location.reload();
                isLoading.value = false;
            })
            .catch((error) => {
                console.log(error);
                // alert the error
                alert("An error occurred while uploading the file.");
                isLoading.value = false;
            });
    };
    input.click();
};
</script>

<template>
    <Loading v-if="isLoading" />
    <div
        class="bg-primary justify-center dark:bg-neutral-800 overflow-hidden shadow-sm sm:rounded-xl"
    >
        <div
            class="p-3 text-white dark:text-gray-100 text-center h-60 cursor-pointer"
            @click="uploadDocument"
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
