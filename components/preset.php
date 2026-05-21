<?php
require __DIR__ . '/../packages/configdb.php';
session_start();

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

// verifica se o usuario esta logado, se estiver, redireciona para a pagina de perfil
function loggedIn_verification() {
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
        header("Location: Home.php");
        exit;
    }
}

// verifica se o usuario esta deslogado, se estiver, redireciona para a pagina de login
function loggedOut_verification() {
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        header("Location: login.php");
        exit;
    }
}