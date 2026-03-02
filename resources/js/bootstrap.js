import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,  // or process.env.MIX_PUSHER_APP_KEY if using mix
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true
});