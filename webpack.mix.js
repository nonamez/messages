let mix = require('laravel-mix');

mix.setPublicPath('public');

mix.js('resources/assets/js/app.js', 'public/js/app.js');
mix.sass('resources/assets/sass/app.scss', 'public/css/app.css');

mix.version();