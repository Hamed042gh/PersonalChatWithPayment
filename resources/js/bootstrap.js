import Echo from "laravel-echo";
import Pusher from "pusher-js";

window.Pusher = Pusher;

const isChatPage = window.location.pathname === '/chat';
if (isChatPage) {
    window.Echo = new Echo({
        authEndpoint: "/broadcasting/auth",
        broadcaster: "pusher",
        key: "c21e892f006db445bd2d",
        cluster: "us2",
        forceTLS: true,
    });

    document.addEventListener("DOMContentLoaded", () => {
        if (typeof Livewire !== "undefined") {
            Livewire.on("userSelected", (user_id) => {
                const minId = Math.min(user_id, window.userId);
                const maxId = Math.max(user_id, window.userId);

                window.Echo.private("chat." + minId + "." + maxId).listen(
                    ".PersonalChatEvent",
                    (event) => {
                        Livewire.dispatch("new", event);
                    }
                );
            });
        }
    });
}
