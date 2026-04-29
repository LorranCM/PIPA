<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

        <form action="" method="POST" class="form-login" id="form-login">
            <div class="container-login">
                <h1>Cadastro</h1>
                <div class="container-inputs">
                    <div class="input-text">
                        <label for="matricula">Matrícula</label>
                        <input type="text" name="matricula" id="registration" placeholder="Matrícula">
                    </div>
                    <div class="input-text">
                        <label for="name">Nome</label>
                        <input type="text" name="name" id="name" placeholder="Nome">
                    </div>
                    <div class="input-text">
                        <label for="lastname">Sobrenome</label>
                        <input type="text" name="lastname" id="lastname" placeholder="Sobrenome">
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
                    <div class="show-password-container">
                        <input type="checkbox" id="show-all-passwords">
                        <label for="show-all-passwords">Mostrar senhas</label>
                    </div>

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

<?php include 'components/footer.php'; ?>

<script>
    const checkbox = document.getElementById('show-all-passwords');
    const passField = document.getElementById('password');
    const confField = document.getElementById('password_confirmation');

    checkbox.addEventListener('change', function() {
        const type = this.checked ? 'text' : 'password';
        passField.type = type;
        confField.type = type;
    });
</script>

<script src="auth/scripts/register.js" type="module"></script>

</body>
</html>