<?php echo $this->extend('master');?>

<!-- 
#--------------------------------------------------------------------
#   Estilos
#--------------------------------------------------------------------
-->

<?= $this->section('styles-ref'); ?>

<link rel="stylesheet" href=<?= base_url("assets/css/colors.css") ?>>
<link rel="stylesheet" href=<?= base_url("assets/css/login_style.css") ?>>

<?= $this->endSection(); ?>

<!-- 
#--------------------------------------------------------------------
#   Scripts
#--------------------------------------------------------------------
-->

<?= $this->section('scripts-ref'); ?>

    <script>
        const passwordField = document.getElementById('password');
        const showPasswordCheckbox = document.getElementById('show-password');
    
        showPasswordCheckbox.addEventListener('change', function() {
            passwordField.type = this.checked ? 'text' : 'password';
        });
    </script>

    <script src=<?= base_url("assets/js/auth/login_request.js") ?> type="module"></script>

<?= $this->endSection(); ?>

<!-- 
#--------------------------------------------------------------------
#   Conteúdo
#--------------------------------------------------------------------
-->

<?= $this->section('content'); ?>

    <div class="page-content">
        <img src=<?= base_url("assets/images/boy_holding_book.png") ?> alt="menino segurando um livro" class="side-image">

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
                    
                    <button type="submit" id="button-login" class="button-login">Entrar</button>

                </div>

            </div>
        </form>

    </div>

<?= $this->endSection(); ?>
