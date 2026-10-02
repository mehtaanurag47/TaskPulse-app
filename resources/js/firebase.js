import { initializeApp } from "firebase/app";
import {
    getMessaging,
    getToken,
    isSupported,
    onMessage
} from "firebase/messaging";

console.clear();

console.log("🚀 Firebase JS Started");

// Firebase Config
const firebaseConfig = {
    apiKey: "AIzaSyCDuolBT2631uLNGnE4N2s6Z7oJP-QXt3o",
    authDomain: "taskpulse-strc.firebaseapp.com",
    projectId: "taskpulse-strc",
    storageBucket: "taskpulse-strc.firebasestorage.app",
    messagingSenderId: "64710165230",
    appId: "1:64710165230:web:f266ee7c2d0233de0b29ff"
};

// Initialize Firebase
const app = initializeApp(firebaseConfig);

console.log("✅ Firebase Initialized");

async function initFCM() {

    try {

        console.log("STEP 1 : Checking Browser Support");

        const supported = await isSupported();

        console.log("Supported :", supported);

        if (!supported) {
            console.log("❌ Firebase Messaging is NOT supported.");
            return;
        }

        console.log("STEP 2 : Creating Messaging");

        const messaging = getMessaging(app);
        onMessage(messaging, (payload) => {

    console.log("🔥 Foreground Message Received");

    console.log(payload);

    const title = payload.data?.title || "TaskPulse Reminder";
    const body = payload.data?.body || "You have a task reminder.";

    new Notification(
        title,
        {
            body: body,
            icon: "/favicon.ico"
        }
    );

});

        console.log("STEP 3 : Registering Service Worker");

        const registration = await navigator.serviceWorker.register(
            "/firebase-messaging-sw.js"
        );

        console.log("✅ Service Worker Registered");

        console.log("STEP 4 : Asking Notification Permission");

        const permission = await Notification.requestPermission();

        console.log("Permission :", permission);

        if (permission !== "granted") {
            console.log("❌ Notification Permission Denied");
            return;
        }

        console.log("STEP 5 : Getting FCM Token");

        const token = await getToken(messaging, {
            vapidKey: "BCTwId5-DbQ5xOQyILe0hdYjJpgKt155bVA8otvRJ-m1tBoe9UHaqUa_mV38b4kHOictTbXhNnquobCNhL9QPK8",
            serviceWorkerRegistration: registration
        });

        console.log("✅ FCM Token Generated");

        console.log(token);

        if (!token) {
            console.log("❌ No FCM Token Found");
            return;
        }

        console.log("STEP 6 : Sending Token To Laravel");

        const csrf = document.querySelector('meta[name="csrf-token"]');

        if (!csrf) {
            console.log("❌ CSRF Token Not Found");
            return;
        }

        const response = await fetch("/save-fcm-token", {

            method: "POST",

            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "X-CSRF-TOKEN": csrf.content
            },

            body: JSON.stringify({
                token: token
            })

        });

        console.log("HTTP Status :", response.status);

        const text = await response.text();

        console.log("Laravel Response:");

        console.log(text);

        console.log("🎉 FCM Token Successfully Saved");

    }
    catch (error) {

        console.error("🔥 Firebase Error");

        console.error(error);

    }

}

initFCM();