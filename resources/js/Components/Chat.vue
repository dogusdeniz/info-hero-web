<script setup>
import { usePage } from "@inertiajs/vue3";
import { nextTick, reactive, ref, watch } from "vue";
import Message from "./Message.vue";
import MessageBox from "./MessageBox.vue";
import Welcome from "./Welcome.vue";
import SHA256 from "crypto-js/sha256";
import Cookies from "js-cookie";
import SSE from "@/Utils/server-sent-event";
import DocumentController from "@/Utils/document-controller";

const page = usePage();

const messageHistory = ref([]);
const message = ref("");
const avatar = ref(null);
const submitable = ref(true);
const stopable = ref(false);
const sse = ref(null);

const getGravatar = (email) => {
    // Step 1: Hash your email address using SHA-256.
    const hashedEmail = SHA256(email);
    // Step 2: Construct the Gravatar URL.
    const gravatarUrl = `https://www.gravatar.com/avatar/${hashedEmail}`;

    return gravatarUrl;
};

const submitChat = (value) => {
    if (avatar.value == null)
        avatar.value = getGravatar(page.props.auth.user.email);

    submitable.value = false;
    stopable.value = true;

    const question = {
        // generate a random id
        messageId: Math.random().toString(36).substring(2, 15),
        name: "You",
        avatar: avatar.value,
        message: value,
        messageAuthorRole: "user",
    };

    messageHistory.value.push(question);

    const answer = {
        // generate a random id
        messageId: Math.random().toString(36).substring(2, 15),
        name: "Info Hero",
        avatar: "/storage/images/logo.svg",
        message: "",
        messageAuthorRole: "assistant",
    };

    messageHistory.value.push(answer);

    const url = route("chat");

    const csrf = Cookies.get("XSRF-TOKEN");

    const documents = DocumentController.getSelectedDocuments();

    sse.value = new SSE({
        "Content-Type": "application/json",
        "X-XSRF-TOKEN": csrf,
    });

    sse.value.on("data", (data) => {
        const text = data
            .replace(/\\n/g, "\n")
            .replace(/\\t/g, "\t")
            .replace(/\\r/g, "\r");

        // update asnwer in the message history
        const index = messageHistory.value.findIndex(
            (msg) => msg.messageId === answer.messageId
        );

        messageHistory.value[index].message += text;
    })
        .on("error", (error) => {
            console.log("error", error);

            submitable.value = true;
            stopable.value = false;
        })
        .on("end", () => {
            console.log("end");

            submitable.value = true;
            stopable.value = false;
        })
        .post(url, {
            prompt: value,
            contextFilter: {
                docs_ids: documents.map((document) => document.uuid),
            },
        });
};

const stopChat = () => {
    console.info("Chat stopped");

    sse.value.close();

    submitable.value = true;
    stopable.value = false;
};
</script>

<template>
    <div class="flex flex-col h-full">
        <div class="flex-1">
            <div id="chat" class="relative h-full">
                <Welcome v-if="messageHistory.length == 0" />
                <Message
                    v-for="(msg, index) in messageHistory"
                    :key="index"
                    :name="msg.name"
                    :avatar="msg.avatar"
                    :message-author-role="msg.messageAuthorRole"
                    :message-id="msg.messageId"
                    :message="msg.message"
                />
            </div>
        </div>
        <div>
            <MessageBox
                @submit="submitChat"
                @stop="stopChat"
                v-model="message"
                :submitable="submitable"
                :stopable="stopable"
            />
        </div>
    </div>
</template>
