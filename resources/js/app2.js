require("./bootstrap");
import { createApp } from "vue";
import router from "./front/Router_front/Router_front";
import Main from "./front/components/Main_front.vue";

createApp(Main).use(router).mount("#front");
