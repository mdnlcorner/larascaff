import preset from './tailwind.config.preset'

/** @type {import('tailwindcss').Config} */
export default {
    presets: [preset],
    content: [
        '/vendor/mulaidarinull/larascaff/resources/views/**/*.blade.php'
    ],
};
