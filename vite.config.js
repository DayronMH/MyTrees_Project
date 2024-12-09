import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';

export default defineConfig({
    plugins: [
        // Plugin para Laravel
        laravel({
            input: ['resources/styles/app.scss', 'resources/js/app.jsx'],
            refresh: true,
        }),
        // Plugin para React
        react(),
    ],
    server: {
        proxy: {
            '/api': {
                target: 'http://127.0.0.1:8000',
                changeOrigin: true,
                rewrite: (path) => path.replace(/^\/api/, '') // Remueve "/api" de la URL
            }
        }
    },
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
            '@': '/resources/styles', // Alias para que puedas importar estilos de manera más sencilla
        },
    },
});
