import preset from './tailwind.config.preset'

/** @type {import('tailwindcss').Config} */
export default {
    presets: [preset],
    content: [
        '../../../vendor/mulaidarinull/larascaff/src/resources/views/**/*.blade.php'
    ],
};
