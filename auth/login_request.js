import { initializeApp } from "https://www.gstatic.com/firebasejs/10.12.2/firebase-app.js";
import { getAuth, signInWithEmailAndPassword } from "https://www.gstatic.com/firebasejs/10.12.2/firebase-auth.js";
import { is_valid_registration, is_valid_password } from "./validate_user_data.js";

// configurações do Firebase (talvez aqui nao seja o lugar mais seguro pra isso estar)
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

// Ao apertar o botao de login a funcao login e chamada e nao e instantanea. Afim de evitar que multiplos cliques
// enquanto a funcao ainda esta processando causem problemas, a variavel "running" e usada para garantir que a funcao 
// login nao seja executada mais de uma vez ao mesmo tempo. Caso ocorra algum erro durante o processo ela e setada pra
// false novamente
let running = false;

async function login() {

    if (running) return;
    running = true;
    buttonLogin.textContent = "..."; // MUDAR: improviso de loading, mudar para algo mais elegante depois

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

        const response = await fetch("auth/token_validateapi.php", {
            method: "POST",
            headers: {"Content-Type": "application/json"},
            body: JSON.stringify({ token })
        });

        const data = await response.json();
        
        if (data.success) {
            // Redireciona o usuario para a landing, que verificara se o usuario esta logado, redirecionando-o para sua pagina de
            // acordo com seu cargo
            window.location.href = data.redirect;
        } else {
            throw {code: "token_validateapi-error", message: data.error };
        }

    } catch (error) {

        // Em caso de erro, a sessão é abortada para garantir que nenhum dado da api permaneca na sessao
        fetch("auth/abort.php");

        switch (error.code) {
            case "auth/invalid-credential":
                // INACABADO: mudar alert para norma de exibicao de erro como e para senha e email
                // avisar que as credenciais informadas sao invalidas!!!!
                alert("Credenciais inválidas");
                break;
            case "token_validateapi-error":
                console.error("Erro: " + error.message);
                break;
            default:
                console.error("Erro: " + error.message);
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