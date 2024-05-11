<script setup>
import { ref, watch } from "vue";
import Message from "./Message.vue";
import MessageBox from "./MessageBox.vue";


const messageHistory = ref([
    {
        name: "You",
        avatar: "https://lh3.googleusercontent.com/a/AEdFTp4akbZbEuEZsS2GlDHq_1YCI9jR0Md1SP8hsvyBIA=s96-c",
        message: "Message : custome textarea component in html and vue",
    },
    {
        name: "Info Hero",
        avatar: "https://lh3.googleusercontent.com/a/AEdFTp4akbZbEuEZsS2GlDHq_1YCI9jR0Md1SP8hsvyBIA=s96-c",
        message: "Info Hero can make mistakes. Check important info.",
    },
]);

const message = ref("");

const submitChat = (value) => {
    messageHistory.value.push({
        name: "You",
        avatar: "https://lh3.googleusercontent.com/a/AEdFTp4akbZbEuEZsS2GlDHq_1YCI9jR0Md1SP8hsvyBIA=s96-c",
        message: value,
    });
};
</script>

<template>
    <div class="flex flex-col h-full">
        <div class="flex-1">
            <div class="relative h-full">
                <Message
                    v-for="msg in messageHistory"
                    :key="msg.message"
                    :name="msg.name"
                    :avatar="msg.avatar"
                    :message="msg.message"
                />
            </div>
        </div>
        <div>
            <MessageBox @submit="submitChat" v-model="message" />
        </div>
    </div>
</template>
