<?php
session_start();
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
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

    
    <section class="content">
        <h1>
            <?php if ($is_own_profile): ?>
                Olá, <?php echo $student_name; ?>
            <?php else: ?>
                <?php echo ($disciplina ?? "Perfil") . " - " . $student_name; ?>
            <?php endif; ?>
            <span class="logout">
                <a href="login.php">Sair</a>
            </span>
        </h1>
        <div class="container">
            <div class="salas">
                <h2>Suas salas</h2>
                <div class="cards">
                    <?php foreach ($teachers as $id_professor => $dados_professor): ?>
                        <a href="classroom.php?id=<?php echo $id_professor; ?>" class="sala-link">
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
        <div class="container">
            <div id='calendar'></div>
        </div>
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