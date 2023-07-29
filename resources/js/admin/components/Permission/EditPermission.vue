<template>
    <Page_header :page_name="`Edit ${module_name}`" />
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">{{ module_name }}</h3>
                        </div>
                        <form
                            @submit.prevent="formSubmit"
                            enctype="multipart/form-data"
                        >
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="permission">Permission</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="permission"
                                        placeholder="Enter permission"
                                        v-model="form.permission"
                                    />
                                    <span
                                        v-if="
                                            formErrors && formErrors.permission
                                        "
                                        class="mx-1 text-danger"
                                        >{{ formErrors.permission }}</span
                                    >
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

const module_name = ref("Permission");
const route = useRoute();

const router = useRouter();
const form = reactive({
    permission: "",
});

const formErrors = ref(null);
const formSubmit = () => {
    axios
        .patch(`/admin_vue/permission/${route.params.id}`, form)
        .then((response) => {
            toastr.success("success", "Permission updated");
            router.push({ path: "/admin/permission" });
        })
        .catch((e) => (formErrors.value = e.response.data.errors));
};
onMounted(async () => {
    await axios
        .get(`/admin_vue/permission/${route.params.id}/edit`)
        .then((response) => {
            form.permission = response.data.permission;
        });
});
</script>

<style></style>
