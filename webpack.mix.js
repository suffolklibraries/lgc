const mix = require('laravel-mix');
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

mix.js('resources/js/site.js', 'public/js')

mix.postCss('resources/css/site.css', 'public/css', [
    require('postcss-import'),
    require('postcss-nested'),
    require('postcss-preset-env')({stage: 0}),
    require("tailwindcss")
])

if (mix.inProduction()) {
   mix.version();
} else {
    mix.browserSync({
        proxy: 'lgc.test',
        notify: false,
        open: false,
        files: [
            '**/*.html',
            '**/*.blade.php',
            '**/*.css',
            '**/*.js'
        ]
    })
}

/*
 |--------------------------------------------------------------------------
 | Statamic Control Panel
 |--------------------------------------------------------------------------
 |
 | Feel free to add your own JS or CSS to the Statamic Control Panel.
 | https://statamic.dev/extending/control-panel#adding-css-and-js-assets
 |
 */

// mix.js('resources/js/cp.js', 'public/vendor/app/js')
//    .postCss('resources/css/cp.css', 'public/vendor/app/css', [
//     require('postcss-import'),
//     require('tailwindcss'),
//     require('postcss-nested'),
//     require('postcss-preset-env')({stage: 0})
// ])
