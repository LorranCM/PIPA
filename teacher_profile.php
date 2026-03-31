<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

// Recupera os dados do professor logado
$matricula = $_SESSION['matricula'];
$users = json_decode(file_get_contents('users.json'), true) ?? [];
$teacher_name = $users[$matricula]['name'] ?? 'Professor';
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
            <li id="navbar-3-bars">
                <img src="assets/icons/bars-svgrepo-com.svg" alt="3-bars">
            </li>
        </ul>
    </nav>

    <section class="banner"></section>

    <section class="perfil_professor">
        <img src="assets/images/avatar_aluno.jpg" class="avatar" alt="Foto de perfil">
        <!-- Nome dinâmico do professor -->
        <h1 class="nome"><?php echo htmlspecialchars($teacher_name); ?></h1>
    </section>

    <section class="calendario_do_professor">
        <h2>Calendário</h2>

        <div class="calendario">
            <div class="topo_calendario">
                <span>-</span>
                <strong>Março 2026</strong>
                <span>-></span>
            </div>

            <div class="semana">
                <div>Dom</div>
                <div>Seg</div>
                <div>Ter</div>
                <div>Qua</div>
                <div>Qui</div>
                <div>Sex</div>
                <div>Sáb</div>
            </div>

            <div class="dias">
                <div class="dia">1</div>
                <div class="dia">2</div>
                <div class="dia">3</div>
                <div class="dia">4</div>
                <div class="dia">5</div>
                <div class="dia">6</div>
                <div class="dia">7</div>

                <div class="dia">8</div>
                <div class="dia">9</div>
                <div class="dia">10</div>
                <div class="dia">11</div>
                <div class="dia">12</div>
                <div class="dia">13</div>
                <div class="dia">14</div>

                <div class="dia">15</div>
                <div class="dia">16</div>
                <div class="dia">17</div>
                <div class="dia">18</div>
                <div class="dia">19</div>
                <div class="dia">20</div>
                <div class="dia">21</div>

                <div class="dia">22</div>
                <div class="dia">23</div>
                <div class="dia">24</div>
                <div class="dia">25</div>
                <div class="dia">26</div>
                <div class="dia">27</div>
                <div class="dia">28</div>

                <div class="dia">29</div>
                <div class="dia">30</div>
                <div class="dia">31</div>
            </div>
        </div>
    </section>

    <?php include 'footer.php'; ?>
</body>
</html>