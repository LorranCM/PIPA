<?php
    // start session
    session_start();

    // Variável para mensagem de erro
    $error_message = "";

    // Processamento do Login
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $matricula = $_POST['matricula'] ?? '';
        $password = $_POST['password'] ?? '';

        $users = json_decode(file_get_contents('users.json'), true) ?? [];
        // Validação das credenciais
        if (isset($users[$matricula]) && $users[$matricula]['password'] === $password) {
            $_SESSION['loggedin'] = true;
            $_SESSION['matricula'] = $matricula;
            header("Location: student_profile.html");
            exit;
        } else {
            $error_message = "Matrícula ou senha incorretos.";
        }
    }

    // compute base URL for assets (strip trailing slash)
    $base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="<?= $base ?>/">
    <title>PIPA</title>
    <link rel="stylesheet" href="styles/navbar.css">
    <link rel="stylesheet" href="styles/login_style.css">
    <link rel="stylesheet" href="styles/footer.css">
    <link rel="icon" type="image/svg+xml" href="assets/icons/kite-origami-paper-svgrepo-com.svg">
</head>

<body>
    <nav id="navbar">
        <ul>
            <li id="navbar-PIPA-clickable"><a href="index.php"><img src="assets/icons/kite-origami-paper-svgrepo-com.svg"
                        alt="icone do pipa">PIPA</a></li>
        </ul>
    </nav>
    <div class="page-content">
        <img src="assets/images/boy_holding_book.png" alt="menino segurando um livro" class="side-image">

        <form action="" method="POST" class="form-login" id="form-login">
            <div class="container-login">
                <h1>Portal de Informação Para Alunos</h1>
                <div class="container-inputs">
                    <h3>Login</h3>

                    <?php if (!empty($error_message)): ?>
                        <div class="error-box"><?= $error_message ?></div>
                    <?php endif; ?>

                    <div class="input-text">
                        <label for="matricula">Matrícula</label>
                        <input type="text" name="matricula" class="matricula" id="matricula" placeholder="Matrícula" required>
                    </div>
                    <div class="input-text">
                        <label for="password">Senha</label>
                        <input type="password" name="password" class="password" id="password" placeholder="Senha" required>
                    </div>
                    <div>
                        <input type="checkbox" name="remember" class="remember" id="remember"><label for="remember">Lembre de mim</label>
                    </div>
                    <p>
                        <a href="recovery.php">Esqueci minha senha</a>
                    </p>
                    <p>Primeiro acesso? <a href="register.php">Registrar</a></p>
                    
                    <button type="submit" class="button-login">Acessar</button>
                </div>
            </div>
        </form>
    </div>

    <footer>
        <section id="footer-section1">
            <figure>
                <svg fill="#FFFFFF" width="800px" height="800px" viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg"><path d="M33.66,4.6c-.96-.8-2.36-.8-3.32,0L9.86,21.63c-.96,.8-1.19,2.2-.54,3.27L30.45,59.14c.25,.41,.66,.7,1.13,.81,.14,.03,.28,.05,.42,.05,.33,0,.66-.09,.95-.27,.24-.15,.45-.35,.59-.59L54.68,24.9c.66-1.06,.43-2.47-.53-3.27L33.66,4.6Zm-.66,2.05l18.76,15.59h-18.76V6.65Zm-2,0v15.59H12.24L31,6.65Zm-.79,17.59l-9.47,15.34-9.47-15.34H30.21Zm-8.29,17.25l9.09-14.72v29.44l-9.09-14.72Zm11.09,14.76V24.25h19.73l-19.73,32.01Z"/></svg>
                <h2><strong>PIPA</strong><br>Portal de Informação para alunos</h2>
            </figure>
            <div id="contacts-info">
                <div>
                    <h2>Fale conosco</h2>
                    <p><strong>Email:</strong> pipaifes@gmail.com</p>
                    <p><strong>Telefone:</strong> (11) 1234-5678</p>
                </div>
                <div>
                    <h2>Nos acompanhe</h2>
                    <ul id="social-media-icon-list">
                        <li>
                            <a href="https://www.facebook.com/pipaifes" target="_blank">
                                <svg width="24px" height="24px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M20 1C21.6569 1 23 2.34315 23 4V20C23 21.6569 21.6569 23 20 23H4C2.34315 23 1 21.6569 1 20V4C1 2.34315 2.34315 1 4 1H20ZM20 3C20.5523 3 21 3.44772 21 4V20C21 20.5523 20.5523 21 20 21H15V13.9999H17.0762C17.5066 13.9999 17.8887 13.7245 18.0249 13.3161L18.4679 11.9871C18.6298 11.5014 18.2683 10.9999 17.7564 10.9999H15V8.99992C15 8.49992 15.5 7.99992 16 7.99992H18C18.5523 7.99992 19 7.5522 19 6.99992V6.31393C19 5.99091 18.7937 5.7013 18.4813 5.61887C17.1705 5.27295 16 5.27295 16 5.27295C13.5 5.27295 12 6.99992 12 8.49992V10.9999H10C9.44772 10.9999 9 11.4476 9 11.9999V12.9999C9 13.5522 9.44771 13.9999 10 13.9999H12V21H4C3.44772 21 3 20.5523 3 20V4C3 3.44772 3.44772 3 4 3H20Z" fill="#FFFFFF"/>
                                </svg>
                            </a>
                        </li>
                        </ul>
                </div>
            </div>
        </section>
        <section id="footer-section2">
            <ul>
                <li><a href="">Institutos Federais parceiros</a></li>
                <li><a href="">Método de trabalho</a></li>
                <li><a href="">Mais funcionalidades</a></li>
                <li><a href="">Nossa história</a></li>
            </ul>
            <div>
                <p>Av. dos Sabiás, 330 - Morada de Laranjeiras, Serra - ES, 29166-630</p>
                <p>&copy 2025 - PIPA</p>
            </div>
        </section>
    </footer>

</body>

</html>