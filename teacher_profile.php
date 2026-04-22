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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>

<body>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <?php include 'components/navbar.php'; ?>

    <section class="topo">
        <div class="perfil">
            <img src="assets/images/avatar_aluno.jpg" alt="perfil">
        </div>
    </section>

    <div class="container my-5">
        <section class="teacher">
            <h2>Página do Professor</h2>
        </section>

        <section class="container">

            <div class="icons">
                <div id="btn-grid"><img src="assets/icons/list-paper-school-svgrepo-com.svg" alt=""></div>
                <div id="btn-doc"><img src="assets/icons/list-paper-school-svgrepo-com.svg" alt=""></div>
            </div>

            <div id="area-documentos" class="area">
                <div class="grid">
                    <div class="box add" id="btn-add">+</div>
                </div>
            </div>

            <div id="area-add" class="area">
                <h3>Adicionar documento</h3>
                <input type="file">
            </div>

            <div id="area-calendar" class="area">
                <div id='calendar'></div>
                <div class="card-wrapper">
                    <strong>Carteirinha PIPA</strong>
                    <div class="box">
                    </div>
                </div>
            </div>

        </section>
    </div>


    <script>
        const areaDocs = document.getElementById("area-documentos");
        const areaAdd = document.getElementById("area-add");
        const areaCalendar = document.getElementById("area-calendar");

        function esconderTudo() {
            areaDocs.classList.remove("ativa");
            areaAdd.classList.remove("ativa");
            areaCalendar.classList.remove("ativa");
        }

        // função toggle das áreas
        function toggleArea(area) {
            const jaAtiva = area.classList.contains("ativa");

            esconderTudo();

            if (!jaAtiva) {
                area.classList.add("ativa");
            }
        }

        // eventos
        document.getElementById("btn-doc").addEventListener("click", () => {
            toggleArea(areaCalendar);
        });

        document.getElementById("btn-grid").addEventListener("click", () => {
            toggleArea(areaDocs);
        });

        // popup
        function togglePopup() {
            var popup = document.getElementById('popup-menu');
            popup.style.display = popup.style.display === 'grid' ? 'none' : 'grid';
        }
    </script>
</body>

</html>