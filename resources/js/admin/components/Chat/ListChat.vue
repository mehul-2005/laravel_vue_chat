<template>
    <Page_loader :loader="loader" />
    <Page_header page_name="Chat" />
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div :class="`col-md-${active_class !== null ? '3' : '12'}`">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Users</h3>
                        </div>

                        <div class="card-body p-0">
                            <ul
                                class="nav nav-pills flex-column"
                                v-if="users.length > 0"
                            >
                                <li
                                    class="nav-item active"
                                    v-for="user in users"
                                    :key="user.id"
                                >
                                    <a
                                        v-if="authUserId !== user.id"
                                        href="#"
                                        @click="userOption(user.id)"
                                        :class="`nav-link ${
                                            active_class !== ''
                                                ? user.id === active_class
                                                    ? 'active'
                                                    : ''
                                                : ''
                                        }`"
                                    >
                                        <i class="fas fa-user"></i>
                                        {{ user.name }}
                                        <span
                                            v-if="readCount[user.id] > 0"
                                            class="badge bg-primary float-right"
                                            >{{ readCount[user.id] }}</span
                                        >
                                    </a>
                                </li>
                            </ul>
                            <ul class="nav nav-pills flex-column" v-else>
                                <li class="nav-item active">User not found</li>
                            </ul>
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>
                <!-- /.col -->

                <div class="col-md-9" v-if="active_class !== null">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">
                                {{ active_class_user.name }}
                            </h3>
                            <div class="float-right">
                                <span
                                    class="d-flex align-items-center"
                                    v-if="
                                        active_class_user.online_status ===
                                        'Online'
                                    "
                                >
                                    <i
                                        class="fa fa-circle mx-1"
                                        style="font-size: 12px; color: green"
                                    ></i>

                                    Online
                                    <span
                                        v-if="typeCheck"
                                        style="font-size: 12px"
                                        >(Typing...)</span
                                    >
                                </span>
                                <span v-else class="d-flex align-items-center">
                                    {{ active_class_user.online_status }}
                                </span>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="mailbox-read-message">
                                <div class="row align-items-center">
                                    <div
                                        class="col-12 scroller"
                                        style="
                                            overflow-y: scroll;
                                            max-height: 50vh;
                                        "
                                        ref="scrollTop"
                                    >
                                        <span
                                            class="col-12"
                                            v-show="chats?.length > 0"
                                            v-for="chat in chats"
                                            :key="chat.id"
                                        >
                                            <div
                                                v-if="
                                                    chat.sender_id !==
                                                        authUserId &&
                                                    chat.receiver_id ===
                                                        authUserId
                                                "
                                                class="col-6 p-2 m-2 rounded"
                                                style="
                                                    box-shadow: 0px 0px 10px 1px
                                                        #888888;
                                                "
                                            >
                                                <p
                                                    class="pl-1"
                                                    style="
                                                        font-size: 17px;
                                                        font-weight: bold;
                                                    "
                                                >
                                                    {{
                                                        chat.deleted !== "2"
                                                            ? chat.message
                                                            : "This message was deleted by user"
                                                    }}
                                                </p>

                                                <div
                                                    style="
                                                        font-size: 12px;
                                                        font-style: italic;
                                                    "
                                                >
                                                    {{ chat.created_at }}
                                                </div>
                                            </div>

                                            <div
                                                v-if="
                                                    chat.sender_id ===
                                                        authUserId &&
                                                    chat.receiver_id !==
                                                        authUserId
                                                "
                                                class="col-6 bg-gradient-secondary p-2 m-2 rounded ml-auto"
                                                style="
                                                    box-shadow: 0px 0px 10px 1px
                                                        #ffffff;
                                                "
                                            >
                                                <p
                                                    class="pl-1"
                                                    :style="
                                                        chat.deleted === '0'
                                                            ? {
                                                                  'font-size':
                                                                      '17px',
                                                                  'font-weight':
                                                                      'bold',
                                                              }
                                                            : {
                                                                  'font-size':
                                                                      '15px',
                                                                  'font-weight':
                                                                      'normal',
                                                                  'font-style':
                                                                      'italic',
                                                              }
                                                    "
                                                >
                                                    {{
                                                        chat.deleted === "0"
                                                            ? chat.message
                                                            : "You deleted this message"
                                                    }}
                                                </p>
                                                <div
                                                v-if="chat.deleted === '0'"
                                                    style="
                                                        font-size: 12px;
                                                        font-style: italic;
                                                    "
                                                >
                                                    {{ chat.created_at }}
                                                    <i
                                                        class="fa fa-check ml-1"
                                                    ></i>
                                                    <i
                                                        class="fa fa-check text-success"
                                                        v-if="chat.read"
                                                    ></i>
                                                    <i
                                                        type="button"
                                                        class="fas fa-trash float-right ml-1"
                                                        title="Delete"
                                                        @click="
                                                            deleteChatCall(
                                                                chat.id,
                                                                chat.original_date
                                                            )
                                                        "
                                                    >
                                                    </i>
                                                </div>
                                            </div>
                                        </span>
                                        <span
                                            v-show="chats?.length < 1"
                                            class="col-12 text-center"
                                            style="overflow: hidden !important"
                                        >
                                            Start new conversation
                                        </span>
                                    </div>
                                    <div class="col-12">
                                        <div
                                            class="d-flex"
                                            style="
                                                align-items: end;
                                                justify-content: center;
                                            "
                                        >
                                            <input
                                                type="text"
                                                class="form-control"
                                                placeholder="Message"
                                                style="
                                                    position: relative;
                                                    height: 50px;
                                                    border-top-right-radius: 50px;
                                                    border-bottom-right-radius: 50px;
                                                "
                                                v-model.trim="messageData"
                                                @keyup="typingCheck"
                                                @keyup.enter="sendMessage"
                                            />

                                            <button
                                                class="btn btn-primary mx-1"
                                                style="
                                                    position: absolute;
                                                    right: 0.5rem;
                                                    border-radius: 50px;
                                                    bottom: 0.4rem;
                                                "
                                                @click="sendMessage"
                                            >
                                                <i
                                                    class="fa fa-paper-plane"
                                                ></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.mailbox-read-message -->
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <hr />
            <list-chat-group />
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
</template>

<script setup>
import {
    computed,
    onMounted,
    onUnmounted,
    reactive,
    ref,
    watch,
    inject,
} from "vue";
import Page_header from "../../includes/Page_header.vue";
import axios from "axios";
import { useStore } from "vuex";
import Page_loader from "../../includes/Page_loader.vue";
import _ from "lodash";

import ListChatGroup from "./ListChatGroup.vue";

const Swal = inject("$swal");
const store = useStore();
const authUser = computed(() => store.getters.getAuthUser);
const authUserId = authUser.value.id;
const users = ref({});
const readCount = ref(null);
const chats = ref(null);
const active_class = ref(null);
const active_class_user = ref(null);
const messageData = ref("");
const scrollTop = ref();
const loader = ref(false);

const scrollToBottom = () => {
    setTimeout(() => {
        if (scrollTop.value) {
            scrollTop.value.scrollTop = scrollTop.value.scrollHeight;
            loader.value = false;
        }
    }, 1000);
};

const userOption = (user, loaderLoad = true) => {
    getReadCount();
    active_class.value = user;
    loader.value = loaderLoad;
    active_class_user.value = users.value.filter((usr) => usr.id === user)[0];
    const formData = reactive({
        id: user,
    });
    axios
        .post(`/admin_vue/get_chat`, formData)
        .then((res) => (chats.value = res.data));
    scrollToBottom();
    typingListen();
};
const typeCheck = ref(false);
const typingListen = () => {
    window.Echo.channel(`typing${active_class.value}`).listen(
        ".IsTyping",
        (e) => {
            if (e.channelName == active_class.value) {
                typeCheck.value = true;
                setTimeout(() => {
                    typeCheck.value = false;
                }, 1000);
            }
        }
    );
};

const sendMessage = () => {
    if (messageData.value !== "") {
        const formData = reactive({
            receiver_id: active_class.value,
            message: messageData.value,
        });
        axios
            .post(`/admin_vue/chat`, formData)
            .then((res) => (messageData.value = ""));
        userOption(active_class.value, false);
    }
};
const audioRec = new Audio("/mp3/sms.mp3");
const audioSend = new Audio("/mp3/sms3.mp3");
const getReadCount = async () => {
    await axios
        .get(`/admin_vue/get_chat_read_count`)
        .then((res) => (readCount.value = res.data));
};
const onlineStatus = async (statuss) => {
    const obj = reactive({
        status: statuss,
    });
    await axios.post(`/admin_vue/user_online_status`, obj);
};
const onlineStatusHandle = _.debounce(function (status) {
    onlineStatus(status);
}, 3000);

const getUserData = async (update = false) => {
    await axios.get(`/admin_vue/user`).then((res) => (users.value = res.data));
    if (update) {
        active_class_user.value = users.value.filter(
            (usr) => usr.id === active_class.value
        )[0];
    }
};
const typingCheck = _.debounce(function () {
    let authUser = authUserId;
    let typeClass = active_class.value;
    axios.get(`/admin_vue/typing/${authUser}/${typeClass}`);
}, 1000);

const deleteChatCall = (id, dateCheck) => {
    var d1 = new Date();
    var d2 = new Date(dateCheck);
    d2.setDate(d2.getDate() + 2);
    console.log(d1 < d2);
    let CancelBtn = d1 < d2 ? true : false;
    Swal.fire({
        title: "Do you want to delete this message?",
        showDenyButton: CancelBtn,
        showCancelButton: true,
        confirmButtonText: "Delete for me",
        denyButtonText: `Delete for everyone`,
    }).then((result) => {
        if (result.isConfirmed) {
            let update = chats.value.filter((data) => data.id === id);
            update.map((chat) => (chat.deleted = "1"));
            deleteData(id, "1");
        } else if (result.isDenied) {
            let update = chats.value.filter((data) => data.id === id);
            update.map((chat) => (chat.deleted = "2"));
            deleteData(id, "2");
        }
    });
    const deleteData = (chatId, deleteData) => {
        const deleteFormData = reactive({
            id: chatId,
            deleted: deleteData,
        });
        let res = axios.post(`/admin_vue/delete_chat_message`, deleteFormData);
        return res ? true : false;
    };
};

onMounted(async () => {
    loader.value = true;
    getUserData();
    getReadCount();
    await window.Echo.channel("chat").listen(".message", (e) => {
        setTimeout(() => {
            typeCheck.value = false;
            userOption(active_class.value, false);
            getReadCount();
            if (
                e.sender_id !== authUserId &&
                e.receiver_id === authUserId &&
                active_class.value !== null
            ) {
                audioRec.play();
            }
            if (e.sender_id === authUserId && active_class.value !== null) {
                audioSend.play();
            }
        }, 500);
    });
    await window.Echo.channel("chatRead").listen(".read", (e) => {
        userOption(active_class.value, false);
    });
    await window.Echo.channel("onlineReport").listen(".online", (e) => {
        getUserData(true);
    });

    window.Echo.connector.pusher.connection.bind(
        "state_change",
        function (states) {
            if (states.current === "disconnected") {
                window.Echo.connector.pusher.connect();
            }
        }
    );
    await onlineStatus(1);
    loader.value = false;
});
onMounted(() => {
    $(window).bind("unload", function () {
        onlineStatus(0);
    });
    $(window).bind("blur", function () {
        onlineStatusHandle(0);
    });
    $(window).bind("focus", function () {
        onlineStatusHandle(1);
    });
    // $(window).bind("offline", function () {
    // });
});
onUnmounted(() => {
    active_class.value = null;
    onlineStatus(0);
    $(window).unbind("blur focus");
});
</script>

<style>
.scroller {
    scroll-behavior: smooth !important;
    display: flex;
    flex-direction: column-reverse;
}
</style>
