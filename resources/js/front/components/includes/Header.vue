<template>
    <section class="header">
        <a href="#" class="logo"><i class="fas fa-utensils"></i> food.</a>
        <nav class="navbar">
            <a href="#home">home</a>
            <a href="#about">about</a>
            <a href="#food">food</a>
            <a href="#gallery">gallery</a>
            <a href="#menu">menu</a>
            <a href="#order">order</a>
            <a href="#blogs">blogs</a>
        </nav>
        <div id="menu-btn" class="fas fa-bars"></div>
    </section>
</template>

<script setup>
import { onMounted } from "vue";
onMounted(() => {
    let navbar = document.querySelector(".header .navbar");
    let menuBtn = document.querySelector("#menu-btn");
    menuBtn.onclick = () => {
        menuBtn.classList.toggle("fa-times");
        navbar.classList.toggle("active");
    };
});
</script>

<style></style>
