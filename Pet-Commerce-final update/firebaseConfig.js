import { initializeApp } from "https://www.gstatic.com/firebasejs/11.0.1/firebase-app.js";

// Your Firebase configuration
const firebaseConfig = {
    apiKey: "AIzaSyCyagA8U4vkKCS2IrSXpjtpBwGCQHmYofU",
    authDomain: "pets-vibe-48eb9.firebaseapp.com",
    projectId: "pets-vibe-48eb9",
    storageBucket: "pets-vibe-48eb9.appspot.com",
    messagingSenderId: "197591384837",
    appId: "1:197591384837:web:e7cda59b166fa956fdad6c",
    measurementId: "G-0K06YXCTDD"
};

// Initialize Firebase
const app = initializeApp(firebaseConfig);
export default app;
