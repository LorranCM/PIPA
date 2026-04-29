import { initializeApp } from "https://www.gstatic.com/firebasejs/10.12.2/firebase-app.js";
import { getAuth, createUserWithEmailAndPassword } from "https://www.gstatic.com/firebasejs/10.12.2/firebase-auth.js";

const firebaseConfig = {
    apiKey: "AIzaSyAUNZfC6a8e8A-5Wi8MgCUSZ8UPdKsacm8",
    authDomain: "pipa-35e1f.firebaseapp.com",
    projectId: "pipa-35e1f",
    storageBucket: "pipa-35e1f.firebasestorage.app",
    messagingSenderId: "63441751370",
    appId: "1:63441751370:web:e0068e3346b4b47912c5b3"
};

const app = initializeApp(firebaseConfig);
const auth = getAuth(app);

async function login() {

    const registration = document.getElementById("registration").value.trim() + "@app.com";
    const password = document.getElementById("password").value.trim();

    try {
        const userCredential = await signInWithEmailAndPassword(auth, registration, password);

        const token = await userCredential.user.getIdToken();

        console.log("Login OK");

        const response = await fetch("./auth/loginapi.php", {
            method: "POST",
            headers: {"Content-Type": "application/json"},
            body: JSON.stringify({ token })
        });

        const data = await response.json();
        console.log(data.uid);

        // if (data.ok) {
        //     window.location.href = data.redirect;
        // }

    } catch (error) {
            console.log("Erro:", error.code);

            if (error.code === "auth/user-not-found") {
            alert("Usuário não existe");
            }

            if (error.code === "auth/wrong-password") {
            alert("Senha incorreta");
            }
        }
    }

    document.getElementById("form-login").addEventListener("submit", function(event) {
    event.preventDefault();
    login();

});
