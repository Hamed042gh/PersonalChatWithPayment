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
        window.Echo.private("chat." + user_id + "." + window.userId)
        .listen(
            ".PersonalChatEvent",
            (event) => {
                console.log("Event received:", event);
                
            }
        );
    });
});
