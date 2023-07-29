<script setup>
import HeaderVue from "../includes/Header.vue";
import SidebarVue from "../includes/Sidebar.vue";
import FooterVue from "../includes/Footer.vue";
import { useStore } from "vuex";
import { computed, onMounted } from "vue";

const store = useStore();
onMounted(() => {
    store.dispatch("settings/getSettings");
});
const font_family = computed(() =>
    store.getters.getSettings.font_family === "null"
        ? ""
        : store.getters.getSettings.font_family
);
const setting = computed(() => store.getters.getSettings);
const theme_color = computed(() => setting.value.theme_color);
const font_color = computed(() => setting.value.font_color);
</script>
<template>
    <HeaderVue />
    <SidebarVue />

    <div class="content-wrapper">
        <router-view></router-view>
    </div>
    <FooterVue />
</template>

<style>
* {
    font-family: v-bind("font_family?font_family.value:''");
}
.card-primary:not(.card-outline) > .card-header,
#app .btn-primary {
    background: v-bind("theme_color") !important;
    color: v-bind("font_color");
    border-color: v-bind("font_color");
}
#app .breadcrumb-item a {
    color: v-bind("theme_color") !important;
}
</style>
