import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

const csrfToken = document
    .querySelector('meta[name="csrf-token"]')
    ?.getAttribute('content');

const isLocalHost =
    window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1';

const pageIsHttps = window.location.protocol === 'https:';

const envScheme = (import.meta.env.VITE_REVERB_SCHEME ?? '').toLowerCase();
const envWantsTls = envScheme === 'https';

const forceTLS = isLocalHost ? false : (envWantsTls || pageIsHttps);

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST ?? window.location.hostname,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 8080,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 8080,
    forceTLS,
    enabledTransports: ['ws', 'wss'],
    authEndpoint: '/broadcasting/auth',
    auth: {
        headers: csrfToken ? { 'X-CSRF-TOKEN': csrfToken } : {},
    },
});
