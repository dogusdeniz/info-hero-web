<script setup>
import { ref } from "vue";
import {
    DocumentTextIcon,
    DocumentCheckIcon,
    TrashIcon,
} from "@heroicons/vue/24/outline";
import { usePage } from "@inertiajs/vue3";

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

    documents.value = documents.value.filter((doc) => doc.id !== document.id);
};

const uploadDocument = () => {
    const input = document.createElement("input");
    input.type = "file";
    input.accept = ".pdf";
    input.onchange = (e) => {
        const file = e.target.files[0];
        documents.value.push({
            id: documents.value.length + 1,
            name: file.name,
        });
        input.remove();
    };
    input.click();
};
</script>
<template>
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
                class="flex text-white text-sm mb-3"
                v-for="document in documents"
                :key="document.id"
            >
                <div
                    class="flex flex-1"
                    :class="
                        document.isSelected
                            ? 'bg-surface-secondary rounded-md'
                            : ''
                    "
                    @click="() => documentSelect(document)"
                >
                    <div class="mr-3 p-1">
                        <DocumentTextIcon
                            class="h-5 w-5"
                            v-if="!document.isSelected"
                        />
                        <DocumentCheckIcon
                            class="h-5 w-5"
                            v-if="document.isSelected"
                        />
                    </div>
                    <div class="overflow-hidden truncate p-1 select-none">
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
