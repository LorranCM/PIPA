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

async function register() {

    // const registration = document.getElementById("registration")?.value.trim() + "@app.com";
    // const name = document.getElementById("name")?.value.trim();
    // const lastname = document.getElementById("lastname")?.value.trim();
    // const email = document.getElementById("email")?.value.trim();
    // const email_confirmation = document.getElementById("email_confirmation")?.value.trim();
    // const password = document.getElementById("password")?.value.trim();
    // const password_confirmation = document.getElementById("password_confirmation")?.value.trim();
    // const role = document.querySelector('input[name="role"]:checked')?.value;

    const registration = "teste@app.com";
    const name = "a";
    const lastname = "a";
    const email = "teste@app.com";
    const email_confirmation = "teste@app.com";
    const password = "12345678";
    const password_confirmation = "12345678";
    const role = "student";

    
    if (!registration || !name || !lastname || !email || !email_confirmation || !password || !password_confirmation || !role) {
        console.log("Preencha todos os campos");
        return;
    }
    
    if (email !== email_confirmation) {
        console.log("Os e-mails não coincidem");
        return;
    }
    
    if (password !== password_confirmation) {
        console.log("As senhas não coincidem");
        return;
    }

    // console.log("matricula:", registration);
    // console.log("nome:", name);
    // console.log("sobrenome:", lastname);
    // console.log("e-mail:", email);
    // console.log("confirmar e-mail:", email_confirmation);
    // console.log("senha:", password);
    // console.log("confirmar senha:", password_confirmation);
    // console.log("tipo de usuário:", role);

    console.log("Validação OK");

    try {
        const userCredential = await createUserWithEmailAndPassword(auth, registration, password);

        const token = await userCredential.user.getIdToken();

        console.log("Registro OK");

        const payload = {
            token: token,
            registration: registration,
            name: name,
            lastname: lastname,
            email: email,
            password: password,
            role: role
        };

        const response = await fetch("./auth/registerapi.php", {
            method: "POST",
            headers: {"Content-Type": "application/json"},
            body: JSON.stringify(payload)
        });

        const data = await response.json();
        
        if (data.ok) {
            console.log(data.uid);
        }

    } catch (error) {
            console.log("Erro:", error.code);
        }
    }

    document.getElementById("form-login").addEventListener("submit", function(event) {
    event.preventDefault();
    register();

});
