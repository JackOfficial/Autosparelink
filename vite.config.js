import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { resolve } from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                // 1. Storefront Assets
                'resources/css/app.css', 
                'resources/js/app.js',
                
                // 2. Dashboard Bundle (Strategy A)
                // This single file now includes style.scss, custom.js, 
                // sidebar.js, and chart.js via your imports.
                'resources/js/userdashboard/main.js',
            ],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            '@': '/resources/js',
        },
    },
});