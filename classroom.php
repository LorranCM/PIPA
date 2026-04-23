<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

$users = json_decode(file_get_contents('users.json'), true) ?? [];

$id_professor = $_GET['id'] ?? '';
$teacher_data = $users[$id_professor] ?? null;

if (!$teacher_data || $teacher_data['role'] !== 'teacher') {
    echo "Sala não encontrada.";
    exit;
}

$matricula_logada = $_SESSION['matricula'];
$user_role = $_SESSION['role'] ?? 'student';
$is_owner = ($user_role === 'teacher' && $matricula_logada === $id_professor);

// --- LÓGICA DE ATUALIZAÇÃO ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_room']) && $is_owner) {
    $users[$id_professor]['sala_fisica'] = $_POST['sala_fisica'];

    // Recebe os dias como array e horários
    $users[$id_professor]['horario'] = [
        'dias'   => $_POST['dias'] ?? [],
        'inicio' => $_POST['inicio'],
        'fim'    => $_POST['fim'],
    ];

    file_put_contents('users.json', json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    $teacher_data = $users[$id_professor];
}

// --- LÓGICA DE AGENDAMENTO (aluno agenda um horário) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agendar_data']) && $user_role === 'student') {
    $data_agendada = $_POST['agendar_data']; // formato: "2025-06-09"

    // Garante que o array existe
    if (!isset($users[$id_professor]['agendamentos'])) {
        $users[$id_professor]['agendamentos'] = [];
    }

    // Evita duplicatas
    if (!in_array($data_agendada, $users[$id_professor]['agendamentos'])) {
        $users[$id_professor]['agendamentos'][] = $data_agendada;
        file_put_contents('users.json', json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        $teacher_data = $users[$id_professor];
        $agendamento_msg = "Atendimento agendado para " . date('d/m/Y', strtotime($data_agendada)) . "!";
    } else {
        $agendamento_msg = "Você já tem um agendamento nesta data.";
    }
}

// Pega agendamentos para passar ao calendário
$agendamentos = $teacher_data['agendamentos'] ?? [];
$agendamentos_json = json_encode($agendamentos);

$disciplina  = $teacher_data['materia']     ?? 'Disciplina';
$sala_fisica = $teacher_data['sala_fisica'] ?? 'A definir';
$horario     = $teacher_data['horario']     ?? [];

// Monta texto legível do horário
$dias_pt = [
    'sunday'    => 'Domingo',
    'monday'    => 'Segunda-feira',
    'tuesday'   => 'Terça-feira',
    'wednesday' => 'Quarta-feira',
    'thursday'  => 'Quinta-feira',
    'friday'    => 'Sexta-feira',
    'saturday'  => 'Sábado',
];

$dias_legivel = '';
if (!empty($horario['dias'])) {
    $nomes = array_map(fn($d) => $dias_pt[$d] ?? $d, $horario['dias']);
    $dias_legivel = implode(', ', $nomes);
}
$horario_legivel = $dias_legivel
    ? "$dias_legivel — {$horario['inicio']} às {$horario['fim']}"
    : 'Não definido';

// Prepara dados para o FullCalendar (eventos recorrentes via daysOfWeek)
// Mapeia nome do dia para número (0=domingo … 6=sábado)
$dow_map = [
    'sunday'=>0,'monday'=>1,'tuesday'=>2,
    'wednesday'=>3,'thursday'=>4,'friday'=>5,'saturday'=>6
];

$fc_events = [];
if (!empty($horario['dias'])) {
    foreach ($horario['dias'] as $dia) {
        $fc_events[] = [
            'title'      => 'Disponível',
            'daysOfWeek' => [$dow_map[$dia] ?? 0],
            'startTime'  => $horario['inicio'],
            'endTime'    => $horario['fim'],
            'color'      => '#2E8E5A',
            'startRecur' => date('Y-m-01'),          // começa no 1º dia do mês atual
            'endRecur'   => date('Y-m-d', strtotime('+6 months')), // 6 meses à frente
        ];
    }
}

$fc_events_json = json_encode($fc_events);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Sala — <?php echo htmlspecialchars($disciplina); ?></title>
    <link rel="stylesheet" href="colors.css">
    <link rel="stylesheet" href="styles/navbar.css">
    <link rel="stylesheet" href="styles/student_profile.css">
    <link rel="stylesheet" href="styles/footer.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.20/index.global.min.js"></script>
    <style>
        .classroom-container { padding: 40px 50px; font-family: 'Encode Sans', sans-serif; }
        .room-card { background: white; border: 1px solid #ddd; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); margin-bottom: 30px; }
        .room-header { border-bottom: 2px solid #2E8E5A; margin-bottom: 20px; padding-bottom: 10px; }
        .info-group { margin-bottom: 15px; }
        .info-group label { font-weight: bold; color: #555; display: block; }
        .info-group span { font-size: 1.1em; color: #222; }
        .btn { padding: 12px 25px; border-radius: 5px; border: none; cursor: pointer; font-weight: bold; text-decoration: none; display: inline-block; }
        .btn-schedule { background-color: #2E8E5A; color: white; }
        .btn-edit { background-color: #f39c12; color: white; }
        .btn:hover { opacity: 0.9; }

        #calendar { background: white; padding: 20px; border: 1px solid #ccc; border-radius: 6px; font-size: 0.85em; }
        .fc .fc-toolbar-title { font-size: 1.1em !important; }
        .fc .fc-button { padding: 0.3em 0.5em !important; font-size: 0.85em !important; }
        .fc .fc-daygrid-day { cursor: default; }

        /* Modal */
        #modalEdit { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); }
        .modal-content { background: white; margin: 8% auto; padding: 25px; border-radius: 8px; width: 450px; }
        .modal-content input[type=text] { width: 100%; padding: 10px; margin: 8px 0 16px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .dias-check { display: flex; flex-wrap: wrap; gap: 10px; margin: 8px 0 16px; }
        .dias-check label { display: flex; align-items: center; gap: 5px; cursor: pointer; }
        .horario-row { display: flex; gap: 10px; }
        .horario-row div { flex: 1; }
        .horario-row label { font-weight: bold; color: #555; display: block; margin-bottom: 4px; }
        .modal-footer { text-align: right; margin-top: 20px; }
        .btn-cancel { background: #ccc; color: #333; margin-right: 10px; }
    </style>
</head>
<body>
    <?php include 'components/navbar.php'; ?>

    <section class="banner"></section>

<section class="classroom-container">
    <div class="room-card">
        <div class="room-header">
            <h1><?php echo htmlspecialchars($disciplina); ?></h1>
            <p>Professor(a): <strong><?php echo htmlspecialchars($teacher_data['name']); ?></strong></p>
        </div>

        <div class="info-group">
            <label>Localização:</label>
            <span><?php echo htmlspecialchars($sala_fisica); ?></span>
        </div>

        <div class="info-group">
            <label>Horário de Atendimento:</label>
            <span><?php echo htmlspecialchars($horario_legivel); ?></span>
        </div>

        <?php if (!empty($agendamento_msg)): ?>
            <div style="margin-top:15px; padding:12px; background:#e8f5e9; border-left:4px solid #2E8E5A; border-radius:4px; color:#1b5e20;">
                <?php echo htmlspecialchars($agendamento_msg); ?>
            </div>
        <?php endif; ?>

        <div class="action-area" style="margin-top:20px;">
            <?php if ($is_owner): ?>
                <button class="btn btn-edit" onclick="openModal()">✏️ Alterar Horário</button>
            <?php endif; ?>
        </div>
    </div>

    <!-- Calendário: aluno clica num dia disponível para agendar -->
    <p style="text-align:center; color:#555; margin-bottom:8px;">
        <?php if ($user_role === 'student'): ?>
            Clique em um dia <strong style="color:#2E8E5A;">disponível</strong> para agendar seu atendimento.
        <?php endif; ?>
    </p>
    <div id="calendar"></div>

    <!-- Form oculto para submeter o agendamento -->
    <?php if ($user_role === 'student'): ?>
    <form id="form-agendar" method="POST" style="display:none;">
        <input type="hidden" name="agendar_data" id="input-data-agendamento">
    </form>
    <?php endif; ?>
</section>

    <!-- Modal de edição (só aparece para o professor dono) -->
    <?php if ($is_owner): ?>
    <div id="modalEdit">
        <div class="modal-content">
            <h3>Editar Informações da Sala</h3>
            <form method="POST">
                <label style="font-weight:bold;color:#555;">Nova Localização:</label>
                <input type="text" name="sala_fisica" value="<?php echo htmlspecialchars($sala_fisica); ?>" required>

                <label style="font-weight:bold;color:#555;">Dias de Atendimento:</label>
                <div class="dias-check">
                    <?php
                    $dias_opcoes = [
                        'monday'=>'Segunda','tuesday'=>'Terça','wednesday'=>'Quarta',
                        'thursday'=>'Quinta','friday'=>'Sexta','saturday'=>'Sábado','sunday'=>'Domingo'
                    ];
                    $dias_selecionados = $horario['dias'] ?? [];
                    foreach ($dias_opcoes as $val => $label):
                        $checked = in_array($val, $dias_selecionados) ? 'checked' : '';
                    ?>
                        <label>
                            <input type="checkbox" name="dias[]" value="<?php echo $val; ?>" <?php echo $checked; ?>>
                            <?php echo $label; ?>
                        </label>
                    <?php endforeach; ?>
                </div>

                <div class="horario-row">
                    <div>
                        <label>Início:</label>
                        <input type="text" name="inicio" value="<?php echo htmlspecialchars($horario['inicio'] ?? ''); ?>" placeholder="08:00" required>
                    </div>
                    <div>
                        <label>Fim:</label>
                        <input type="text" name="fim" value="<?php echo htmlspecialchars($horario['fim'] ?? ''); ?>" placeholder="09:40" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-cancel" onclick="closeModal()">Cancelar</button>
                    <button type="submit" name="update_room" class="btn btn-schedule">Salvar</button>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const calendarEl = document.getElementById('calendar');
        const events     = <?php echo $fc_events_json; ?>;
        const agendados  = <?php echo $agendamentos_json; ?>;
        const userRole   = '<?php echo $user_role; ?>';

        // Adiciona os agendamentos já feitos como eventos amarelos (info)
        agendados.forEach(function(data) {
            events.push({
                title: 'Agendado',
                start: data,
                color: '#f39c12',
                allDay: true
            });
        });

        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'pt-br',
            height: 'auto',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth'
            },
            events: events,

            // Aluno clica num dia disponível → confirma e agenda
            dateClick: function(info) {
                if (userRole !== 'student') return;

                // Verifica se o dia clicado tem evento "Disponível"
                const eventosDoDia = calendar.getEvents().filter(function(ev) {
                    const start = ev.startStr?.substring(0, 10);
                    const end   = ev.endStr?.substring(0, 10);
                    // Para eventos recorrentes, verifica pelo daysOfWeek
                    if (ev.extendedProps?.daysOfWeek) return false; // tratado abaixo
                    return start === info.dateStr || (start < info.dateStr && end > info.dateStr);
                });

                // Verifica pelos eventos recorrentes (disponíveis)
                const dow = new Date(info.dateStr + 'T12:00:00').getDay(); // 0=dom
                const disponivelHoje = events.some(function(ev) {
                    return ev.daysOfWeek && ev.daysOfWeek.includes(dow)
                        && info.dateStr >= (ev.startRecur ?? '')
                        && info.dateStr <= (ev.endRecur ?? '9999');
                });

                if (!disponivelHoje) {
                    alert('Este dia não está disponível para agendamento.');
                    return;
                }

                // Verifica se já está agendado
                if (agendados.includes(info.dateStr)) {
                    alert('Você já agendou um atendimento nesta data.');
                    return;
                }

                const dataFormatada = new Date(info.dateStr + 'T12:00:00')
                    .toLocaleDateString('pt-BR');

                if (confirm('Agendar atendimento para ' + dataFormatada + '?')) {
                    document.getElementById('input-data-agendamento').value = info.dateStr;
                    document.getElementById('form-agendar').submit();
                }
            }
        });

        calendar.render();
    });

    function openModal()  { document.getElementById('modalEdit').style.display = 'block'; }
    function closeModal() { document.getElementById('modalEdit').style.display = 'none'; }
    window.onclick = function(e) {
        if (e.target === document.getElementById('modalEdit')) closeModal();
    }
</script>
</body>
</html>