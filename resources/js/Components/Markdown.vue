<script setup>
import { nextTick, ref, watch } from "vue";
import { micromark } from "micromark";
import { gfm, gfmHtml } from "micromark-extension-gfm";
import { math, mathHtml } from "micromark-extension-math";
import hljs from 'highlight.js';
import 'highlight.js/styles/github-dark.min.css';

const props = defineProps({
    value: {
        type: String,
        required: true,
    },
});

const renderedValue = ref(false);
renderedValue.value = micromark(props.value, {
    extensions: [gfm(), math()],
    htmlExtensions: [gfmHtml(), mathHtml()],
});

nextTick(() => {
    hljs.highlightAll();
});

watch(() => props.value, (newValue) => {
    renderedValue.value = micromark(newValue, {
        extensions: [gfm(), math()],
        htmlExtensions: [gfmHtml(), mathHtml()],
    });
    nextTick(() => {
        hljs.highlightAll();
    });
});
</script>
<template>
    <div class="markdown prose w-full break-words dark:prose-invert dark">
        <div v-html="renderedValue"></div>
    </div>
</template>
