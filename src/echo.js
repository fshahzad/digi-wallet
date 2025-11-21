import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

const options = {
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true,
    encrypted: true,
    //authEndpoint: '/broadcasting/auth2', // if using sanctum
    //auth: {
    //    headers: {
    //        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
    //    }
    //},
    // include auth headers if using token auth:
    //auth: { headers: { Authorization: "Bearer " + token } } //Authorization: `Bearer ${localStorage.getItem('access_token')}`
    //wsHost: import.meta.env.VITE_PUSHER_HOST ?? `ws-${import.meta.env.VITE_PUSHER_APP_CLUSTER}.pusher.com`,
    //wsPort: import.meta.env.VITE_PUSHER_PORT ?? 80,
    //wssPort: import.meta.env.VITE_PUSHER_PORT ?? 443,
    //forceTLS: (import.meta.env.VITE_PUSHER_SCHEME ?? 'https') === 'https',
    //enabledTransports: ['ws', 'wss'],
}

//window.Echo = new Echo({
//    ...options,
//    client: new Pusher(options.key, options)
//});

const echo = new Echo({
    ...options,
});

export default echo;
