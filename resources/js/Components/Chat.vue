<script setup>
import { nextTick, ref, watch } from "vue";
import Message from "./Message.vue";
import MessageBox from "./MessageBox.vue";
import Welcome from "./Welcome.vue";

const messageHistory = ref([
    // {
    //     id: "as12-asd123-asd123-asd123-asd123",
    //     name: "You",
    //     avatar: "https://lh3.googleusercontent.com/a/AEdFTp4akbZbEuEZsS2GlDHq_1YCI9jR0Md1SP8hsvyBIA=s96-c",
    //     message: "Message : custome textarea component in html and vue",
    //     messageAuthorRole: "user",
    // },
    // {
    //     id: "as124-asd123-asd123-asd123-asd123",
    //     name: "Info Hero",
    //     avatar: "https://lh3.googleusercontent.com/a/AEdFTp4akbZbEuEZsS2GlDHq_1YCI9jR0Md1SP8hsvyBIA=s96-c",
    //     message: "Info Hero can make mistakes. Check important info.",
    //     messageAuthorRole: "user",
    // },
]);

const message = ref("");

const submitChat = (value) => {
    const question = {
        // generate a random id
        id: Math.random().toString(36).substring(2, 15),
        name: "You",
        avatar: "https://lh3.googleusercontent.com/a/AEdFTp4akbZbEuEZsS2GlDHq_1YCI9jR0Md1SP8hsvyBIA=s96-c",
        message: value,
    };

    messageHistory.value.push(question);

    const answer = {
        // generate a random id
        id: Math.random().toString(36).substring(2, 15),
        name: "Info Hero",
        avatar: "https://lh3.googleusercontent.com/a/AEdFTp4akbZbEuEZsS2GlDHq_1YCI9jR0Md1SP8hsvyBIA=s96-c",
        message: "",
    };

    messageHistory.value.push(answer);

    const source = new EventSource(
        "http://info-hero.local/api/chat?prompt=" + value
    );

    source.addEventListener("update", (event) => {
        // close connection if we receive the end event
        if (event.data === "<END_STREAMING_SSE>") {
            source.close();
            return;
        }

        var text = event.data
            .replace(/\\n/g, "\n")
            .replace(/\\t/g, "\t")
            .replace(/\\r/g, "\r");

        messageHistory.value[messageHistory.value.length - 1].message += text;

        console.log("update", text);

        // Trigger Vue reactivity to update the message in the template
        nextTick();
    });

    source.addEventListener("error", (event) => {
        console.log("error", event);
        source.close();
    });
};

const stopChat = () => {
    console.info("Chat stopped");
};
</script>

<template>
    <div class="flex flex-col h-full">
        <div class="flex-1">
            <div id="chat" class="relative h-full">
                <Welcome v-if="messageHistory.length == 0" />
                <Message
                    v-for="msg in messageHistory"
                    :key="msg.message"
                    :name="msg.name"
                    :avatar="msg.avatar"
                    :message="msg.message"
                    :message-author-role="msg.messageAuthorRole"
                    :message-id="msg.id"
                />
            </div>
        </div>
        <div>
            <MessageBox
                @submit="submitChat"
                @stop="stopChat"
                v-model="message"
                :submitable="true"
                :stopable="false"
            />
        </div>
    </div>
</template>
