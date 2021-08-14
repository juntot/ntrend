const mix = require('laravel-mix');

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

mix.js('resources/js/app.js', 'public/js')
mix.js('resources/js/public-app.js', 'public/js')
mix.js('resources/js/calendar-app.js', 'public/js')
   mix.styles([
      'resources/assets/css/admin.css',
      'resources/assets/css/admin-media.css',
   ], 'public/css/admin.css')
   mix.styles([
      'resources/assets/css/style.css',
      'resources/assets/css/media.css',
      'resources/assets/css/animate.css',
   ], 'public/css/app.css')
   mix.styles([
      'resources/assets/css/calendar-app.css',
   ], 'public/css/calendar.app.min.css');;
//    .options({
//       processCssUrls: false
//   });
   // .sass('resources/sass/app.scss', 'public/css');
