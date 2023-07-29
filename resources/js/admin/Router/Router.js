import { createRouter, createWebHistory } from "vue-router";
import NotFoundView from "../components/NotFoundView.vue";
import Dashboard from "../components/Dashboard.vue";
import ListUser from "../components/User/ListUser.vue";
import AddUser from "../components/User/AddUser.vue";
import EditUser from "../components/User/EditUser.vue";
import ListRole from "../components/Role/ListRole.vue";
import AddRole from "../components/Role/AddRole.vue";
import EditRole from "../components/Role/EditRole.vue";
import ListPermission from "../components/Permission/ListPermission.vue";
import AddPermission from "../components/Permission/AddPermission.vue";
import EditPermission from "../components/Permission/EditPermission.vue";
import Setting from "../components/Setting/Setting.vue";
import ListChat from '../components/Chat/ListChat.vue';

import Login from "../components/Login/Login.vue";
import Register from "../components/Login/Register.vue";

import store from "../store/store";
import axios from "axios";
import toastr from "../externals/toastr";

const routes = [
    { path: "/admin", name: "admin", component: Dashboard },
    {
        path: "/login",
        name: "login",
        component: Login,
    },
    {
        path: "/register",
        name: "register",
        component: Register,
    },
    {
        path: "/admin/user",
        component: ListUser,
    },
    {
        path: "/admin/user/create",
        component: AddUser,
        beforeEnter: (to, from, next) => checkPermission(next, "create"),
    },
    {
        path: "/admin/user/:id/edit",
        component: EditUser,
        beforeEnter: (to, from, next) => checkPermission(next, "update"),
    },
    {
        path: "/admin/role",
        component: ListRole,
    },
    {
        path: "/admin/role/create",
        component: AddRole,
        beforeEnter: (to, from, next) => checkPermission(next, "create"),
    },
    {
        path: "/admin/role/:id/edit",
        component: EditRole,
        beforeEnter: (to, from, next) => checkPermission(next, "update"),
    },
    {
        path: "/admin/permission",
        component: ListPermission,
    },
    {
        path: "/admin/permission/create",
        component: AddPermission,
        beforeEnter: (to, from, next) => checkPermission(next, "create"),
    },
    {
        path: "/admin/permission/:id/edit",
        component: EditPermission,
        beforeEnter: (to, from, next) => checkPermission(next, "update"),
    },
    {
        path: "/admin/setting",
        component: Setting,
    },
    {
        path: "/admin/chat",
        component: ListChat,
    },
    {
        path: "/admin/:pathMatch(.*)*",
        name: "notfound",
        component: NotFoundView,
    },
];
const router = createRouter({
    history: createWebHistory(),
    routes,
});
const checkPermission = (next, value) => {
    return store.getters.getAuthUser.permissionslist.includes(value)
        ? next()
        : next({ name: "notfound" });
};
const checkAuth = async () => {
    let data = await axios.get(`/auth_check`);
    let auth = data.data;
    if (auth === 0) {
        // localStorage.removeItem("authUser");
        localStorage.clear();
        return false;
    } else {
        return true;
    }
};
router.beforeEach(async (to, from, next) => {
    let authCheck = from.path === "/" ? await checkAuth() : true;
    let isAuthenticated =
        (await store.getters.getAuthUser) && authCheck ? true : false;
    if (isAuthenticated && (to.name === "login" || to.name === "register"))
        next({ name: "admin" });
    else if (!isAuthenticated && to.path.startsWith("/admin"))
        next({ name: "login" });
    else next();
});
router.afterEach(async (to, from) => {
    if (to.path.startsWith("/admin")) {
        if (
            !(await checkAuth()) &&
            to.path.startsWith("/admin") &&
            to.name !== "login"
        ) {
            toastr.error("Auth failed please login again");
            router.go();
        }
    }
});

export default router;
