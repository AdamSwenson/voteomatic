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

// dev For VOT-288
// mix.webpackConfig({
//         resolve: {
//             alias: {
//                 vue: '@vue/compat'
//             }
//         },
//         module: {
//             rules: [
//                 {
//                     test: /\.vue$/,
//                     loader: 'vue-loader',
//                     options: {
//                         compilerOptions: {
//                             compatConfig: {
//                                 // MODE: 2
//                                 MODE: 3
//                             }
//                         }
//                     }
//                 }
//             ]
//         }
// });

// //dev Attempting to remove console log statements from production. Doesn't seem to work though.
// if (mix.inProduction()) {
//     mix.options({
//         terser: {
//             terserOptions: {
//                 compress: {
//                     drop_console: true
//                 }
//             }
//         }
//     });
//
//     mix.js('resources/js/app.js', 'public/js').vue().sourceMaps();
//
//     // mix.version();
// }

mix.js('resources/js/app.js', 'public/js').vue().sourceMaps();

mix.sass('resources/sass/app.scss', 'public/css');
