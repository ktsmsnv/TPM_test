import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import fs from 'fs';
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
    ],
    server: {
        // https: {
        //     key: fs.readFileSync(path.resolve(__dirname, 'storage/app/ssl/privat_key.key')),
        //     cert: fs.readFileSync(path.resolve(__dirname, 'storage/app/ssl/certificate.crt')),
        // },
        host: '127.0.0.1',
        port: 5173,
    },
    optimizeDeps: {
        include: ['intro.js'],
    },
});
