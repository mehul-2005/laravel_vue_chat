<template>
    <!-- order section start -->
    <section id="order" class="order">
        <div class="heading">
            <span>order now</span>
            <h3>fast home delivery</h3>
        </div>
        <form action="">
            <div class="box-container">
                <div class="box">
                    <div class="inputbox">
                        <span>full name</span>
                        <input type="text" placeholder="enter your name" />
                    </div>
                    <div class="inputbox">
                        <span>food name</span>
                        <input type="text" placeholder="food you want" />
                    </div>
                    <div class="inputbox">
                        <span>order details</span>
                        <input type="text" placeholder="specifics with food" />
                    </div>
                    <div class="inputbox">
                        <span>your address</span>
                        <textarea
                            name=""
                            placeholder="enter your address"
                            id=""
                            cols="30"
                            rows="10"
                        ></textarea>
                    </div>
                </div>
                <div class="box">
                    <div class="inputbox">
                        <span>number</span>
                        <input type="number" placeholder="enter your number" />
                    </div>
                    <div class="inputbox">
                        <span>how much</span>
                        <input type="number" placeholder="how many you want" />
                    </div>
                    <div class="inputbox">
                        <span>when you want</span>
                        <input type="datetime-local" />
                    </div>
                    <div class="inputbox">
                        <span>our address</span>
                        <iframe
                            class="map"
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d14687.154528616475!2d72.51219788877613!3d23.03153177476587!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x395e9b353bc60d91%3A0xd5e48ea22ff8d924!2sSatellite%2C%20Ahmedabad%2C%20Gujarat!5e0!3m2!1sen!2sin!4v1674195843740!5m2!1sen!2sin"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                        ></iframe>
                    </div>
                </div>
            </div>
            <input type="submit" value="order now" class="btn" />
        </form>
    </section>
    <!-- order section end -->
</template>

<script setup>

</script>

<style></style>
