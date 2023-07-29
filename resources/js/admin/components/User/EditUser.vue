<template>
    <Page_header page_name="Edit user" />
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">User</h3>
                        </div>
                        <form
                            @submit.prevent="formSubmit"
                            enctype="multipart/form-data"
                        >
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="name"
                                        placeholder="Enter name"
                                        v-model="form.name"
                                    />
                                    <span
                                        v-if="formErrors && formErrors.name"
                                        class="mx-1 text-danger"
                                        >{{ formErrors.name }}</span
                                    >
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"
                                        >Email address</label
                                    >
                                    <input
                                        type="email"
                                        class="form-control"
                                        id="exampleInputEmail1"
                                        placeholder="Enter email"
                                        v-model="form.email"
                                    />
                                    <span
                                        v-if="formErrors && formErrors.email"
                                        class="mx-1 text-danger"
                                        >{{ formErrors.email }}</span
                                    >
                                </div>
                                <!-- <div class="form-group">
                                    <label for="exampleInputPassword1"
                                        >Password</label
                                    >
                                    <input
                                        type="password"
                                        class="form-control"
                                        id="exampleInputPassword1"
                                        placeholder="Password"
                                        v-model="form.password"
                                    />
                                    <span
                                        v-if="formErrors && formErrors.password"
                                        class="mx-1 text-danger"
                                        >{{ formErrors.password }}</span
                                    >
                                </div> -->
                                <div class="form-group">
                                    <label for="role">Role</label>
                                    <select
                                        id="role"
                                        class="form-control"
                                        multiple
                                        v-model="form.role"
                                    >
                                        <option
                                            v-for="role in roles"
                                            :key="role.id"
                                            :value="role.id"
                                            :selected="
                                                editRole.includes(role.role)
                                                    ? true
                                                    : false
                                            "
                                        >
                                            {{ role.role }}
                                        </option>
                                    </select>
                                    <span
                                        v-if="formErrors && formErrors.role"
                                        class="mx-1 text-danger"
                                        >{{ formErrors.role }}</span
                                    >
                                </div>
                                <div class="form-group">
                                    <label for="permission">Perission</label>
                                    <select
                                        id="permission"
                                        class="form-control"
                                        multiple
                                        v-model="form.permission"
                                    >
                                        <option
                                            v-for="permission in permissions"
                                            :key="permission.id"
                                            :value="permission.id"
                                            :selected="
                                                editPermission.includes(
                                                    permission.permission
                                                )
                                                    ? true
                                                    : false
                                            "
                                        >
                                            {{ permission.permission }}
                                        </option>
                                    </select>
                                    <span
                                        v-if="
                                            formErrors && formErrors.permission
                                        "
                                        class="mx-1 text-danger"
                                        >{{ formErrors.permission }}</span
                                    >
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputFile"
                                        >File input</label
                                    >
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input
                                                type="file"
                                                class="custom-file-input"
                                                id="exampleInputFile"
                                                @input="uploadImage"
                                            />
                                            <label
                                                class="custom-file-label"
                                                for="exampleInputFile"
                                                >Choose file</label
                                            >
                                        </div>
                                    </div>
                                    <span
                                        v-if="formErrors && formErrors.image"
                                        class="mx-1 text-danger"
                                        >{{ formErrors.image }}</span
                                    >
                                    <img
                                        class="float-right d-inline"
                                        v-if="previewImage"
                                        :src="previewImage"
                                        width="200"
                                        height="100"
                                    />
                                </div>
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">
                                    Update
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
import { onMounted, reactive, ref } from "vue";
import Page_header from "../../includes/Page_header.vue";
import axios from "axios";
import toastr from "../../externals/toastr";
import { useRouter, useRoute } from "vue-router";
import { useStore } from "vuex";
const store = useStore();
const route = useRoute();

const router = useRouter();
const roles = ref(null);
const permissions = ref(null);
const form = reactive({
    name: "",
    email: "",
    password: "",
    role: [],
    permission: [],
    image: {},
});

const previewImage = ref(null);
const uploadImage = (e) => {
    if (e.target.files.length > 0) {
        const image = e.target.files[0];
        form.image = e.target.files[0];
        const reader = new FileReader();
        reader.readAsDataURL(image);
        reader.onload = (e) => {
            previewImage.value = e.target.result;
        };
    }
};

const formErrors = ref(null);
const formSubmit = () => {
    let formData = new FormData();
    for (const [key, value] of Object.entries(form)) {
        formData.append(key, value);
    }
    formData.append("_method", "patch");
    let config = {
        header: {
            "content-Type": "multipart/form-data",
        },
    };
    axios
        .post(`/admin_vue/user/${route.params.id}`, formData, config)
        .then((response) => {
            toastr.success("success", "User updated");
            store.dispatch("auth/authReload");
            router.push({ path: "/admin/user" });
        })
        .catch((e) => (formErrors.value = e.response.data.errors));
};
const editPermission = ref(null);
const editRole = ref(null);
onMounted(async () => {
    await axios
        .get(`/admin_vue/user/${route.params.id}/edit`)
        .then((response) => {
            form.name = response.data.name;
            form.email = response.data.email;
            editRole.value = response.data.roleslist;
            editPermission.value = response.data.permissionslist;
            form.role = response.data.roles.map((datas) => datas.id);
            form.permission = response.data.permissions.map((data) => data.id);
            previewImage.value = "/storage/" + response.data.image;
        });

    await axios
        .get(`/admin_vue/role`)
        .then((response) => (roles.value = response.data));

    await axios
        .get(`/admin_vue/permission`)
        .then((response) => (permissions.value = response.data));
});
</script>

<style></style>
