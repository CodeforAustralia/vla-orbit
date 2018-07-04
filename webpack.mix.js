const { mix } = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js(
            [
                'resources/assets/js/bookings_vue.js',
                'resources/assets/js/notifications_vue.js'
            ],
            'public/js/orbit.js'
        )
   .js('resources/assets/js/information_vue.js', 'public/js')
   .js('resources/assets/js/request_service_vue.js', 'public/js')
   .sass('resources/assets/sass/information.scss', 'public/css');
