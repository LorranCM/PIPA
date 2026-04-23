<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

$users = json_decode(file_get_contents('users.json'), true) ?? [];

$matricula_logada = $_SESSION['matricula'];
$id_professor     = $_GET['id'] ?? $matricula_logada;
$is_own_profile   = ($matricula_logada === $id_professor);

$teacher_name = $users[$id_professor]['name']   ?? 'Professor';
$disciplina   = $users[$id_professor]['materia'] ?? 'Desenvolvimento de Sistemas';
$horario      = $users[$id_professor]['horario'] ?? [];
$agendamentos = $users[$id_professor]['agendamentos'] ?? [];

// Mapeia dias para número (FullCalendar daysOfWeek)
$dow_map = [
    'sunday'=>0,'monday'=>1,'tuesday'=>2,
    'wednesday'=>3,'thursday'=>4,'friday'=>5,'saturday'=>6
];

// Eventos recorrentes: dias disponíveis (verde)
$fc_events = [];
if (!empty($horario['dias'])) {
    foreach ($horario['dias'] as $dia) {
        $fc_events[] = [
            'title'      => 'Disponível',
            'daysOfWeek' => [$dow_map[$dia] ?? 0],
            'startTime'  => $horario['inicio'],
            'endTime'    => $horario['fim'],
            'color'      => '#2E8E5A',
            'startRecur' => date('Y-m-01'),
            'endRecur'   => date('Y-m-d', strtotime('+6 months')),
        ];
    }
}

// Eventos pontuais: agendamentos recebidos (amarelo)
foreach ($agendamentos as $data) {
    $fc_events[] = [
        'title' => 'Agendado',
        'start' => $data,
        'color' => '#f39c12',
        'allDay'=> true,
    ];
}

$fc_events_json = json_encode($fc_events);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página do Professor - <?php echo htmlspecialchars($teacher_name); ?></title>

    <link rel="stylesheet" href="colors.css">
    <link rel="stylesheet" href="styles/teacher_profile.css">
    <link rel="stylesheet" href="styles/footer.css">
    <link rel="stylesheet" href="styles/navbar.css">
    <link rel="icon" type="image/svg+xml" href="assets/icons/kite-origami-paper-svgrepo-com.svg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.20/index.global.min.js"></script>
</head>
<body>

    <?php include 'components/navbar.php'; ?>

    <section class="topo">
        <div class="perfil">
            <img src="assets/images/avatar_aluno.jpg" alt="perfil">
        </div>
    </section>

    <div class="container my-5">

        <!-- Saudação -->
        <section class="teacher">
            <h2>Olá, <?php echo htmlspecialchars($teacher_name); ?>!</h2>
        </section>

        <!-- Salas do professor -->
        <section class="mb-4">
            <h4 class="text-center mb-3">Suas salas</h4>
            <div class="d-flex flex-wrap justify-content-center gap-3">
                <?php foreach ($users as $id => $u):
                    if (($u['role'] ?? '') !== 'teacher' || $id !== $id_professor) continue;
                ?>
                    <a href="classroom.php?id=<?php echo $id; ?>"
                       style="text-decoration:none;">
                        <div style="background:#6f9f89;color:white;padding:20px 30px;border-radius:10px;
                                    min-width:220px;text-align:center;cursor:pointer;
                                    transition:transform .2s;" 
                             onmouseover="this.style.transform='translateY(-4px)'"
                             onmouseout="this.style.transform='translateY(0)'">
                            <strong><?php echo htmlspecialchars($u['materia'] ?? 'Disciplina'); ?></strong><br>
                            <small><?php echo htmlspecialchars($u['sala_fisica'] ?? 'Local não definido'); ?></small>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="container">
            <div class="icons">
                <div id="btn-grid">
                    <img src="assets/icons/list-paper-school-svgrepo-com.svg" alt="Documentos">
                </div>
                <div id="btn-doc">
                    <img src="assets/icons/calendar-days-svgrepo-com.svg" alt="Calendário">
                </div>
            </div>

            <div id="area-documentos" class="area">
                <div class="grid">
                    <div class="box add" id="btn-add">+</div>
                </div>
            </div>

            <div id="area-add" class="area">
                <h3>Adicionar documento</h3>
                <input type="file" class="form-control">
            </div>

            <div id="area-calendar" class="area">
                <!-- Legenda -->
                <div style="display:flex;gap:20px;justify-content:center;margin-bottom:12px;font-size:0.9em;">
                    <span><span style="display:inline-block;width:14px;height:14px;background:#2E8E5A;border-radius:3px;vertical-align:middle;margin-right:5px;"></span>Disponível</span>
                    <span><span style="display:inline-block;width:14px;height:14px;background:#f39c12;border-radius:3px;vertical-align:middle;margin-right:5px;"></span>Agendado</span>
                </div>
                <div id="calendar"></div>
            </div>
        </section>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const areaDocs     = document.getElementById("area-documentos");
        const areaAdd      = document.getElementById("area-add");
        const areaCalendar = document.getElementById("area-calendar");
        const btnGrid      = document.getElementById("btn-grid");
        const btnDoc       = document.getElementById("btn-doc");
        const btnAdd       = document.getElementById("btn-add");

        let calendar;

        document.addEventListener('DOMContentLoaded', function () {
            const calendarEl = document.getElementById('calendar');
            const events = <?php echo $fc_events_json; ?>;

            calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'pt-br',
                height: 'auto',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth'
                },
                events: events,
                eventClick: function(info) {
                    if (info.event.title === 'Agendado') {
                        const data = new Date(info.event.startStr + 'T12:00:00')
                            .toLocaleDateString('pt-BR');
                        alert('Atendimento agendado para: ' + data);
                    }
                }
            });
            calendar.render();
        });

        function resetEstado() {
            areaDocs.classList.remove("ativa");
            areaAdd.classList.remove("ativa");
            areaCalendar.classList.remove("ativa");
            btnGrid.classList.remove("selected");
            btnDoc.classList.remove("selected");
        }

        function alternarVisualizacao(area, botao) {
            const jaAtiva = area.classList.contains("ativa");
            resetEstado();
            if (!jaAtiva) {
                area.classList.add("ativa");
                if (botao) botao.classList.add("selected");
                if (area === areaCalendar) {
                    setTimeout(() => {
                        if (calendar) { calendar.render(); calendar.updateSize(); }
                    }, 100);
                }
            }
        }

        btnDoc.addEventListener("click",  () => alternarVisualizacao(areaCalendar, btnDoc));
        btnGrid.addEventListener("click", () => alternarVisualizacao(areaDocs, btnGrid));
        btnAdd.addEventListener("click",  () => alternarVisualizacao(areaAdd, null));
    </script>
</body>
</html>