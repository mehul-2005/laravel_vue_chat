<template>
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="index3.html" class="brand-link">
            <img
                :src="setting.company_logo"
                alt="AdminLTE Logo"
                class="brand-image img-circle elevation-3"
                style="opacity: 0.8"
            />
            <span class="brand-text font-weight-light">{{
                setting.company_name
            }}</span>
        </a>

        <div class="sidebar">
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img
                        :src="`/storage/${authUser.image}`"
                        class="img-circle elevation-2"
                        :height="`100`"
                        alt="User Image"
                    />
                </div>
                <div class="info">
                    <router-link
                        :to="`/admin/user/${authUser.id}/edit`"
                        class="d-block"
                        >{{ authUser.name }}</router-link
                    >
                </div>
            </div>

            <nav class="mt-2">
                <ul
                    class="nav nav-pills nav-sidebar flex-column"
                    data-widget="treeview"
                    role="menu"
                    data-accordion="false"
                >
                    <li class="nav-item">
                        <router-link
                            to="/admin"
                            class="nav-link"
                            active-class="active"
                        >
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link
                            to="/admin/user"
                            class="nav-link"
                            :class="
                                $route.path.startsWith('/admin/user')
                                    ? 'active'
                                    : ''
                            "
                            active-class="active"
                        >
                            <i class="nav-icon fas fa-user"></i>
                            <p>Users</p>
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link
                            to="/admin/role"
                            class="nav-link"
                            :class="
                                $route.path.startsWith('/admin/role')
                                    ? 'active'
                                    : ''
                            "
                            active-class="active"
                        >
                            <i class="nav-icon fas fa-user-check"></i>
                            <p>Roles</p>
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link
                            to="/admin/permission"
                            class="nav-link"
                            :class="
                                $route.path.startsWith('/admin/permission')
                                    ? 'active'
                                    : ''
                            "
                            active-class="active"
                        >
                            <i class="nav-icon fas fa-user-secret"></i>
                            <p>Permissions</p>
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link
                            to="/admin/chat"
                            class="nav-link"
                            active-class="active"
                        >
                            <i class="nav-icon fas fa-envelope"></i>
                            <p>Chat</p>
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link
                            to="/admin/setting"
                            class="nav-link"
                            active-class="active"
                        >
                            <i class="nav-icon fas fa-cog"></i>
                            <p>Settings</p>
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <a
                            href="#"
                            @click="logout()"
                            class="nav-link text-danger"
                            active-class="active"
                        >
                            <i class="nav-icon fas fa-sign-out-alt"></i>
                            <p>Logout</p>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>
</template>

<script setup>
import { computed, ref } from "vue";
import { useStore } from "vuex";
import { useRouter } from "vue-router";
import toastr from "../externals/toastr";
import axios from "axios";
const store = useStore();
const authUser = computed(() => store.getters.getAuthUser);
const setting = computed(() => store.getters.getSettings);
const router = useRouter();
const logout = async () => {
    localStorage.removeItem("authUser");
    localStorage.removeItem("token");
    await axios
        .post("/logout")
        .then((response) => toastr.success("success", "Logout success"));
    router.go();
};
</script>

<style>
.nav-item > .nav-link.active {
    background-color: v-bind("setting.theme_color") !important;
    color: v-bind("setting.font_color") !important;
}
</style>
