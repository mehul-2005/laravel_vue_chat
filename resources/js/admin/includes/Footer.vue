<template>
    <footer class="main-footer">
        <!-- <div class="float-right d-none d-sm-inline">Anything you want</div>

        <strong
            >Copyright &copy; 2014-2021
            <a href="https://adminlte.io">AdminLTE.io</a>.</strong
        >
        All rights reserved. -->
        <span v-html="setting.company_footer"></span>
    </footer>
</template>

<script setup>
import { computed } from "vue";
import { useStore } from "vuex";

const store = useStore();
const setting = computed(()=>store.getters.getSettings);
</script>

<style></style>
