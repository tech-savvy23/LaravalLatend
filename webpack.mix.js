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

mix.js([
    'resources/js/jquery-1.10.2.min.js',
    // 'resources/js/app.js',
    'resources/js/easing.js',
    'resources/js/move-top.js',
    'resources/js/slick.js']
    , 'public/js/app.js')
    .styles([
        'resources/sass/slick.css.css', 
        'resources/sass/style.css'], 
        'public/css/app.css');