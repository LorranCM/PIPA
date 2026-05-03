<?php
    require 'components/preset.php';
    loggedIn_verification();

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="colors.css">
    <link rel="stylesheet" href="styles/login_style.css">
    <link rel="stylesheet" href="styles/footer.css">
    <link rel="stylesheet" href="styles/navbar.css">
</head>
<body>
    <?php 
        include 'components/navbar.php'; 
        modular_nav();
        
    ?>

    <div class="page-content">
        <img src="assets/images/boy_holding_book.png" alt="menino segurando um livro" class="side-image">

        <form class="form-login" id="form-login">

            <div class="container-login">

                <h1>Login</h1>

                <div class="container-inputs">

                    <div class="input-text">
                        <label for="registration">Matrícula</label>
                        <input type="text" name="registration" id="registration" class="matricula" placeholder="Matrícula" required>
                    </div>
                    <div class="input-text">
                        <label for="password">Senha</label>
                        <input type="password" name="password" id="password" class="password" placeholder="Senha" required>
                    </div>
                   

                    <div class="show-password-container">
                        <input type="checkbox" id="show-password">
                        <label for="show-password">Exibir senha</label>
                    </div>
                    <p><a href="recovery.php">Esqueci minha senha</a></p>
                    <!-- <p>Primeiro acesso? <a href="register.php">Registrar</a></p> -->
                    
                    <button type="submit" class="button-login">Entrar</button>

                </div>

            </div>
        </form>

    </div>

    <?php include 'components/footer.php'; ?>

    <script>
        const passwordField = document.getElementById('password');
        const showPasswordCheckbox = document.getElementById('show-password');

        showPasswordCheckbox.addEventListener('change', function() {
            passwordField.type = this.checked ? 'text' : 'password';
        });
    </script>

    <script src="auth/login_request.js" type="module"></script>

</body>
</html>