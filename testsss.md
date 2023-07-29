for vue install in laravel

npm i vue
npm i vue-loader

-   in resources\js\app.js
    import { createApp } from 'vue';
    import App from './admin/App.vue';

    createApp(App).mount('#app');

-   in webpack.mix.js
    .vue();
    add
    npm run dev

-   in resources\views\Admin.blade.php
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ mix('js/app.js') }}" defer></script>

         <div id="app">
        </div>
        npm run hot

    -   for admin-lte
        npm install admin-lte@^3.0 --save
        copy code from view source
        and imported extenral css js add in resources\js\app.js or resources\css\app.css

    -   in vue js datatable - https://datatables.net/blog/2022-06-22-vue
        npm install --save datatables.net-vue3
        import DataTable from 'datatables.net-vue3';
        import DataTablesCore from 'datatables.net';

    DataTable.use(DataTablesCore);
    resources\js\admin\components\User\ListUser.vue
