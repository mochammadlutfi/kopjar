const mix = require('laravel-mix');
const path = require('path');
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
    .vue()
    .sass('resources/sass/app.scss', 'public/css')
    .browserSync('localhost:8000')
    .disableNotifications()
    .options({
        processCssUrls: false
    })
    .webpackConfig({
        output: { chunkFilename: 'js/[name].[contenthash].js' },
        resolve: {
          alias: {
            vue$: 'vue/dist/vue.runtime.js',
            '@': path.resolve('resources/js'),
            ziggy: path.resolve('vendor/tightenco/ziggy/dist'),
          },
        },
      });