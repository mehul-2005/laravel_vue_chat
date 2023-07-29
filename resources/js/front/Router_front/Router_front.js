import { createRouter, createWebHistory } from "vue-router";
import Main from "../components/Main_front.vue";

const routes = [{ path: "/front", name: "front", component: Main }];
const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
