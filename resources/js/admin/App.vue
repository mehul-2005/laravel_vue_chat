<template>
    <Main v-if="this.$route.path.startsWith('/admin')" />

    <Teleport
        to="#login"
        v-if="this.$route.name === 'login' || this.$route.name === 'register'"
    >
        <Login v-if="this.$route.name === 'login'" />
        <Register v-if="this.$route.name === 'register'" />
    </Teleport>
</template>

<script setup>
import Login from "./components/Login/Login.vue";
import Register from "./components/Login/Register.vue";
import Main from "./components/Main.vue";
import { useRoute } from "vue-router";
import { computed, onUpdated } from "vue";
import { useStore } from "vuex";

const store = useStore();
const route = useRoute();
const setting = computed(() => store.getters.getSettings);

const titleCall = () => {
    if (_.isEmpty(setting.value)) {
        document.title = route.name[0].toUpperCase() + route.name.slice(1);
    } else {
        document.title = setting.value.company_name;
    }
};
onUpdated(() => {
    titleCall();
});

</script>
<script>
const $ = require("jquery");
window.$ = $;
</script>

<style></style>
