importScripts("https://www.gstatic.com/firebasejs/12.5.0/firebase-app-compat.js");
importScripts("https://www.gstatic.com/firebasejs/12.5.0/firebase-messaging-compat.js");

firebase.initializeApp({
    apiKey: "AIzaSyCDuolBT2631uLNGnE4N2s6Z7oJP-QXt3o",
    authDomain: "taskpulse-strc.firebaseapp.com",
    projectId: "taskpulse-strc",
    storageBucket: "taskpulse-strc.firebasestorage.app",
    messagingSenderId: "64710165230",
    appId: "1:64710165230:web:f266ee7c2d0233de0b29ff"
});

const messaging = firebase.messaging();

messaging.onBackgroundMessage(function (payload) {

    console.log("Background Message:", payload);

    const title = payload.data?.title || "TaskPulse Reminder";
    const body = payload.data?.body || "You have a task reminder.";

    self.registration.showNotification(
        title,
        {
            body: body,
            icon: "/favicon.ico"
        }
    );

});

// ---------------------------------------------------------------
// PWA OFFLINE SUPPORT
// Caches the dashboard shell + key assets so the app can still
// open and show previously-loaded data without an internet connection.
// ---------------------------------------------------------------

const CACHE_NAME = "taskpulse-cache-v1";

const OFFLINE_URLS = [
    "/dashboard",
    "/tasks",
    "/manifest.json",
    "/favicon.ico"
];

self.addEventListener("install", (event) => {
    self.skipWaiting();
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            return cache.addAll(OFFLINE_URLS).catch((err) => {
                console.warn("Offline cache pre-fill skipped some URLs:", err);
            });
        })
    );
});

self.addEventListener("activate", (event) => {
    event.waitUntil(
        caches.keys().then((keys) => {
            return Promise.all(
                keys
                    .filter((key) => key !== CACHE_NAME)
                    .map((key) => caches.delete(key))
            );
        })
    );
    self.clients.claim();
});

self.addEventListener("fetch", (event) => {

    // Only handle GET requests for our own origin; let everything
    // else (POST forms, API calls, Firebase, etc.) pass through normally.
    if (event.request.method !== "GET") return;
    if (!event.request.url.startsWith(self.location.origin)) return;

    event.respondWith(
        fetch(event.request)
            .then((response) => {
                const responseClone = response.clone();
                caches.open(CACHE_NAME).then((cache) => {
                    cache.put(event.request, responseClone);
                });
                return response;
            })
            .catch(() => {
                return caches.match(event.request).then((cached) => {
                    return cached || caches.match("/dashboard");
                });
            })
    );

});