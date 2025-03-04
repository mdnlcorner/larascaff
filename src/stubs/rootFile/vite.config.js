import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { glob } from 'glob';

const entries = glob.sync([
    './resources/scss/*.scss',
    './resources/js/pages/**/{*.ts,*.js}',
], {
    ignore: ['./resources/scss/_*.scss']
})

export default defineConfig({
    css: {
        preprocessorOptions: {
            scss: {
                api: 'modern-compiler'
            }
        }
    },
    plugins: [
        laravel({
            input: [
                ...entries,
                '/resources/css/app.css',
                '/resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
});
