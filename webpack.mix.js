const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css');

mix.browserSync({
    proxy: 'localhost:8000', // Adjust this to your local development URL
    files: [
        'resources/views/**/*.php',
        'app/**/*.php',
        'public/js/**/*.js',
        'public/css/**/*.css'
    ]
});
