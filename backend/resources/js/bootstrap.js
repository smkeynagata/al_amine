import axios from 'axios';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const tokenElement = document.head.querySelector('meta[name="csrf-token"]');

if (tokenElement) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = tokenElement.content;
} else {
    console.warn('Balise meta CSRF introuvable. Les requêtes POST pourraient échouer.');
}

window.Pusher = Pusher;

const pusherKey = import.meta.env.VITE_PUSHER_APP_KEY;

if (pusherKey) {
    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: pusherKey,
        cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? 'mt1',
        wsHost: import.meta.env.VITE_PUSHER_HOST ?? `ws-${import.meta.env.VITE_PUSHER_APP_CLUSTER ?? 'mt1'}.pusher.com`,
        wsPort: Number(import.meta.env.VITE_PUSHER_PORT ?? (import.meta.env.VITE_PUSHER_SCHEME === 'https' ? 443 : 80)),
        wssPort: Number(import.meta.env.VITE_PUSHER_PORT ?? 443),
        forceTLS: (import.meta.env.VITE_PUSHER_SCHEME ?? 'https') === 'https',
        enabledTransports: ['ws', 'wss'],
    });
} else {
    console.warn('Echo non initialisé : VITE_PUSHER_APP_KEY manquant.');
    window.Echo = null;
}
