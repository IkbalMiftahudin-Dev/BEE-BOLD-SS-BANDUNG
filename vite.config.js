import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';
import os from 'os';

// Resolve host IP for mobile/LAN access
function getLocalIp() {
    if (process.env.VITE_HOST) {
        return process.env.VITE_HOST;
    }
    const interfaces = os.networkInterfaces();
    for (const name of Object.keys(interfaces)) {
        if (/wi-?fi|wlan/i.test(name)) {
            for (const iface of interfaces[name]) {
                if (iface.family === 'IPv4' && !iface.internal) {
                    return iface.address;
                }
            }
        }
    }
    for (const name of Object.keys(interfaces)) {
        if (!/vmware|virtual|vbox|vethernet|loopback/i.test(name)) {
            for (const iface of interfaces[name]) {
                if (iface.family === 'IPv4' && !iface.internal && !iface.address.startsWith('169.254')) {
                    return iface.address;
                }
            }
        }
    }
    return 'localhost';
}

const localIp = getLocalIp();

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
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
        host: '0.0.0.0',
        cors: true,
        hmr: {
            host: localIp,
        },
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
