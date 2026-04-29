<?php
session_start();
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

$users = json_decode(file_get_contents('users.json'), true) ?? [];
// Pega a matrícula de quem está LOGADO
$matricula_logada = $_SESSION['matricula'];

// Pega a matrícula do DONO da página (via URL) ou assume que é o próprio logado
$matricula_perfil = $_GET['id'] ?? $matricula_logada;

// Verifica se o usuário logado é o dono da página
$is_own_profile = ($matricula_logada === $matricula_perfil);
$student_name = $users[$matricula_perfil]['name'] ?? 'Aluno';

// --- NOVA LÓGICA: FILTRAR PROFESSORES PARA AS SALAS ---
$teachers = array_filter($users, function ($user) {
    return isset($user['role']) && $user['role'] === 'teacher';
});

// NOVO: buscar agendamentos do aluno
$meus_agendamentos = [];

foreach ($users as $id => $user) {
    if (($user['role'] ?? '') === 'teacher' && !empty($user['agendamentos'])) {
        foreach ($user['agendamentos'] as $ag) {
            if (
                isset($ag['student']) &&
                $ag['student'] === $matricula_logada
            ) {
                $meus_agendamentos[] = [
                    'date' => $ag['date'],
                    'status' => $ag['status'],
                    'professor' => $user['name'] ?? 'Professor'
                ];
            }
        }
    }
}

$meus_agendamentos_json = json_encode($meus_agendamentos);

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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <title>PIPA</title>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.20/index.global.min.js'></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var meusEventos = <?php echo $meus_agendamentos_json; ?>;

        var eventosFormatados = meusEventos.map(function(ag) {
            let cor = ag.status === 'confirmed' ? '#3498db' : '#f39c12';
            let titulo = ag.status === 'confirmed' ? 'Confirmado' : 'Pendente';

            return {
                title: titulo + ' - ' + ag.professor,
                start: ag.date,
                color: cor,
                allDay: true
            };
        });

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'pt-br',
            height: 'auto',

            events: eventosFormatados,

            dateClick: function(info) {
                alert('Data selecionada: ' + info.dateStr);
            },

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
    <?php 
        include 'components/navbar.php'; 
        modular_nav();
    ?>

    <section class="topo">
        <div class="perfil">
            <img src="assets/images/avatar_aluno.jpg" alt="perfil">
        </div>
    </section>

    <div class="container">
        <section class="content">
            <h1>
                <?php if ($is_own_profile): ?>
                Olá, <?php echo $student_name; ?>
                <?php else: ?>
                <?php echo ($disciplina ?? "Perfil") . " - " . $student_name; ?>
                <?php endif; ?>
                <span class="logout">
                    <a href="logout.php">Sair</a>
                </span>
            </h1>
            <h2 class="class">Suas salas</h2>
            <div class="salas">
                <div class="cards">
                    <?php foreach ($teachers as $id_professor => $dados_professor): ?>
                    <a href="teacher_profile.php?id=<?php echo $id_professor; ?>" class="sala-link">
                        <div class="card">
                            <?php echo $dados_professor['materia'] ?? 'Disciplina'; ?><br>
                            <small><?php echo $dados_professor['name']; ?></small>
                        </div>
                    </a>
                    <?php endforeach; ?>
                    <?php if (empty($teachers)): ?>
                    <p>Nenhuma sala disponível no momento.</p>
                    <?php endif; ?>
                </div>
            </div>
        </section>
        <section>
            <div id='calendar'></div>
        </section>
        <section class="carteirinha">
            <div class="card-wrapper">
                <strong>Carteirinha PIPA</strong>
                <div class="box">
                    Aluno<br>
                    Semestre<br>
                    Curso
                </div>
            </div>
            <div class="contato">
                <strong>Contato institucional</strong><br><br>
                Email
                <div class="box1">
                    <input type="text" placeholder="xxxx@xxx.com" disabled>
                    Telefone
                    <input type="text" placeholder="(27) 99999-9999">
                </div>
            </div>
        </section>
    </div>


    <script>
    function togglePopup() {
        var popup = document.getElementById('popup-menu');
        popup.style.display = popup.style.display === 'grid' ? 'none' : 'grid';
    }
    </script>

</body>

</html>