<?php
session_start();

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $matricula = $_POST['matricula'] ?? '';
    $password = $_POST['password'] ?? '';

    // Carrega a base de usuários
    $users = json_decode(file_get_contents('users.json'), true) ?? [];

    if (isset($users[$matricula]) && $users[$matricula]['password'] === $password) {
        $_SESSION['loggedin'] = true;
        $_SESSION['matricula'] = $matricula;
        
        // Define o tipo de usuário (padrão 'student')
        $role = $users[$matricula]['role'] ?? 'student';
        $_SESSION['role'] = $role;

        // Redirecionamento lógico
        if ($role === 'teacher') {
            header("Location: teacher_profile.php");
        } else {
            header("Location: student_profile.php");
        }
        exit;
    } else {
        $error_message = "Matrícula ou senha incorretos.";
    }
}
?>