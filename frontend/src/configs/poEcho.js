import store from "@/store";
require('pusher-js')
import Echo from "laravel-echo";
import axios from "axios";

export default new Echo({
    broadcaster: 'pusher',
    key: process.env.VUE_APP_PUSHER_KEY,
    cluster: process.env.VUE_APP_PUSHER_CLUSTER,
    wsHost: process.env.VUE_APP_PUSHER_HOST,
    wsPort: process.env.VUE_APP_PUSHER_PORT,
    wssPort: process.env.VUE_APP_PUSHER_PORT,
    forceTLS: process.env.VUE_APP_PUSHER_TLS,
    disableStats: false,
    enabledTransports: ['ws', 'wss'],
    authorizer: (channel) => {
        return {
            authorize: (socketId, callback) => {
                axios.post(`${process.env.VUE_APP_BROADCASTING_AUTH_HOST}`, {
                    socket_id: socketId,
                    channel_name: channel.name
                })
                    .then(response => {
                        callback(false, response.data);
                    })
                    .catch(error => {
                        callback(true, error);
                    });
            }
        };
    },
});
/* wssPort: process.env.VUE_APP_PUSHER_PORT, */
    /* encrypted: process.env.VUE_APP_PUSHER_ENC, */

/* export default new Echo({
    broadcaster: 'pusher',
    key: process.env.VUE_APP_PUSHER_KEY,
    cluster: process.env.VUE_APP_PUSHER_CLUSTER,
    wsHost: process.env.VUE_APP_PUSHER_HOST,
    wsPort: process.env.VUE_APP_PUSHER_PORT,
    forceTLS: process.env.VUE_APP_PUSHER_TLS,
    disableStats: false,
    enabledTransports: ['ws'],
}); */

const poNotificationChannel = process.env.VUE_APP_PO_NOTIFICATION_CHANNEL

export function connectToPONotificationChannel(userId) {
    window.poEcho.private(`${poNotificationChannel}${userId}`)
        .notification((notification) => {
            notification.microservice = "PO"
            store.dispatch("notifications/fetchPushedNotification",notification);
        });
}

export function leavePONotificationChannel(userId) {
    window.poEcho.leaveChannel(`users.` + userId);

}
