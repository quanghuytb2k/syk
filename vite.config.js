import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
        vue(),
    ],
    server: {
        host: true,
        hmr: {
            host: 'localhost',
        },
    },
    resolve: {
        alias: [
            {
                find: /~(.+)/,
                replacement: path.join(process.cwd(), 'node_modules/$1'),
            },
        ],
    },
    build: {
        sourcemap: true,
    },
});
