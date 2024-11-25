import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/styles/app.scss', 'resources/js/app.jsx'],
            refresh: true,
        }),
        react(),
    ],
    css: {
        preprocessorOptions: {
            scss: {
                additionalData: `
                    @use "sass:math";
                    @use "@/abstracts/variables" as var;
                    @use "@/abstracts/mixins" as mix;
                `
            }
        }
    },
    resolve: {
        alias: {
            '@': '/resources/styles',
        },
    },
});