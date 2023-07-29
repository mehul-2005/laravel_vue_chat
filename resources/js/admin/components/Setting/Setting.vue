<template>
    <Page_header page_name="Add Settings" />
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Settings</h3>
                        </div>
                        <form
                            @submit.prevent="formSubmit"
                            enctype="multipart/form-data"
                        >
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="company_name"
                                        >Company name</label
                                    >
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="company_name"
                                        placeholder="Enter Company name"
                                        v-model="form.company_name"
                                    />
                                    <span
                                        v-if="
                                            formErrors &&
                                            formErrors.company_name
                                        "
                                        class="mx-1 text-danger"
                                        >{{ formErrors.company_name }}</span
                                    >
                                </div>
                                <div class="form-group">
                                    <label for="company_name"
                                        >Company email</label
                                    >
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="company_email"
                                        placeholder="Enter Email"
                                        v-model="form.company_email"
                                    />
                                    <span
                                        v-if="
                                            formErrors &&
                                            formErrors.company_email
                                        "
                                        class="mx-1 text-danger"
                                        >{{ formErrors.company_email }}</span
                                    >
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputFile"
                                        >Company logo</label
                                    >
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input
                                                type="file"
                                                class="custom-file-input"
                                                id="exampleInputFile"
                                                @input="uploadImage"
                                            />
                                            <label
                                                class="custom-file-label"
                                                for="exampleInputFile"
                                                >Choose file</label
                                            >
                                        </div>
                                    </div>
                                    <span
                                        v-if="
                                            formErrors &&
                                            formErrors.company_logo
                                        "
                                        class="mx-1 text-danger"
                                        >{{ formErrors.company_logo }}</span
                                    >
                                    <img
                                        class=""
                                        v-if="previewImage"
                                        :src="previewImage"
                                        width="200"
                                        height="100"
                                    />
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputFile"
                                        >Company favicon</label
                                    >
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input
                                                type="file"
                                                class="custom-file-input"
                                                id="exampleInputFile"
                                                @input="uploadImage2"
                                                accept="image/png"
                                            />
                                            <label
                                                class="custom-file-label"
                                                for="exampleInputFile"
                                                >Choose file</label
                                            >
                                        </div>
                                    </div>
                                    <span
                                        v-if="
                                            formErrors &&
                                            formErrors.company_fevicon
                                        "
                                        class="mx-1 text-danger"
                                        >{{ formErrors.company_fevicon }}</span
                                    >
                                    <img
                                        class=""
                                        v-if="previewImage_company_fevicon"
                                        :src="previewImage_company_fevicon"
                                        width="200"
                                        height="100"
                                    />
                                </div>
                                <div class="form-group">
                                    <label for="company_footer"
                                        >Company footer</label
                                    >
                                    <textarea
                                        class="form-control"
                                        id="company_footer"
                                        placeholder="Enter Company name"
                                        v-model.trim="form.company_footer"
                                        rows="5"
                                    ></textarea>

                                    <span
                                        v-if="
                                            formErrors &&
                                            formErrors.company_footer
                                        "
                                        class="mx-1 text-danger"
                                        >{{ formErrors.company_footer }}</span
                                    >
                                </div>
                                <div class="form-group">
                                    <label for="company_address"
                                        >Company address</label
                                    >
                                    <textarea
                                        class="form-control"
                                        id="company_address"
                                        placeholder="Enter Company Address"
                                        v-model.trim="form.company_address"
                                        rows="5"
                                    ></textarea>

                                    <span
                                        v-if="
                                            formErrors &&
                                            formErrors.company_address
                                        "
                                        class="mx-1 text-danger"
                                        >{{ formErrors.company_address }}</span
                                    >
                                </div>
                                <div class="form-group">
                                    <label for="theme_color">Theme color</label>
                                    <input
                                        type="color"
                                        class="form-check"
                                        id="theme_color"
                                        v-model="form.theme_color"
                                    />
                                </div>
                                <div class="form-group">
                                    <label for="font_color">Font color</label>
                                    <input
                                        type="color"
                                        class="form-check"
                                        id="font_color"
                                        v-model="form.font_color"
                                    />
                                </div>
                                <div class="form-group">
                                    <label for="font_family"
                                        >Font Family</label
                                    >
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="font_family"
                                        placeholder="Enter Font Family"
                                        v-model="form.font_family"
                                    />
                                    <span
                                        v-if="
                                            formErrors &&
                                            formErrors.font_family
                                        "
                                        class="mx-1 text-danger"
                                        >{{ formErrors.font_family }}</span
                                    >
                                </div>
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">
                                    Update
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
import { onMounted, reactive, ref } from "vue";
import Page_header from "../../includes/Page_header.vue";
import axios from "axios";
import toastr from "../../externals/toastr";
import { useStore } from "vuex";
const store = useStore();
const form = reactive({
    company_email: "",
    company_name: "",
    company_logo: {},
    company_fevicon: {},
    company_footer: "",
    theme_color: "",
    font_color: "",
    company_address:"",
    font_family:""
});
const previewImage = ref(null);
const uploadImage = (e) => {
    if (e.target.files.length > 0) {
        const image = e.target.files[0];
        form.company_logo = e.target.files[0];
        const reader = new FileReader();
        reader.readAsDataURL(image);
        reader.onload = (e) => {
            previewImage.value = e.target.result;
        };
    }else{
        previewImage.value = ''
    }
};
const previewImage_company_fevicon = ref(null);
const uploadImage2 = (e) => {
    if (e.target.files.length > 0) {
        const image = e.target.files[0];
        form.company_fevicon = e.target.files[0];
        const reader = new FileReader();
        reader.readAsDataURL(image);
        reader.onload = (e) => {
            previewImage_company_fevicon.value = e.target.result;
        };
    }
};
onMounted(async () => {
    await axios.get(`/admin_vue/setting`).then((res) => {
        form.company_email = res.data.company_email;
        form.company_name = res.data.company_name;
        previewImage.value = res.data.company_logo;
        previewImage_company_fevicon.value = res.data.company_fevicon;
        form.company_footer = res.data.company_footer;
        form.company_address = res.data.company_address;
        form.theme_color = res.data.theme_color;
        form.font_color = res.data.font_color;
        form.font_family = res.data.font_family==='null'?'':res.data.font_family;
    });
});

const formErrors = ref(null);
const formSubmit = () => {
    formErrors.value = null;
    let formData = new FormData();
    for (const [key, value] of Object.entries(form)) {
        formData.append(key, value);
    }
    let config = {
        header: {
            "content-Type": "multipart/form-data",
        },
    };
    axios
        .post(`/admin_vue/setting`, formData, config)
        .then((response) => {
            toastr.success("success", "Setting updated");
            // router.push({ path: "/admin/user" });
            store.dispatch("settings/getSettings");
        })
        .catch((e) => (formErrors.value = e.response.data.errors));
};
</script>

<style>
*{

}
</style>
