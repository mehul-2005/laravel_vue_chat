<script setup>
import { onMounted, reactive, ref, onBeforeMount } from "vue";
import Page_header from "../../includes/Page_header.vue";
import DataTable from "datatables.net-vue3";
import DataTablesCore from "datatables.net";
import axios from "axios";
import { useRouter } from "vue-router";
import toastr from "../../externals/toastr";
import Swal from "sweetalert2";

const router = useRouter();

DataTable.use(DataTablesCore);

const users = ref();
onBeforeMount(async () => {
    let datas = await axios
        .get(`/admin_vue/permission`)
        .catch((e) => console.log("error"));
    var { data } = datas;
    var userData = reactive([]);
    data.map((data) => {
        userData.push([
            data.id,
            data.permission,
            `<button id="editData" data-id="${data.id}"  class='btn btn-success mx-2'>Edit</button><button data-id="${data.id}" id="deleteData" class='btn btn-danger'>Delete</button>`,
        ]);
    });

    users.value = userData;
});
$(document).on("click", "#editData", function () {
    let id = $(this).data("id");
    router.push(`/admin/permission/${id}/edit`);
});
$(document).on("click", "#deleteData", function () {
    let id = $(this).data("id");
    new Swal({
        title: "Are you sure?",
        text: "Do you want to delete?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, I am sure!",
        cancelButtonText: "No, cancel it!",
    }).then(function (isConfirm) {
        if (isConfirm.value) {
            axios
                .delete(`/admin_vue/permission/${id}`)
                .then((res) => {
                    toastr.success("Permission deleted");
                    users.value = users.value.filter((data) => data[0] !== id);
                })
                .catch((e) => console.log("error"));
        }
    });
});
</script>

<template>
    <Page_header page_name="Permissions" />

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        DataTable with minimal features &amp; hover style
                    </h3>
                    <button
                        class="btn btn-primary float-right"
                        @click="$router.push('/admin/permission/create')"
                    >
                        <i class="fa fa-user-plus" aria-hidden="true"></i> Add
                        permission
                    </button>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <DataTable
                        class="table table-bordered table-hover display"
                        :data="users"
                        :options="{
                            select: true,
                            paging: true,
                            lengthChange: true,
                            searching: true,
                            ordering: false,
                            info: true,
                            autoWidth: true,
                            responsive: true,
                            retrieve: true,
                        }"
                    >
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Permission</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                    </DataTable>
                </div>
            </div>
        </div>
    </div>
</template>

<style></style>
