<template>
    <div
        v-if="loader"
        class="d-flex"
        style="
            height: 100%;
            width: 100%;
            background-color: rgba(255, 255, 255, 0.7);
            position: fixed;
            z-index: 1;
        "
    >
        <div
            class="spinner-border"
            role="status"
            style="position: fixed; top: 50%; left: 50%"
        ></div>
    </div>
</template>

<script setup>
import { toRefs } from "vue";

const props = defineProps({ loader: { type: Boolean, required: true } });
const { loader } = toRefs(props);
</script>

<style></style>
