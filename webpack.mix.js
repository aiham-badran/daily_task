const mix = require("laravel-mix");

/**
 /------------------------------------------------------------------------
 / i18n
 /------------------------------------------------------------------------
 / Extend Mix with the "i18n" method, that loads the vue-i18n-loader
 */

mix.extend(
  "i18n",
  new (class {
    webpackRules() {
      return [
        {
          resourceQuery: /blockType=i18n/,
          type: "javascript/auto",
          loader: "@kazupon/vue-i18n-loader",
        },
      ];
    }
  })()
);

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

mix.sass("resources/sass/app.scss", "public/css");
mix.i18n().js("resources/js/app.js", "public/js");

mix.browserSync("http://localhost:8000/");
