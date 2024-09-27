import Echo from "laravel-echo";
import Pusher from "pusher-js";

window.Pusher = Pusher;
window.Echo = new Echo({
    authEndpoint: "/broadcasting/auth",
    broadcaster: "pusher",
    key: "c21e892f006db445bd2d",
    cluster: "us2",
    forceTLS: true,
});

document.addEventListener("DOMContentLoaded", () => {
    Livewire.on("userSelected", (user_id) => {
        const minId = Math.min(user_id, window.userId);
        const maxId = Math.max(user_id, window.userId);

        window.Echo.join("chat." + minId + "." + maxId)
            .here((users) => {
                console.log("Online users:", users);
            })
            .joining((user) => {
                console.log(user.name + " is online");
            })
            .leaving((user) => {
                console.log(user.name + " has gone offline");
            })
            .listen(".PersonalChatEvent", (event) => {
                console.log("Event received:", event);
            });
    });

    Livewire.on("addMessage", (message) => {
        console.log("addMessage:", message);
    });
});
