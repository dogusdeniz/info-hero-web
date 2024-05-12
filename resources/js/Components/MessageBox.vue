<script setup>
import { nextTick, ref } from "vue";

const { modelValue } = defineProps({
    modelValue: String,
});

const internalValue = ref(modelValue ?? "");

const emit = defineEmits(["submit", "update:modelValue"]);

const updateModelValue = (event) => {
    internalValue.value = event.target.value;
    emit("update:modelValue", event.target.value);
};

const submit = () => {
    if (internalValue.value.trim() === "") {
        return;
    }

    // remove  empty newlines after senteces before emitting
    internalValue.value = internalValue.value.trim();

    emit("submit", internalValue.value);
};

nextTick(() => {
    const textarea = document.getElementById("prompt-textarea");
    const sendButton = document.querySelector("#send-button");

    function reset() {
        textarea.value = "";
        textarea.style.height = "52px";
        textarea.style.overflowY = "hidden";
        sendButton.disabled = true;
    }

    textarea.addEventListener("keydown", (event) => {
        if (event.key === "Enter" && !event.shiftKey) {
            event.preventDefault();
            submit();
            reset();
        }
    });

    textarea.addEventListener("input", () => {
        textarea.style.height = "auto";
        textarea.style.height = textarea.scrollHeight + "px";
        const lineHeight = getComputedStyle(textarea).lineHeight;
        const maxRowHeight = 5 * parseFloat(lineHeight);

        if (textarea.scrollHeight > maxRowHeight) {
            textarea.style.overflowY = "auto";
        } else {
            textarea.style.overflowY = "hidden";
        }
        sendButton.disabled = textarea.value.trim() === "";
    });

    sendButton.addEventListener("click", () => {
        reset();
    });
});
</script>
<template>
    <div class="py-2 px-3 text-base md:px-4 m-auto lg:px-1 xl:px-5">
        <div
            class="mx-auto flex flex-1 gap-3 text-base juice:gap-4 juice:md:gap-6 md:max-w-3xl lg:max-w-[40rem] xl:max-w-[48rem]"
        >
            <div class="flex w-full items-end">
                <div
                    class="overflow-hidden flex flex-col w-full flex-grow relative border border-neutral-700 dark:text-white rounded-2xl"
                >
                    <textarea
                        id="prompt-textarea"
                        tabindex="0"
                        dir="auto"
                        rows="1"
                        placeholder="Message Info Hero"
                        class="text-white m-0 w-full resize-none border-0 bg-transparent focus:ring-0 focus-visible:ring-0 dark:bg-transparent py-[10px] pr-10 md:py-3.5 md:pr-12 max-h-[133px] placeholder-white/50 dark:placeholder-white/50 pl-4 md:pl-6"
                        style="height: 52px; overflow-y: hidden"
                        :value="internalValue"
                        @input="updateModelValue"
                    ></textarea>
                    <button
                        disabled
                        class="absolute bottom-1.5 right-2 rounded-lg border border-black bg-white p-0.5 text-black transition-colors enabled:bg-white disabled:text-gray-400 disabled:opacity-10 dark:border-white dark:bg-white dark:hover:bg-white md:bottom-3 md:right-3"
                        id="send-button"
                        @click="submit"
                    >
                        <span class="" data-state="closed"
                            ><svg
                                width="24"
                                height="24"
                                viewBox="0 0 24 24"
                                fill="none"
                                class="text-black dark:text-black"
                            >
                                <path
                                    d="M7 11L12 6L17 11M12 18V7"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                ></path>
                            </svg>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div
        class="relative px-2 py-2 text-center text-xs text-token-text-secondary md:px-[60px]"
    >
        <span>Info Hero can make mistakes. Check important info.</span>
    </div>
</template>