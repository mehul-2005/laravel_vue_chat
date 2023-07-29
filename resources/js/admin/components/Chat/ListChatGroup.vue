<template>
    <!-- /.card -->
    <Page_loader :loader="loader" />
    <div class="row">
        <div :class="`col-md-${group_active_class !== null ? '3' : '12'}`">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Groups</h3>
                    <button
                        class="btn btn-primary float-right btn-sm"
                        @click="modelOpen = !modelOpen"
                    >
                        Add group
                    </button>
                    <ChatGroup
                        :modelOpen="modelOpen"
                        @groupCreate="formCreate"
                    />
                </div>

                <div class="card-body p-0">
                    <ul
                        class="nav nav-pills flex-column"
                        v-if="groups.length > 0"
                    >
                        <li
                            class="nav-item active"
                            v-for="group in groups"
                            :key="group.id"
                        >
                            <a
                                @contextmenu="
                                    clearConversation(
                                        $event,
                                        group.id,
                                        group.group_admin
                                    )
                                "
                                href="#"
                                @click="groupOption(group.id)"
                                :class="`nav-link ${
                                    group_active_class !== ''
                                        ? group.id === group_active_class
                                            ? 'active'
                                            : ''
                                        : ''
                                }`"
                            >
                                <i class="fas fa-user"></i>
                                {{ group.group_name }}
                                <span
                                    v-if="readCount[group.id] > 0"
                                    class="badge bg-primary float-right"
                                    >{{ readCount[group.id] }} 
                                       
                                    </span
                                >
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-pills flex-column" v-else>
                        <li class="nav-item active">Group not found</li>
                    </ul>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
        <!-- /.col -->
        <div class="col-md-9" v-if="group_active_class !== null">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        {{ group_active_class_name.group_name }}
                        <p class="h6 mt-2">
                            {{ group_active_class_users }}
                        </p>
                    </h3>
                </div>
                <div class="card-body p-0">
                    <div class="mailbox-read-message">
                        <div class="row align-items-center">
                            <div
                                class="col-12 scroller"
                                style="overflow-y: scroll; max-height: 50vh"
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
                                            chat.sender_id !== authUserId &&
                                            chat.receiver_id
                                                .split(',')
                                                .includes(`${authUserId}`)
                                        "
                                        class="col-6 p-2 m-2 rounded"
                                        style="
                                            box-shadow: 0px 0px 10px 1px #888888;
                                        "
                                    >
                                        <p
                                            class="mb-1 font-weight-bold font-italic"
                                        >
                                            {{ chat.user.name }}
                                        </p>
                                        <p
                                            class="pl-1"
                                            :style="
                                                chat.deleted !== '2'
                                                    ? {
                                                          'font-size': '17px',
                                                          'font-weight': 'bold',
                                                      }
                                                    : {
                                                          'font-size': '15px',
                                                          'font-weight':
                                                              'normal',
                                                          'font-style':
                                                              'italic',
                                                      }
                                            "
                                        >
                                            {{
                                                chat.deleted !== "2"
                                                    ? chat.message
                                                    : "This message was deleted by user"
                                            }}
                                        </p>

                                        <div
                                            v-if="chat.deleted !== '2'"
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
                                            chat.sender_id === authUserId &&
                                            !chat.receiver_id
                                                .split(',')
                                                .includes(`${authUserId}`)
                                        "
                                        class="col-6 bg-gradient-secondary p-2 m-2 rounded ml-auto"
                                        style="
                                            box-shadow: 0px 0px 10px 1px #ffffff;
                                        "
                                    >
                                        <p
                                            class="pl-1"
                                            :style="
                                                chat.deleted === '0'
                                                    ? {
                                                          'font-size': '17px',
                                                          'font-weight': 'bold',
                                                      }
                                                    : {
                                                          'font-size': '15px',
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
                                            <i class="fa fa-check ml-1"></i>
                                            <i
                                                class="fa fa-check text-success"
                                                v-if="
                                                    JSON.stringify(
                                                        chat.read
                                                            .split(',')
                                                            .sort()
                                                            .map(Number)
                                                    ) ===
                                                    JSON.stringify(
                                                        chat.receiver_id
                                                            .split(',')
                                                            .sort()
                                                            .map(Number)
                                                    )
                                                "
                                            ></i>
                                            <i
                                                v-if="chat.deleted === '0'"
                                                type="button"
                                                class="fas fa-trash float-right ml-1"
                                                title="Delete"
                                                @click="
                                                    deleteCall(
                                                        chat.id,
                                                        chat.original_date
                                                    )
                                                "
                                            >
                                            </i>
                                            <i
                                                type="button"
                                                class="fa fa-eye float-right popover_class"
                                                data-toggle="popover"
                                                title="Read"
                                                data-trigger="hover"
                                                :data-content="
                                                    getUserReadPopover(
                                                        chat.read
                                                    ) == ''
                                                        ? '-'
                                                        : getUserReadPopover(
                                                              chat.read
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
                                        <i class="fa fa-paper-plane"></i>
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
    <!-- /.card -->
</template>

<script setup>
import axios from "axios";
import { computed, inject, onMounted, reactive, ref } from "vue";
import ChatGroup from "./ChatGroup.vue";
import { useStore } from "vuex";
import { debounce } from "lodash";
import Page_loader from "../../includes/Page_loader.vue";
const modelOpen = ref(false);
const Swal = inject("$swal");
const store = useStore();
const authUser = computed(() => store.getters.getAuthUser);
const authUserId = authUser.value.id;
const groups = ref({});
const group_active_class = ref(null);
const group_active_class_name = ref(null);
const group_active_class_users = ref(null);
const chats = ref({});
const messageData = ref("");
const scrollTop = ref();
const loader = ref(false);

const scrollToBottom = () => {
    setTimeout(() => {
        if (scrollTop.value) {
            scrollTop.value.scrollTop = scrollTop.value.scrollHeight;
            loader.value = false;
        }
        $(function () {
            $(".popover_class").popover();
        });
    }, 1000);
};

const groupOption = (groupId, loaderLoad = true) => {
    loader.value = loaderLoad;
    setTimeout(() => {
        if (groupId !== "") {
            window.scroll({
                top: 1000,
                left: 0,
                behavior: "smooth",
            });
        }
    }, 500);
    group_active_class.value = groupId;
    group_active_class_name.value = groups.value.filter(
        (group) => group.id === groupId
    )[0];
    if (groupId) {
        const groupAdmin = group_active_class_name.value.group_admin;

        group_active_class_users.value = group_active_class_name.value.user
            .map((user) =>
                user.id == groupAdmin ? `${user.name + " (admin)"}` : user.name
            )
            .join(", ");
    }

    getReadCount();

    const formData = reactive({
        group_id: groupId,
    });
    axios
        .post(`/admin_vue/get_group_chats`, formData)
        .then((res) => (chats.value = res.data));
    scrollToBottom();
};
const sendMessage = () => {
    if (messageData.value !== "") {
        const formData = reactive({
            group_id: group_active_class.value,
            message: messageData.value,
        });
        axios
            .post(`/admin_vue/group_chat`, formData)
            .then((res) => (messageData.value = ""));
        groupOption(group_active_class.value, false);
    }
};

const getGroupData = async () => {
    await axios
        .get(`/admin_vue/get_chat_groups`)
        .then((res) => (groups.value = res.data))
        .catch((e) => console.log(e));
};

const readCount = ref(null);
const getReadCount = async () => {
    await axios
        .get(`/admin_vue/get_group_chat_read_count`)
        .then((res) => (readCount.value = res.data));
    loader.value = false;
};

const getUserReadPopover = (ReadId) => {
    let id = ReadId.slice(",");
    let datas = group_active_class_name.value.user.filter((users) =>
        id.includes(users.id)
    );
    return datas.map((us) => us.name).join(",");
};

const deleteCall = (id, dateCheck) => {
    var d1 = new Date();
    var d2 = new Date(dateCheck);
    d2.setDate(d2.getDate() + 2);
    let CancelBtn = d1 < d2;
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
        let res = axios.post(`/admin_vue/delete_group_message`, deleteFormData);
        return res ? true : false;
    };
};

const clearConversation = (e, group_id, group_admin) => {
    e.preventDefault();
    let denyBtn = group_admin == authUserId;
    Swal.fire({
        title: "Do you want to delete this conversation?",
        showCancelButton: true,
        showDenyButton: denyBtn,
        confirmButtonText: "Delete conversation",
        denyButtonText: `Delete group`,
    }).then((result) => {
        if (result.isConfirmed) {
            deleteConversation(group_id);
            groupOption(group_active_class.value, false);
        } else if (result.isDenied) {
            deleteConversation(group_id, 1);
            formCreate();
            group_active_class.value = null;
        }
    });
    const deleteConversation = (group_id, is_group = 0) => {
        const deleteFormData = reactive({
            group_id: group_id,
            is_group: is_group,
        });
        let res = axios.post(
            `/admin_vue/delete_group_coversation`,
            deleteFormData
        );
        return res ? true : false;
    };
};
const formCreate = () => {
    getGroupData();
};
const audioRec = new Audio("/mp3/sms.mp3");
const audioSend = new Audio("/mp3/sms3.mp3");
onMounted(async () => {
    loader.value = true;
    getGroupData();
    getReadCount();
    await window.Echo.channel("GroupChat").listen(".message", (e) => {
        setTimeout(() => {
            groupOption(group_active_class.value, false);
            getReadCount();
            if (
                e.sender_id !== authUserId &&
                e.receiver_id.split(",").includes(`${authUserId}`) &&
                group_active_class.value !== null
            ) {
                audioRec.play();
            }
            if (
                e.sender_id === authUserId &&
                group_active_class.value !== null
            ) {
                audioSend.play();
            }
        }, 500);
    });
    await window.Echo.channel("GroupChatReadReport").listen(".read", (e) => {
        groupOption(group_active_class.value, false);
    });
});
</script>

<style>
.scroller {
    scroll-behavior: smooth !important;
    display: flex;
    flex-direction: column-reverse;
}
</style>
