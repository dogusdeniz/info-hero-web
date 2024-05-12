<script setup>
import { ref } from "vue";
import {
    DocumentTextIcon,
    DocumentCheckIcon,
    TrashIcon,
} from "@heroicons/vue/24/outline";
import { usePage } from "@inertiajs/vue3";
import Loading from "./Loading.vue";
import axios from "axios";

const page = usePage();

const documents = ref(page.props.documents ?? []);

const documentSelect = (document) => {
    document.isSelected = !document.isSelected;
};

const documentRemove = (document) => {
    // confirm dialog
    if (!confirm("Are you sure you want to delete this document?")) {
        return;
    }

    // remove the document
    axios
        .delete(route("documents.destroy", document.id))
        .then((response) => {
            // reload the page
            location.reload();
        })
        .catch((error) => {
            console.log(error);
            alert("An error occurred while deleting the document.");
        });
};

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
    <div>
        <span
            class="block px-1 pt-1 text-sm font-medium text-white focus:outline-none focus:border-indigo-700 transition duration-150 ease-in-out"
        >
            Documents
            <span
                class="bg-avatar px-2 py-1 rounded cursor-pointer select-none active:border-white active:border"
                @click="uploadDocument"
            >
                +
            </span>
        </span>

        <ul class="mt-5 px-1 pt-1 max-h-[720px] overflow-auto">
            <li v-if="documents.length == 0">
                <div class="text-center text-gray-500">
                    You don't have a document
                </div>
            </li>
            <li
                class="flex overflow-x-hidden text-white text-sm mb-3"
                v-for="document in documents"
                :key="document.id"
            >
                <div
                    class="flex flex-1 w-5/6"
                    :class="
                        document.isSelected
                            ? 'bg-surface-secondary rounded-md'
                            : ''
                    "
                    @click="() => documentSelect(document)"
                >
                    <div class="p-1">
                        <DocumentTextIcon
                            class="h-5 w-5"
                            v-if="!document.isSelected"
                        />
                        <DocumentCheckIcon
                            class="h-5 w-5"
                            v-if="document.isSelected"
                        />
                    </div>
                    <div
                        class="overflow-hidden truncate p-1 select-none w-full"
                    >
                        {{ document.name }}
                    </div>
                </div>
                <div
                    class="ml-auto bg-avatar rounded p-1 active:border cursor-pointer"
                    @click="() => documentRemove(document)"
                >
                    <TrashIcon class="h-5 w-5" />
                </div>
            </li>
        </ul>
    </div>
</template>
