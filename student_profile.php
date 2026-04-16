<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

$users = json_decode(file_get_contents('users.json'), true) ?? [];

// Pega a matrícula de quem está LOGADO (para a navbar/saudação)
$matricula_logada = $_SESSION['matricula'];
$nome_logado = $users[$matricula_logada]['name'] ?? 'Usuário';

// Pega a matrícula do DONO da página (via URL ?id=...)
// Se não houver 'id' na URL, assume que o aluno está vendo o próprio perfil
$matricula_perfil = $_GET['id'] ?? $matricula_logada;
$student_name = $users[$matricula_perfil]['name'] ?? 'Aluno';

$matricula_logada = $_SESSION['matricula'];
$matricula_perfil = $_GET['id'] ?? $matricula_logada;

// Verifica se o usuário logado é o dono da página
$is_own_profile = ($matricula_logada === $matricula_perfil);
$student_name = $users[$matricula_perfil]['name'] ?? 'Aluno';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil do Aluno</title>
    <link rel="stylesheet" href="styles/student_profile.css">
    <link rel="stylesheet" href="colors.css">
    <link rel="stylesheet" href="styles/navbar.css">
    <link rel="stylesheet" href="styles/footer.css">
    <link rel="icon" type="image/svg+xml" href="assets/icons/kite-origami-paper-svgrepo-com.svg">
    <title>PIPA</title>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.20/index.global.min.js'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'pt-br',

                // Mantém o calendário compacto (sem espaços vazios)
                height: 'auto',

                // Função que detecta o clique no dia
                dateClick: function(info) {
                    // Pop-up simples com a data e botão de OK (padrão do navegador)
                    alert('Data selecionada: ' + info.dateStr);
                },

                // Personalização opcional dos botões do topo
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth'
                }
            });
            calendar.render();
        });
    </script>
</head>

<body>
    <?php include 'components/navbar.php'; ?>

    <section class="topo">
        <div class="perfil">
            <img src="assets/images/avatar_aluno.jpg" alt="perfil">
        </div>
    </section>

    <section class="container">
        <h1>Bem vindo, AlunoX! <span class="logout"><a href="login.php">Sair</a></span></h1>

        <div class="salas">
            <h2>Suas salas</h2>
            <div class="cards">
                <div class="card">PIPA ID<br><small>Nome Professor</small></div>
                <div class="card">PIPA ID<br><small>Nome Professor</small></div>
                <div class="card">PIPA ID<br><small>Nome Professor</small></div>
            </div>
        </div>

    </section>

    <div id='calendar'></div>
    <!-- <section class="seu_calendario">
        <h2>Seu Calendário</h2>

        <div class="calendario">
            <div class="topo_calendario">
                <span><-</span>
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
                <div class="dia-m">01</div>
                <div class="dia-m">02</div>
                <div class="dia-m">03</div>
                <div class="dia-m">04</div>
            </div>
        </div>
    </section> -->

    <section class="footer">
        <div class="box">
            <strong>Carteirinha PIPA</strong>
            <br><br>
            Aluno<br>
            Semestre<br>
            Curso
        </div>

        <div class="contato">
            <strong>Contato institucional</strong><br><br>
            Email
            <input type="text" placeholder="xxxx@xxx.com">
            Telefone
            <input type="text" placeholder="(27) 99999-9999">
        </div>
    </section>

    <script>
        function togglePopup() {
            var popup = document.getElementById('popup-menu');
            popup.style.display = popup.style.display === 'grid' ? 'none' : 'grid';
        }
    </script>

</body>

</html>