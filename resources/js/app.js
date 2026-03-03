import "./bootstrap";
import Alpine from "alpinejs";
import collapse from "@alpinejs/collapse";
import focus from "@alpinejs/focus";
import "notyf/notyf.min.css";
import { Notyf } from "notyf";
import { createIcons, icons } from "lucide";

window.Alpine = Alpine;

Alpine.plugin(collapse);
Alpine.plugin(focus);

/* -------------------------------------------------
|  Notification Bell Component
-------------------------------------------------- */
Alpine.data("notificationBell", () => ({
    open: false,
    notifications: [],
    unread: 0,
    animate: false,
    audio: null,

    init() {
        this.fetchNotifications();

        // 🔊 Load sound
        this.audio = new Audio("/notification.mp3");
        this.audio.volume = 0.4;

        if (window.Echo && window.userId) {
            const channel = window.Echo.private(
                `App.Models.User.${window.userId}`,
            );

            channel.notification((notification) => {
                const data = notification.data ?? notification;
                const id = notification.id;

                if (!id) return;
                if (this.notifications.some((n) => n.id === id)) return;

                this.notifications.unshift({
                    id: id,
                    title: data.title ?? "",
                    body: data.body ?? "",
                    status: data.status ?? "",
                    job_post_id: data.job_post_id ?? null,
                    time: "Just now",
                    read: false,
                });

                this.unread++;

                this.animate = true;
                setTimeout(() => (this.animate = false), 600);

                this.audio?.play().catch(() => {});
            });
        }
    },

    fetchNotifications() {
        fetch("/notifications")
            .then((res) => res.json())
            .then((data) => {
                this.notifications = data.notifications ?? [];
                this.unread = data.unread ?? 0;
            });
    },

    markAllRead() {
        fetch("/notifications/mark-all", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
            },
        });

        this.notifications.forEach((n) => (n.read = true));
        this.unread = 0;
    },

    markSingleRead(notification) {
        if (notification.read) return;

        fetch(`/notifications/${notification.id}/read`, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
            },
        });

        notification.read = true;
        this.unread--;
    },
}));

Alpine.start();

/* -------------------------------------------------
|  Lucide Icons
-------------------------------------------------- */
document.addEventListener("DOMContentLoaded", () => {
    createIcons({ icons });
});

document.addEventListener("livewire:init", () => {
    Livewire.hook("morph.updated", () => {
        createIcons({ icons });
    });
});

/* -------------------------------------------------
|  Toast (Notyf)
-------------------------------------------------- */
window.notyf = new Notyf({
    duration: 2200,
    position: { x: "right", y: "top" },
    dismissible: true,
    ripple: false,
});

window.toast = (type = "info", message = "") => {
    if (!window.notyf) return;

    if (type === "success") return window.notyf.success(message);
    if (type === "error") return window.notyf.error(message);

    return window.notyf.open({ type, message });
};
