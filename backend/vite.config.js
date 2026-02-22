import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'Modules/Fabric/Resources/assets/sass/app.scss',
                'Modules/Fabric/Resources/assets/js/app.js'
            ],
            refresh: true,
        }),
    ],
});
