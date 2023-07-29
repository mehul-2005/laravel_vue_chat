<script setup>
import { onMounted, reactive, ref, onBeforeMount, inject, computed } from "vue";
import Page_header from "../../includes/Page_header.vue";
// import { debounce } from "lodash";
import DataTable from "datatables.net-vue3";
import DataTablesCore from "datatables.net";
// import Select from 'datatables.net-select';
import axios from "axios";
import { useRouter } from "vue-router";
import Swal from "sweetalert2";
import toastr from "../../externals/toastr";
import { useStore } from "vuex";
const store = useStore();
const router = useRouter();

DataTable.use(DataTablesCore);

const users = ref();
onBeforeMount(async () => {
    let datas = await axios
        .get(`/admin_vue/user`)
        .catch((e) => console.log("error"));
    var { data } = datas;
    var userData = reactive([]);
    const deletePermission = computed(() =>
        store.getters.getAuthUser.permissionslist.includes("delete")
            ? true
            : false
    );
    data.map((data) => {
        let img =
            data.image === null
                ? `/dist/img/avatar5.png`
                : `/storage/${data.image}`;
        let dltBtn = deletePermission.value
            ? `<button data-id="${data.id}" id="deleteData" class='btn btn-danger' v-if="${dltBtn}" >Delete</button>`
            : ``;
        userData.push([
            `<img src="${img}" width="80">`,
            data.id,
            data.name,
            data.email,
            `Roles: ${data.roleslist} | Permissions: ${data.permissionslist}`,
            `<button id="editData" data-id="${data.id}"  class='btn btn-success mx-2'>Edit</button>${dltBtn}`,
        ]);
    });
    users.value = userData;
});
$(document).on("click", "#editData", function () {
    let id = $(this).data("id");
    router.push(`/admin/user/${id}/edit`);
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
                .delete(`/admin_vue/user/${id}`)
                .then((res) => {
                    toastr.success("User deleted");
                    users.value = users.value.filter((data) => data[1] !== id);
                })
                .catch((e) => console.log("error"));
        }
    });
});
onMounted(() => {});
</script>

<template>
    <Page_header page_name="Users" />

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        DataTable with minimal features &amp; hover style
                    </h3>
                    <button
                        class="btn btn-primary float-right"
                        @click="$router.push('/admin/user/create')"
                    >
                        <i class="fa fa-user-plus" aria-hidden="true"></i> Add
                        user
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
                            ordering: true,
                            info: true,
                            autoWidth: true,
                            responsive: true,
                            retrieve: true,
                        }"
                    >
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Id</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role/Permission</th>
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
