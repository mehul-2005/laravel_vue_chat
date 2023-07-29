<template>
    <!-- Button trigger modal -->
    <button
        type="button"
        class="btn btn-primary"
        data-toggle="modal"
        data-target="#exampleModalCenter"
        ref="openModelBtn"
        style="display: none"
    >
        Launch demo
    </button>

    <!-- Modal -->
    <div
        class="modal fade"
        id="exampleModalCenter"
        tabindex="-1"
        role="dialog"
        aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">
                        Create group
                    </h5>
                    <button
                        type="button"
                        class="close"
                        data-dismiss="modal"
                        aria-label="Close"
                        ref="closeModelBtn"
                    >
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input
                        type="text"
                        class="form-control my-2"
                        placeholder="Group name"
                        v-model="formState.groupName"
                    />
                    <span
                        v-if="formErrors && formErrors.groupName"
                        class="mx-1 text-danger"
                        >{{ formErrors.groupName[0] }}</span
                    >
                    <input
                        type="search"
                        class="form-control my-2 col-4"
                        placeholder="Search user"
                        v-model="state.searchInput"
                    />
                    <div>
                        <span
                            class="badge badge-secondary mx-1"
                            v-for="tag in badge"
                            :key="tag"
                            >{{ tag.name }}
                            <i
                                class="fa fa-times ml-1 cursor"
                                role="button"
                                @click="badgeRemove(tag.id)"
                            ></i>
                        </span>
                    </div>

                    <div v-for="user in users" :key="user.id">
                        <div class="form-check" v-if="authUser.id !== user.id">
                            <input
                                type="checkbox"
                                class="form-check-input"
                                :value="user.id"
                                :id="user.id"
                                v-model="formState.selectUser"
                            />
                            <label :for="user.id" class="form-check-label">
                                {{ user.name }}
                            </label>
                        </div>
                    </div>
                    <span
                        v-if="formErrors && formErrors.selectUser"
                        class="mx-1 text-danger"
                        >{{ formErrors.selectUser[0] }}</span
                    >
                </div>
                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-dismiss="modal"
                    >
                        Close
                    </button>
                    <button
                        type="button"
                        class="btn btn-primary"
                        @click="formSubmit"
                    >
                        Create
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
// use normal <script> to declare options
export default {
    inheritAttrs: false,
};
</script>
<script setup>
import {
    computed,
    onMounted,
    onUnmounted,
    reactive,
    ref,
    toRefs,
    watch,
} from "vue";
import axios from "axios";
import toastr from "../../externals/toastr";
import { useStore } from "vuex";

const store = useStore();
const authUser = computed(() => store.getters.getAuthUser);

const props = defineProps({
    modelOpen: Boolean,
    default: false,
});
const { modelOpen } = toRefs(props);
const openModelBtn = ref(null);
const closeModelBtn = ref(null);
watch(modelOpen, () => {
    openModelBtn.value.click();
});
const state = reactive({
    searchInput: null,
    users: [],
});

const users = computed(() => {
    if (state.searchInput) {
        var search = state.searchInput.toLowerCase();
        return state.users.filter((user) =>
            user.name.toLowerCase().includes(search)
        );
    } else {
        return state.users;
    }
});

const formState = reactive({
    selectUser: [],
    groupName: "",
});
const badge = ref(null);
watch(
    (formState.selectUser,
    () => {
        badge.value = users.value
            .filter((datas) => formState.selectUser.includes(datas.id))
            .map((datas) => {
                return { name: datas.name, id: datas.id };
            });
    })
);

const badgeRemove = (id) => {
    formState.selectUser = formState.selectUser.filter((data) => data !== id);
};
const emit = defineEmits(["groupCreate"]);
const formErrors = ref(null);
const formSubmit = async () => {
    await axios
        .post(`/admin_vue/chat_group_add`, formState)
        .then((res) => {
            emit("groupCreate");
            toastr.success("success", "Group created");
            // router.push({ path: "/admin/chat" });
            closeModelBtn.value.click();
        })
        .catch((e) => (formErrors.value = e.response.data.errors));
};
onMounted(async () => {
    await axios.get(`/admin_vue/user`).then((res) => (state.users = res.data));
});
</script>

<style></style>
