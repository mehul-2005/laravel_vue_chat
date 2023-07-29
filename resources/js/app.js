require("./bootstrap");
import "admin-lte/plugins/jquery/jquery.min.js";
import "admin-lte/plugins/bootstrap/js/bootstrap.bundle.min.js";
import "admin-lte/plugins/datatables/jquery.dataTables.min.js";
import "admin-lte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js";
import "admin-lte/plugins/datatables-responsive/js/dataTables.responsive.min.js";
import "admin-lte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js";
import "admin-lte/plugins/datatables-buttons/js/dataTables.buttons.min.js";

import "admin-lte/plugins/datatables-buttons/js/buttons.bootstrap4.min.js";
import "admin-lte/dist/js/adminlte.min.js";

import { createApp } from "vue";
import App from "./admin/App.vue";
import router from "./admin/Router/Router";
import store from "./admin/store/store";
import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

createApp(App).use(router).use(store).use(VueSweetalert2).mount("#app");
