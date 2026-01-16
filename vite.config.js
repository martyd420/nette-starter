import { defineConfig } from 'vite';
import nette from '@nette/vite-plugin';
import path from 'path';

export default defineConfig({
    build: {
        outDir: 'web/assets',  // where compiled files go
    },
    root: '.',
    resolve: {
        alias: {
            '@contributte/datagrid/assets': path.resolve(__dirname, 'node_modules/@contributte/datagrid/assets'),
            '@contributte/datagrid@contributte/datagrid': path.resolve(__dirname, 'node_modules/@contributte/datagrid/assets/index.ts'),
            '@': path.resolve(__dirname, 'private/assets'),
        }
    },
    plugins: [
        nette({
            entry: [
                'private/assets/js/admin/admin.js',
                'private/assets/js/front/front.js',
            ],
        }),
    ],
});