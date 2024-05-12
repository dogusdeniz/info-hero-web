<script setup>
import { defineProps, watch } from "vue";
import Markdown from "./Markdown.vue";

defineProps({
    name: String,
    avatar: String,
    message: String,
    messageId: String,
    messageAuthorRole: String,
});
</script>
<template>
    <div>
        <div class="p-3 text-base md:px-4 m-auto lg:px-1 xl:px-5">
            <div
                class="mx-auto flex flex-1 gap-3 text-base md:max-w-3xl lg:max-w-[40rem] xl:max-w-[48rem]"
            >
                <div class="flex-shrink-0 flex flex-col relative items-end">
                    <div>
                        <div class="pt-0.5">
                            <div
                                class="hermit-shadow-stroke flex h-6 w-6 items-center justify-center overflow-hidden rounded-full"
                            >
                                <div class="relative flex">
                                    <img
                                        alt="User"
                                        referrerpolicy="no-referrer"
                                        loading="lazy"
                                        width="24"
                                        height="24"
                                        decoding="async"
                                        class="rounded-sm text-primary"
                                        :src="avatar"
                                        style="color: transparent"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="relative flex w-full min-w-0 flex-col">
                    <div class="font-semibold select-none mb-5">{{ name }}</div>
                    <div class="flex-col gap-1 md:gap-3">
                        <div class="flex flex-grow flex-col max-w-full">
                            <div
                                :data-message-author-role="messageAuthorRole"
                                :data-message-id="messageId"
                                dir="auto"
                                class="min-h-[20px] text-message flex flex-col items-start whitespace-pre-wrap break-words overflow-x-auto gap-3"
                            >
                                <div class="relative max-w-[90%]">
                                    <div v-if="messageAuthorRole == 'user'">
                                        {{ message }}
                                    </div>
                                    <Markdown v-else :value="message" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
