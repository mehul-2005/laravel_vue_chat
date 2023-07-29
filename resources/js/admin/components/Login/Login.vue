<template>
    <body class="hold-transition login-page">
        <div class="login-box">
            <!-- /.login-logo -->
            <div class="card card-outline card-primary">
                <div class="card-header text-center">
                    <a href="/" class="h1"><b>Admin</b>LTE</a>
                </div>
                <div class="card-body">
                    <p class="login-box-msg">
                        Sign in to start your session {{ firstname }}
                    </p>

                    <form @submit.prevent="loginSubmit">
                        <div class="input-group mb-3">
                            <input
                                v-model="form.email"
                                type="email"
                                class="form-control"
                                placeholder="Email"
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
                                v-model="form.password"
                                type="password"
                                class="form-control"
                                placeholder="Password"
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
                        <div class="row">
                            <div class="col-8">
                                <div class="icheck-primary">
                                    <input type="checkbox" id="remember" />
                                    <label for="remember"> Remember Me </label>
                                </div>
                            </div>
                            <!-- /.col -->
                            <div class="col-4">
                                <button
                                    type="submit"
                                    class="btn btn-primary btn-block"
                                >
                                    Sign In
                                </button>
                            </div>
                            <!-- /.col -->
                        </div>
                    </form>

                    <!-- /.social-auth-links -->

                    <p class="mb-1">
                        <a href="forgot-password.html">I forgot my password</a>
                    </p>
                    <p class="mb-0">
                        <router-link to="/register" class="text-center"
                            >Register a new membership</router-link
                        >
                    </p>
                </div>
                <!-- /.card-body -->
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
import { useStore } from "vuex";

const store = useStore();
const router = useRouter();
const form = reactive({
    email: "",
    password: "",
});
const formErrors = ref(null);
// store.dispatch("auth/login", { user: true});

const loginSubmit = async () => {
    toastr.info("Please wait...");
    await axios
        .post(`/login_submit`, form)
        .then((response) => {
            window.user = response.data.user;
            toastr.remove();
            toastr.success("success", "Login success");
            store.dispatch("auth/login", { user: response.data.user });
            store.dispatch("settings/getSettings");
            setTimeout(() => {
                router.push({ path: "/admin" });
            }, 500);
        })
        .catch((e) => {
            toastr.remove();
            formErrors.value = e.response.data.errors;
            if (e.response.data.invalid) {
                toastr.error("Invalid", e.response.data.invalid);
            }
        });
};
</script>

<style></style>
