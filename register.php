<?php
session_start();
$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');

$error_message = "";
$success_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $matricula = $_POST['matricula'] ?? '';
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $email_confirmation = $_POST['email_confirmation'] ?? '';
    $password = $_POST['password'] ?? '';
    $password_confirmation = $_POST['password_confirmation'] ?? '';
    $role = $_POST['role'] ?? 'student'; // <-- NOVO: tipo de usuário (padrão aluno)

    if ($email !== $email_confirmation) {
        $error_message = "Os e-mails não coincidem.";
    } elseif ($password !== $password_confirmation) {
        $error_message = "As senhas não coincidem.";
    } elseif (empty($matricula) || empty($name) || empty($email) || empty($password)) {
        $error_message = "Todos os campos são obrigatórios.";
    } elseif (!in_array($role, ['student', 'teacher'])) { // <-- NOVO: validação do tipo
        $error_message = "Tipo de usuário inválido.";
    } else {
        $usersFile = __DIR__ . '/users.json';

        if (file_exists($usersFile)) {
            $json = file_get_contents($usersFile);
            $users = json_decode($json, true);
            if (!is_array($users)) {
                $users = [];
            }
        } else {
            $users = [];
        }

        if (isset($users[$matricula])) {
            $error_message = "Matrícula já cadastrada.";
        } else {
            $users[$matricula] = [
                'name' => $name,
                'email' => $email,
                'password' => $password,
                'role' => $role,
            ];

            $jsonData = json_encode($users, JSON_PRETTY_PRINT);
            if (file_put_contents($usersFile, $jsonData) === false) {
                $error_message = "Erro ao salvar os dados. Verifique as permissões da pasta.";
            } else {
                header("Location: login.php");
                exit;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="<?= $base ?>/">
    <title>PIPA - Cadastro</title>
    <link rel="stylesheet" href="colors.css">
    <link rel="stylesheet" href="styles/login_style.css">
    <link rel="stylesheet" href="styles/navbar.css">
    <link rel="stylesheet" href="styles/footer.css">
    <link rel="icon" type="image/svg+xml" href="assets/icons/kite-origami-paper-svgrepo-com.svg">
</head>
<body>
    <?php include 'components/navbar.php'; ?>

    <div class="page-content">
        <img src="assets/images/boy_holding_book.png" alt="menino segurando um livro" class="side-image">

        <form action="" method="POST" class="form-login">
            <div class="container-login">
                <h1>Cadastro</h1>
                <div class="container-inputs">
                    <?php if ($error_message): ?>
                        <p style="color: red;"><?php echo $error_message; ?></p>
                    <?php endif; ?>
                    <div class="input-text">
                        <label for="matricula">Matrícula</label>
                        <input type="text" name="matricula" id="matricula" placeholder="Matrícula">
                    </div>
                    <div class="input-text">
                        <label for="name">Nome Completo</label>
                        <input type="text" name="name" id="name" placeholder="Nome Completo">
                    </div>
                    <div class="input-text">
                        <label for="email">E-mail</label>
                        <input type="email" name="email" id="email" placeholder="E-mail">
                    </div>
                    <div class="input-text">
                        <label for="email_confirmation">Confirmar E-mail</label>
                        <input type="email" name="email_confirmation" id="email_confirmation" placeholder="Confirmar E-mail">
                    </div>
                    <div class="input-text">
                        <label for="password">Senha</label>
                        <input type="password" name="password" id="password" placeholder="Senha">
                    </div>
                    <div class="input-text">
                        <label for="password_confirmation">Confirmar Senha</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Confirmar a senha">
                    </div>

                    <!-- NOVO: Campo de seleção de tipo de usuário -->
                    <div class="input-text" style="margin-top: 10px;">
                        <label>Tipo de usuário:</label>
                        <div style="display: flex; gap: 15px; margin-top: 5px;">
                            <label>
                                <input type="radio" name="role" value="student" checked> Aluno
                            </label>
                            <label>
                                <input type="radio" name="role" value="teacher"> Professor
                            </label>
                        </div>
                    </div>

                    <p>Já possui uma conta? <a href="./login.php">Faça Login</a></p>
                    <button type="submit" class="button-login">Cadastrar</button>
                </div>
            </div>
        </form>
    </div>

<link rel="stylesheet" href="styles/footer.css">
<?php include 'components/footer.php'; ?>
</body>
</html>