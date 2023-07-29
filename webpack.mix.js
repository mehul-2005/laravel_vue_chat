const mix = require("laravel-mix");

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js("resources/js/app.js", "public/js")
    .js("resources/js/app2.js", "public/js")
    .postCss("resources/css/app.css", "public/css", [
        //
    ]).postCss("resources/css/app2.css", "public/css")
    .vue();
mix.webpackConfig({
    devServer: {
        proxy: {
            "*": "http://127.0.0.1:8000",
        },
    },
});
mix.options({
    hmrOptions: {
        host: "192.168.1.8", // set this to the local ip address (192.168.0.**) of whatever device is running `php artisan serve`
        port: 8000, // set this to which port `php artisan serve` is using.
    },
});
// mix.copy("node_modules/admin-lte/dist/img", "public/dist/img");
mix.disableNotifications();
