<?php
    session_start();
    // compute base URL for assets (strip trailing slash)
    $base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');

    $error_message = "";
    $success_message = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $matricula = $_POST['matricula'] ?? '';
        $email = $_POST['email'] ?? '';

        $users = json_decode(file_get_contents('users.json'), true) ?? [];
        if (empty($matricula) || empty($email)) {
            $error_message = "Matrícula e e-mail são obrigatórios.";
        } elseif (isset($users[$matricula]) && $users[$matricula]['email'] === $email) {
            header("Location: login.php");
            exit;
        } else {
            $error_message = "Matrícula ou e-mail não encontrados.";
        }
    }
    ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="<?= $base ?>/">
    <title>PIPA - Recuperação de Senha</title>
    <link rel="stylesheet" href="colors.css">
    <link rel="stylesheet" href="styles/login_style.css">
    <link rel="stylesheet" href="styles/navbar.css">
    <link rel="stylesheet" href="styles/footer.css">
    <link rel="icon" type="image/svg+xml" href="assets/icons/kite-origami-paper-svgrepo-com.svg">
</head>

<body>
    <?php include 'components/footer.php'; ?>

    <div class="page-content">
        <img src="assets/images/boy_holding_book.png" alt="menino segurando um livro" class="side-image">

        <form action="" method="POST" class="form-login">
            <div class="container-login">
                <h1>Recuperação de Senha</h1>
                <div class="container-inputs">
                    <?php if ($error_message): ?>
                        <p style="color: red;"><?php echo $error_message; ?></p>
                    <?php endif; ?>
                    <div class="input-text">
                        <label for="matricula">Matrícula</label>
                        <input type="text" name="matricula" id="matricula" placeholder="Matrícula">
                    </div>
                    <div class="input-text">
                        <label for="email">E-mail</label>
                        <input type="email" name="email" id="email" placeholder="E-mail">
                    </div>
                    <p>Já possui uma conta? <a href="./login.php">Faça Login</a></p>
                    <button type="submit" class="button-login">Recuperar</button>
                </div>
            </div>
        </form>
    </div>
    <?php include 'components/footer.php'; ?>
</body>

</html>