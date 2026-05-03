import { initializeApp } from "https://www.gstatic.com/firebasejs/10.12.2/firebase-app.js";
import { getAuth, signInWithEmailAndPassword } from "https://www.gstatic.com/firebasejs/10.12.2/firebase-auth.js";
import { is_valid_registration, is_valid_password } from "./validate_user_data.js";

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

let running = false;

async function login() {

    if (running) return;
    running = true;

    const registration = document.getElementById("registration").value.trim();
    const password = document.getElementById("password").value.trim();

    const registration_error = is_valid_registration(registration);
    const password_error = is_valid_password(password);

    if (registration_error) {
        // tratar erros de registro
        running = false;
        return;
    }

    if (password_error) {
        // tratar erros de senha
        running = false;
        return;
    }

    try {
        const userCredential = await signInWithEmailAndPassword(auth, registration + "@app.com", password);
        const token = await userCredential.user.getIdToken();

        const response = await fetch("auth/token_validateapi.php", {
            method: "POST",
            headers: {"Content-Type": "application/json"},
            body: JSON.stringify({ token })
        });
        const data = await response.json();
        
        // DEBUG
        // console.log(data.uid);
        // console.log(data.role);
        
        if (data.ok) {
            window.location.href = data.redirect;
        } else {
            throw {code: "token_validateapi-error", message: data.error };
        }

    } catch (error) {

        switch (error.code) {
            case "auth/invalid-credential":
                alert("Credenciais inválidas");
                break;
            case "token_validateapi-error":
                alert("Erro: " + error.message);
                break;
            default:
                alert("Erro: " + error.message);
        }
    }

    running = false;
}

document.getElementById("form-login").addEventListener("submit", function(event) {
event.preventDefault();
login();

});
