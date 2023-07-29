<template>
    <body class="hold-transition register-page">
        <div class="register-box">
            <div class="card card-outline card-primary">
                <div class="card-header text-center">
                    <a href="../../index2.html" class="h1"><b>Admin</b>LTE</a>
                </div>
                <div class="card-body">
                    <p class="login-box-msg">Register a new membership</p>

                    <form @submit.prevent="formSubmit">
                        <div class="input-group mb-3">
                            <input
                                type="text"
                                class="form-control"
                                placeholder="Full name"
                                v-model="form.name"
                            />
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-user"></span>
                                </div>
                            </div>
                        </div>
                        <span
                            v-if="formErrors && formErrors.name"
                            class="mx-1 text-danger"
                            >{{ formErrors.name[0] }}</span
                        >
                        <div class="input-group mb-3">
                            <input
                                type="email"
                                class="form-control"
                                placeholder="Email"
                                v-model="form.email"
                            />
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-envelope"></span>
                                </div>
                            </div>
                        </div>
                        <span
                            v-if="formErrors && formErrors.email"
                            class="mx-1 text-danger"
                            >{{ formErrors.email[0] }}</span
                        >
                        <div class="input-group mb-3">
                            <input
                                type="password"
                                class="form-control"
                                placeholder="Password"
                                v-model="form.password"
                            />
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                        </div>
                        <span
                            v-if="formErrors && formErrors.password"
                            class="mx-1 text-danger"
                            >{{ formErrors.password[0] }}</span
                        >
                        <div class="input-group mb-3">
                            <input
                                type="password"
                                class="form-control"
                                placeholder="Retype password"
                                v-model="form.retypePassword"
                            />
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                        </div>
                        <span
                            v-if="formErrors && formErrors.retypePassword"
                            class="mx-1 text-danger"
                            >{{ formErrors.retypePassword[0] }}</span
                        >
                        <div class="row">
                            <!-- /.col -->
                            <div class="col-4">
                                <button
                                    type="submit"
                                    class="btn btn-primary btn-block"
                                >
                                    Register
                                </button>
                            </div>
                            <!-- /.col -->
                        </div>
                    </form>

                    <router-link to="/login" class="text-center"
                        >I already have a membership</router-link
                    >
                </div>
                <!-- /.form-box -->
            </div>
            <!-- /.card -->
        </div>
    </body>
</template>

<script setup>
import axios from "axios";
import { reactive, ref } from "vue";
import toastr from "../../externals/toastr";
import { useRouter } from "vue-router";

const form = reactive({
    name: "",
    email: "",
    password: "",
    retypePassword: "",
});
const router = useRouter();
const formErrors = ref(null);
const formSubmit = async () => {
    toastr.info("Please wait...");
    await axios
        .post(`/register_submit`, form)
        .then((response) => {
            toastr.remove();
            toastr.success("success", "Registration success");
            setTimeout(() => {
                router.push({ path: "/login" });
            }, 700);
        })
        .catch((e) => {
            toastr.remove();
            formErrors.value = e.response.data.errors;
        });
};
</script>

<style></style>
