import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/auth.css',
                'resources/css/comanda-rapida.css',
                'resources/css/contract.css',
                'resources/css/dashboard.css',
                'resources/css/eventos.css',
                'resources/css/fiori.css',
                'resources/css/forms.css',
                'resources/css/ingredientes.css',
                'resources/css/platillos.css',
                'resources/css/reportes.css',
                'resources/css/salones.css',
                'resources/css/sucursales.css',
                'resources/css/welcome.css',
                'resources/js/app.js',
            ],
            refresh: true,
            fonts: [
                bunny('Instrument Sans', {
                    weights: [400, 500, 600],
                }),
            ],
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
