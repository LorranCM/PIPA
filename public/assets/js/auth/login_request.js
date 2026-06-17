import { initializeApp } from "https://www.gstatic.com/firebasejs/10.12.2/firebase-app.js";
import { getAuth, signInWithEmailAndPassword } from "https://www.gstatic.com/firebasejs/10.12.2/firebase-auth.js";
import { is_valid_registration, is_valid_password } from "./validate_user_data.js";

// configurações do Firebase
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

const buttonLogin = document.getElementById("button-login");
const buttonLogin_default_message = buttonLogin.textContent;

// Ao apertar o botão de login a função login é chamada e não é instantânea. Afim de evitar que múltiplos clíques
// enquanto a função ainda está processando causem problemas, a variável "running" é usada para garantir que a função 
// login não seja executada mais de uma vez ao mesmo tempo. Caso ocorra algum erro durante o processo ela é setada pra
// false novamente
let running = false;

async function login() {

    if (running) return;
    running = true;
    buttonLogin.textContent = "...";
    // Coleta os dados do formulário
    const registration = document.getElementById("registration").value.trim();
    const password = document.getElementById("password").value.trim();

    // Valida os dados usando as funções de validação importadas do validate_user_data.js
    const registration_error = is_valid_registration(registration);
    const password_error = is_valid_password(password);

    if (registration_error) {
        // INACABADO: adicionar exibicao de erro de matricula (aviso para o usuario)
        stop_login();
        return;
    }

    if (password_error) {
        // INACABADO: Adicionar exibicao de erro de senha (aviso para o usuario)
        stop_login();
        return;
    }

    try {
        const userCredential = await signInWithEmailAndPassword(auth, registration + "@app.com", password);
        const token = await userCredential.user.getIdToken();
        
        const response = await fetch(baseUrl + "/auth/login/validation", {
            method: "POST",
            headers: {"Content-Type": "application/json"},
            body: JSON.stringify({ token })
        });

        const data = await response.json();

        if (data.success) {
            window.location.href = data.redirect;
        } else {
            const error = new Error();
            error.code = "invalid-token";
            throw error;
        }
        
    } catch (error) {

        if (error.code == "auth/invalid-credential") {
            // INACABADO: mudar alert para norma de exibicao de erro como e para senha e email
            // avisar que as credenciais informadas sao invalidas!!!!
            alert("Credenciais inválidas");
        }
        if (error.code == "invalid-token") {
            alert("Token inválido");
        }
    }

    stop_login();
}

function stop_login() {
    running = false;
    buttonLogin.textContent = buttonLogin_default_message;
}

document.getElementById("form-login").addEventListener("submit", function(event) {
    event.preventDefault();
    login();
});