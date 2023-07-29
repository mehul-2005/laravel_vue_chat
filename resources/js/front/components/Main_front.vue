<template>
    <Header></Header>

    <!-- home section start -->
    <section class="home" id="home">
        <div class="swiper home-slider">
            <div class="swiper-wrapper">
                <div
                    class="slide swiper-slide"
                    style="background: url(images/home-slide-1.jpg) no-repeat"
                >
                    <div class="content">
                        <span>outstanding food</span>
                        <h3>dalicious cooking</h3>
                        <a href="" class="btn">get started</a>
                    </div>
                </div>
                <div
                    class="slide swiper-slide"
                    style="background: url(images/home-slide-2.jpg) no-repeat"
                >
                    <div class="content">
                        <span>outstanding food</span>
                        <h3>morning moment</h3>
                        <a href="" class="btn">get started</a>
                    </div>
                </div>
                <div
                    class="slide swiper-slide"
                    style="background: url(images/home-slide-3.jpg) no-repeat"
                >
                    <div class="content">
                        <span>outstanding food lore</span>
                        <h3>authentic kitchen</h3>
                        <a href="" class="btn">get started</a>
                    </div>
                </div>
            </div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    </section>
    <!-- home section end -->

    <About />
    <Food />
    <Food_prev />
    <Gallery />
    <Menus />
    <Order />
    <Blogs />

    <Footer />
</template>

<script setup>
import { onMounted } from "vue";
import Header from "./includes/Header.vue";
import Footer from "./includes/Footer.vue";
import Swiper, { Navigation, Pagination } from "swiper";
import lightGallery from "lightgallery";
import About from "./About.vue";
import Food from "./Food.vue";
import Food_prev from "./Food_prev.vue";
import Gallery from "./Gallery.vue";
import Menus from "./Menu.vue";
import Order from "./Order.vue";
import Blogs from "./Blogs.vue";

onMounted(() => {
    setTimeout(() => {
        var swiper = new Swiper(".home-slider", {
            grabCursor: true,
            loop: true,
            centeredSlides: true,
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
        });

        var swiper = new Swiper(".food-slider", {
            grabCursor: true,
            loop: true,
            centeredSlides: true,
            spaceBetween: 20,
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            breakpoints: {
                0: {
                    slidesPerView: 1,
                },
                700: {
                    slidesPerView: 2,
                },
                1000: {
                    slidesPerView: 3,
                },
            },
        });

        let previewContainer = document.querySelector(
            ".food-preview-container"
        );
        let previewBox = previewContainer.querySelectorAll(".food-preview");

        document.querySelectorAll(".food .slide").forEach((food) => {
            food.onclick = () => {
                previewContainer.style.display = "flex";
                let name = food.getAttribute("data-name");
                previewBox.forEach((preview) => {
                    let target = preview.getAttribute("data-target");
                    if (name == target) {
                        preview.classList.add("active");
                    }
                });
            };
        });

        document.onkeydown = function (evt) {
            if (evt.keyCode == 27) {
                previewContainer.style.display = "none";
                previewBox.forEach((close) => {
                    close.classList.remove("active");
                });
            }
        };
        previewContainer.querySelector("#close-preview").onclick = () => {
            previewContainer.style.display = "none";
            previewBox.forEach((close) => {
                close.classList.remove("active");
            });
        };

        var swiper = new Swiper(".menu-slider", {
            grabCursor: true,
            loop: true,
            centeredSlides: true,
            autoHeight: true,
            spaceBetween: 20,
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
        });

        var swiper = new Swiper(".blogs-slider", {
            grabCursor: true,
            loop: true,
            centeredSlides: true,
            autoHeight: true,
            spaceBetween: 20,
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            breakpoints: {
                0: {
                    slidesPerView: 1,
                },
                700: {
                    slidesPerView: 2,
                },
                1000: {
                    slidesPerView: 3,
                },
            },
        });
        lightGallery(document.querySelector(".gallery .gallery-container"));
    }, 1000);
});
</script>

<style></style>
