import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/scss/styles.scss',
                'resources/scss/styles1.scss',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
});
