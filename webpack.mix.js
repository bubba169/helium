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

mix.js('resources/js/helium.js', 'public/js')
    .postCss('resources/css/helium.css', 'public/css', [
        require('postcss-import'),
        require('postcss-nesting'),
        require('tailwindcss')
    ]);
