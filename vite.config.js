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
                
                // 2. Shop Dashboard Bundle
                // This handles your Shop/Seller specific styles and logic
                'resources/js/userdashboard/main.js',

                // 3. Normal User Dashboard Bundle
                // Create this file to handle the specific "Soft UI" user layout
                'resources/js/userdashboard/user-main.js',
            ],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            '@': resolve(__dirname, 'resources/js'),
        },
    },
});