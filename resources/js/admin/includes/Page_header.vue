<template>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ props.page_name }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="">Home</a></li>
                        <li class="breadcrumb-item active">{{ props.page_name }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
const props = defineProps(['page_name']);
</script>

<style></style>
