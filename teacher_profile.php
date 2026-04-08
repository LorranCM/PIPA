<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

$users = json_decode(file_get_contents('users.json'), true) ?? [];

// Matrícula de quem está acessando
$matricula_logada = $_SESSION['matricula'];

// Matrícula do professor DONO da sala sendo visitada
// Se não houver ID na URL, mostra o perfil do próprio professor logado
$id_professor = $_GET['id'] ?? $matricula_logada;
$teacher_name = $users[$id_professor]['name'] ?? 'Professor';

$matricula_logada = $_SESSION['matricula'];
$id_professor = $_GET['id'] ?? $matricula_logada;

$is_own_profile = ($matricula_logada === $id_professor);
$teacher_name = $users[$id_professor]['name'] ?? 'Professor';

// Simulando a disciplina (você pode adicionar "materia" no users.json depois)
$disciplina = $users[$id_professor]['materia'] ?? 'Desenvolvimento de Sistemas';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página do professor</title>
    <link rel="stylesheet" href="colors.css">
    <link rel="stylesheet" href="styles/teacher_profile.css">
    <link rel="stylesheet" href="styles/footer.css">
    <link rel="stylesheet" href="styles/navbar.css">
    <link rel="icon" type="image/svg+xml" href="assets/icons/kite-origami-paper-svgrepo-com.svg">
</head>

<body>

    <nav id="navbar">
        <ul>
            <li id="navbar-PIPA-clickable">
                <a href="">
                    <img src="assets/icons/kite-origami-paper-svgrepo-com.svg" alt="icone do pipa">PIPA
                </a>
            </li>
            <li id="navbar-3-bars" onclick="togglePopup()">
                <img src="assets/icons/bars-svgrepo-com.svg" alt="3-bars">
            </li>
        </ul>
        <div id="popup-menu" class="popup-menu">
            <ul>
                <li><a href="teacher_profile.php">Início</a></li>
                <li><a href="teacher_information.php">Suas configurações</a></li>
                <li><a href="login.php">Sair</a></li>
            </ul>
        </div>
    </nav>

    <section id="banner" class="banner">
    </section>

    <section class="perfil_aluno">

        <img src="assets/images/avatar_aluno.jpg" class="avatar" alt="Foto de perfil">
            <?php if ($is_own_profile): ?>
        <h1>Olá, <?php echo $teacher_name; ?></h1>
        <?php else: ?>
        <h1><?php echo $disciplina . " - " . $teacher_name; ?></h1>
        <?php endif; ?>
        <a href="login.php" class="sair">Sair</a>

    </section>

    <section class="seus_prof">

        <h2>Suas Turmas</h2>

        <a href="student_profile.php?id=20232tiimi0209">
            <div class="pagina-prof">
                <img src="assets/images/one.jpg">
                <div class="prof-info">
                    <strong>Álvaro Dalmaso</strong> <span>Módulo: xxx</span>
                </div>
            </div>
        </a>

    </section>

    

    
<!-- OI LOPES SOU EU ALVARO VOU MEXER NUMAS COISAS LÁ NO SECTION DE CIMA -->
    <script>
        function togglePopup() {
            var popup = document.getElementById('popup-menu');
            popup.style.display = popup.style.display === 'grid' ? 'none' : 'grid';
        }
    </script>
    <?php include 'footer.php'; ?>
</body>

</html>