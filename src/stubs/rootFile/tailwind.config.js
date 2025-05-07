import preset from './vendor/mulaidarinull/larascaff/tailwind.config.preset'

/** @type {import('tailwindcss').Config} */
export default {
    presets: [preset],
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './vendor/mulaidarinull/larascaff/resources/views/**/*.blade.php'
    ],
};
