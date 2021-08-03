const mix = require('laravel-mix');

// mix.browserSync('eti-v2.prj');

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
   .sass('resources/sass/app.scss', 'public/css')
   .sass('resources/sass/sb-admin-2.scss', 'public/sb-admin/css');

mix.options({
   hmrOptions: {
      host: 'vorx.rto',
      port: 8080,
   }
});