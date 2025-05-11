import {
  getAuth,
  createUserWithEmailAndPassword,
  signInWithEmailAndPassword,
  GoogleAuthProvider,
  signInWithPopup,
  signOut,
  onAuthStateChanged,
} from "https://www.gstatic.com/firebasejs/11.0.1/firebase-auth.js";
import { initializeApp } from "https://www.gstatic.com/firebasejs/11.0.1/firebase-app.js";

// Firebase Configuration
const firebaseConfig = {
  apiKey: "AIzaSyCyagA8U4vkKCS2IrSXpjtpBwGCQHmYofU",
  authDomain: "pets-vibe-48eb9.firebaseapp.com",
  projectId: "pets-vibe-48eb9",
  storageBucket: "pets-vibe-48eb9.appspot.com",
  messagingSenderId: "197591384837",
  appId: "1:197591384837:web:e7cda59b166fa956fdad6c",
  measurementId: "G-0K06YXCTDD",
};

// Initialize Firebase
const app = initializeApp(firebaseConfig);
const auth = getAuth(app);
const provider = new GoogleAuthProvider();

// DOM Elements
const emailInput = document.getElementById("email");
const passwordInput = document.getElementById("password");
const modal = document.getElementById("modal");
const authForm = document.getElementById("authForm");
const loginRegisterBtn = document.getElementById("loginRegisterBtn");
const profilePanel = document.getElementById("profilePanel");
const closeProfilePanel = document.getElementById("closeProfilePanel");
const profileName = document.getElementById("profileName");
const profileEmail = document.getElementById("profileEmail");
const profilePic = document.getElementById("profilePic");
const logoutBtn = document.getElementById("logoutBtn");

// Google Sign-In
document.getElementById("googleSignInBtn").addEventListener("click", () => {
  signInWithPopup(auth, provider)
    .then((result) => {
      console.log("Signed in with Google:", result.user);
      closeModal();
    })
    .catch((error) => {
      alert("Error: " + error.message);
    });
});

// Register/Login Form Submission
authForm.onsubmit = async (event) => {
  event.preventDefault();

  const email = emailInput.value;
  const password = passwordInput.value;
  const isRegistering = document.getElementById("modalTitle").innerText === "Register";

  try {
    if (isRegistering) {
      await createUserWithEmailAndPassword(auth, email, password);
      alert("Account created successfully! You can now log in.");
      switchToLoginForm();
    } else {
      await signInWithEmailAndPassword(auth, email, password);
      closeModal();
    }
  } catch (error) {
    alert("Error: " + error.message);
  }
};

// Switch Modal Between Login and Register
document.getElementById("toggleForm").onclick = switchToLoginForm;

// Open/Close Modal
function closeModal() {
  modal.style.display = "none";
  emailInput.value = "";
  passwordInput.value = "";
}

function openModal() {
  modal.style.display = "flex";
}

document.getElementById("closeModal").onclick = closeModal;

// Switch To Login Form
function switchToLoginForm() {
  const modalTitle = document.getElementById("modalTitle");
  const authFormButton = authForm.querySelector("button");
  const toggleFormText = document.getElementById("toggleForm");

  if (modalTitle.innerText === "Register") {
    modalTitle.innerText = "Login";
    authFormButton.innerText = "Login";
    toggleFormText.innerText = "Register";
  } else {
    modalTitle.innerText = "Register";
    authFormButton.innerText = "Register";
    toggleFormText.innerText = "Login";
  }
}

// Open Profile Panel
function openProfilePanel(user) {
  console.log("Opening profile panel for: ", user); // Log the user object

  const derivedName = user.email ? user.email.split('@')[0] : "No Name Available";
  profileName.textContent = user.displayName || derivedName;
  profileEmail.textContent = user.email || "No Email Available";

  if (user.photoURL) {
    console.log("Profile picture found: ", user.photoURL);
    profilePic.src = user.photoURL;

    // Fallback if the image fails to load
    profilePic.onerror = () => {
      console.error("Failed to load profile picture, using fallback image.");
      profilePic.src = "http://localhost/Pet-Commerce-main/css/images/default-pic.svg";
    };
  } else {
    console.warn("No profile picture found. Using default.");
    profilePic.src = "http://localhost/Pet-Commerce-main/css/images/default-pic.svg";
  }

  profilePanel.style.right = "0"; // Open the profile panel
}

// Close Profile Panel
closeProfilePanel.addEventListener("click", () => {
  profilePanel.style.right = "-300px";
});

// Logout Function
logoutBtn.addEventListener("click", () => {
  console.log("Logging out...");
  signOut(auth)
    .then(() => {
      console.log("User logged out successfully.");
      resetUI();
      location.reload(); // Refresh the page to reset everything
    })
    .catch((error) => {
      console.error("Error signing out: " + error.message);
    });
});

// Reset UI After Logout
function resetUI() {
  loginRegisterBtn.innerHTML = "Login/Register";
  loginRegisterBtn.style.borderRadius = "10px";
  loginRegisterBtn.onclick = openModal;
  profilePanel.style.right = "-300px";
}

// Update UI On Login
function updateUIOnLogin(user) {
  console.log("User object on login: ", user);

  loginRegisterBtn.innerHTML = "Profile";
  loginRegisterBtn.onclick = () => openProfilePanel(user);
}

// Listen For Auth State Changes
onAuthStateChanged(auth, (user) => {
  if (user) {
    updateUIOnLogin(user);
  } else {
    resetUI();
  }
});
