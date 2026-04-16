<?php include 'login_controller.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PIPA - Login</title>
    <link rel="stylesheet" href="colors.css">
    <link rel="stylesheet" href="styles/login_style.css">
    <link rel="stylesheet" href="styles/footer.css">
    <link rel="stylesheet" href="styles/navbar.css">
</head>
<body>
    <?php include 'components/navbar.php'; ?>

    <div class="page-content">
        <img src="assets/images/boy_holding_book.png" alt="menino segurando um livro" class="side-image">

        <form action="" method="POST" class="form-login" id="form-login">
            <div class="container-login">
                <h1>Login</h1>
                <div class="container-inputs">

                    <?php if (!empty($error_message)): ?>
                        <div class="error-box"><?= $error_message ?></div>
                    <?php endif; ?>

                    <div class="input-text">
                        <label for="matricula">Matrícula</label>
                        <input type="text" name="matricula" id="matricula" class="matricula" placeholder="Matrícula" required>
                    </div>
                    <div class="input-text">
                        <label for="password">Senha</label>
                        <input type="password" name="password" id="password" class="password" placeholder="Senha" required>
                    </div>
                   

                    <div class="show-password-container">
                        <input type="checkbox" id="show-password">
                        <label for="show-password">Mostrar senha</label>
                    </div>
                    <p><a href="recovery.php">Esqueci minha senha</a></p>
                    <p>Primeiro acesso? <a href="register.php">Registrar</a></p>
                    
                    <button type="submit" class="button-login">Entrar</button>
                </div>
            </div>
        </form>
    </div>

    <?php include 'components/footer.php'; ?>
    <script>
    // Seleciona os elementos
    const passwordField = document.getElementById('password');
    const showPasswordCheckbox = document.getElementById('show-password');

    // Escuta o evento de clique na checkbox
    showPasswordCheckbox.addEventListener('change', function() {
        // Se estiver marcado, muda para 'text', se não, volta para 'password'
        passwordField.type = this.checked ? 'text' : 'password';
    });
</script>
</body>
</html>