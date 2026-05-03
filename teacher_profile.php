<?php
require 'components/preset.php';
loggedOut_verification();

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

$users = json_decode(file_get_contents('users.json'), true) ?? [];

$matricula_logada = $_SESSION['matricula'];
$user_role = $_SESSION['role'] ?? 'student';

// ID do professor da página (via GET). Se não fornecido e for teacher, assume o próprio logado
$id_professor = $_GET['id'] ?? null;
if (!$id_professor && $user_role === 'teacher') {
    $id_professor = $matricula_logada;
} elseif (!$id_professor) {
    die("Professor não especificado.");
}

$teacher_data = $users[$id_professor] ?? null;
if (!$teacher_data || $teacher_data['role'] !== 'teacher') {
    die("Professor não encontrado.");
}

$is_owner = ($user_role === 'teacher' && $matricula_logada === $id_professor);
$teacher_name = $teacher_data['name'];
$disciplina  = $teacher_data['materia'] ?? 'Disciplina';
$sala_fisica = $teacher_data['sala_fisica'] ?? 'A definir';
$horario     = $teacher_data['horario'] ?? [];

// ========== LIMPEZA AUTOMÁTICA DOS AGENDAMENTOS (remove objetos vazios) ==========
$agendamentos_raw = $teacher_data['agendamentos'] ?? [];
$agendamentos = [];
if (!empty($agendamentos_raw)) {
    foreach ($agendamentos_raw as $item) {
        if (is_array($item) && isset($item['date']) && !empty($item['date'])) {
            $agendamentos[] = $item;
        } elseif (is_string($item)) {
            $agendamentos[] = ['date' => $item, 'status' => 'pending', 'student' => 'desconhecido'];
        }
    }
}
// Salva a limpeza no array principal, se necessário
if (isset($users[$id_professor]['agendamentos']) && count($users[$id_professor]['agendamentos']) !== count($agendamentos)) {
    $users[$id_professor]['agendamentos'] = $agendamentos;
    file_put_contents('users.json', json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}
// ========== FIM DA LIMPEZA ==========

// Flash messages
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

// POST: atualizar informações da sala (owner)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_room']) && $is_owner) {
    $users[$id_professor]['sala_fisica'] = $_POST['sala_fisica'];
    $users[$id_professor]['horario'] = [
        'dias'   => $_POST['dias'] ?? [],
        'inicio' => $_POST['inicio'],
        'fim'    => $_POST['fim'],
    ];
    file_put_contents('users.json', json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Informações da sala atualizadas.'];
    header("Location: teacher_profile.php?id=" . $id_professor);
    exit;
}

// POST: agendamento (student) - CORRIGIDO COM VERIFICAÇÃO DE ESCRITA
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agendar_data']) && $user_role === 'student' && !$is_owner) {
    $data_agendada = $_POST['agendar_data'];
    
    // --- Garante que a array de agendamentos está limpa e é uma array ---
    if (!isset($users[$id_professor]['agendamentos']) || !is_array($users[$id_professor]['agendamentos'])) {
        $users[$id_professor]['agendamentos'] = [];
    }
    // Remove lixos (objetos sem 'date')
    $users[$id_professor]['agendamentos'] = array_values(array_filter($users[$id_professor]['agendamentos'], function($item) {
        return is_array($item) && isset($item['date']) && !empty($item['date']);
    }));
    
    // Verifica duplicidade
    $existe = false;
    foreach ($users[$id_professor]['agendamentos'] as $ag) {
        if ($ag['date'] === $data_agendada) {
            $existe = true;
            break;
        }
    }
    
    if (!$existe) {
        $dias_semana = ['sunday','monday','tuesday','wednesday','thursday','friday','saturday'];
        $dia_da_semana_num = date('w', strtotime($data_agendada));
        $dia_da_semana_nome = $dias_semana[$dia_da_semana_num];
        $today = date('Y-m-d');
        $max_date = date('Y-m-d', strtotime('+30 days'));
        $valid_day = in_array($dia_da_semana_nome, $horario['dias'] ?? []);
        $within_range = ($data_agendada >= $today && $data_agendada <= $max_date);
        
        if ($valid_day && $within_range) {
            $novo_agendamento = [
                'date'    => $data_agendada,
                'student' => $matricula_logada,
                'status'  => 'pending'
            ];
            $users[$id_professor]['agendamentos'][] = $novo_agendamento;
            
            // Tenta escrever e verifica o retorno
            $json_data = json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            if (file_put_contents('users.json', $json_data) === false) {
                $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Erro ao salvar o agendamento. Verifique as permissões do arquivo users.json.'];
            } else {
                $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Atendimento agendado para ' . date('d/m/Y', strtotime($data_agendada)) . '!'];
            }
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Data inválida ou fora do período permitido.'];
        }
    } else {
        $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Já existe um agendamento nesta data.'];
    }
    header("Location: teacher_profile.php?id=" . $id_professor);
    exit;
}

// POST: cancelar pelo aluno
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancelar_data']) && $user_role === 'student') {
    $data_cancelar = $_POST['cancelar_data'];

    foreach ($users[$id_professor]['agendamentos'] as $key => $ag) {
        if (
            $ag['date'] === $data_cancelar &&
            $ag['student'] === $matricula_logada &&
            in_array($ag['status'], ['confirmed', 'pending'])
        ) {
            unset($users[$id_professor]['agendamentos'][$key]);
            $users[$id_professor]['agendamentos'] = array_values($users[$id_professor]['agendamentos']);

            file_put_contents('users.json', json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

            $_SESSION['flash'] = [
                'type' => 'success',
                'msg' => 'Agendamento cancelado com sucesso.'
            ];
            break;
        }
    }

    header("Location: teacher_profile.php?id=" . $id_professor);
    exit;
}

// POST: confirmar/cancelar (teacher owner)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $is_owner) {
    $data_acao = $_POST['date'];
    $action = $_POST['action'];
    $msg = '';
    foreach ($users[$id_professor]['agendamentos'] as $key => &$ag) {
        if ($ag['date'] === $data_acao) {
            if ($action === 'confirm') {
                $ag['status'] = 'confirmed';
                $msg = 'Agendamento confirmado.';
            } elseif ($action === 'cancel') {
                unset($users[$id_professor]['agendamentos'][$key]);
                $users[$id_professor]['agendamentos'] = array_values($users[$id_professor]['agendamentos']);
                $msg = 'Agendamento cancelado.';
            }
            break;
        }
    }
    unset($ag);
    file_put_contents('users.json', json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    $_SESSION['flash'] = ['type' => 'success', 'msg' => $msg];
    header("Location: teacher_profile.php?id=" . $id_professor);
    exit;
}

// Monta texto legível do horário
$dias_pt = [
    'sunday' => 'Domingo', 'monday' => 'Segunda-feira', 'tuesday' => 'Terça-feira',
    'wednesday' => 'Quarta-feira', 'thursday' => 'Quinta-feira', 'friday' => 'Sexta-feira', 'saturday' => 'Sábado'
];
$dias_legivel = '';
if (!empty($horario['dias'])) {
    $nomes = array_map(fn($d) => $dias_pt[$d] ?? $d, $horario['dias']);
    $dias_legivel = implode(', ', $nomes);
}
$horario_legivel = $dias_legivel
    ? "$dias_legivel — {$horario['inicio']} às {$horario['fim']}"
    : 'Não definido';

// Prepara eventos para FullCalendar
$dow_map = [
    'sunday'=>0,'monday'=>1,'tuesday'=>2,
    'wednesday'=>3,'thursday'=>4,'friday'=>5,'saturday'=>6
];

$today = date('Y-m-d');
$end_date = date('Y-m-d', strtotime('+30 days'));
$fc_events = [];

// Dias disponíveis recorrentes (verdes), apenas no intervalo de hoje a hoje+30
if (!empty($horario['dias'])) {
    foreach ($horario['dias'] as $dia) {
        $fc_events[] = [
            'title'      => 'Disponível',
            'daysOfWeek' => [$dow_map[$dia] ?? 0],
            'startTime'  => $horario['inicio'],
            'endTime'    => $horario['fim'],
            'color'      => '#2E8E5A',
            'startRecur' => $today,
            'endRecur'   => $end_date,
        ];
    }
}

// Agendamentos
foreach ($agendamentos as $ag) {
    $status = $ag['status'] ?? 'pending';
    $student_mat = $ag['student'] ?? '';
    $student_name = $users[$student_mat]['name'] ?? 'Aluno';
    $title = ($status === 'confirmed') ? 'Confirmado' : 'Agendado (pendente)';
    $color = ($status === 'confirmed') ? '#3498db' : '#f39c12';
    $fc_events[] = [
        'title'   => $title . ' - ' . $student_name,
        'start'   => $ag['date'],
        'color'   => $color,
        'allDay'  => true,
        'extendedProps' => [
            'status' => $status,
            'student' => $student_name,
            'date' => $ag['date']
        ]
    ];
}

$fc_events_json = json_encode($fc_events);
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($teacher_name); ?> - Perfil</title>
    <link rel="stylesheet" href="colors.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles/navbar.css">
    <link rel="stylesheet" href="styles/teacher_profile.css">
    <link rel="stylesheet" href="styles/footer.css">
    <link rel="stylesheet" href="styles/modals.css">
    <link rel="icon" type="image/svg+xml" href="assets/icons/kite-origami-paper-svgrepo-com.svg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.20/index.global.min.js"></script>
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
        <?php if ($is_owner): ?>
            <span class="logout">
                <a href="logout.php">Sair <i class="fa-solid fa-right-from-bracket"></i></a>
            </span>
        <?php endif; ?>
        <div class="container my-5">
            <section class="teacher">
                <h2>
                    <?php if ($is_owner): ?>
                    Olá, <?php echo $teacher_name; ?>!
                    <?php else: ?>
                    <?php echo $teacher_name; ?>
                    <?php endif; ?>
                </h2>
            </section>
            <!-- Ícones originais -->
            <div class="icons">
                <div id="btn-grid">
                    <img src="assets/icons/list-paper-school-svgrepo-com.svg" alt="Documentos">
                </div>
                <div id="btn-doc">
                    <img src="assets/icons/calendar-days-svgrepo-com.svg" alt="Calendário">
                </div>
            </div>
            <!-- Flash message -->
            <?php if ($flash): ?>
            <div class="flash-message <?php echo $flash['type'] === 'success' ? 'flash-success' : 'flash-error'; ?>">
                <?php echo htmlspecialchars($flash['msg']); ?>
            </div>
            <?php endif; ?>
            <!-- Área Documentos -->
            <div id="area-documentos" class="area">
                <div class="grid">
                    <?php if ($is_owner): ?>
                    <div class="box add">+</div>
                    <?php else: ?>
                    <?php endif; ?>
                </div>
            </div>
    </div>

        <!-- Área Calendário (sala de aula integrada) -->
        <div id="area-calendar" class="area">
            <div class="room-card">
                <div class="room-header">
                    <h1><?php echo htmlspecialchars($disciplina); ?></h1>
                    <p>Professor(a): <strong><?php echo htmlspecialchars($teacher_name); ?></strong></p>
                </div>
                <div class="info-group">
                    <label>Localização:</label>
                    <span><?php echo htmlspecialchars($sala_fisica); ?></span>
                </div>
                <div class="info-group">
                    <label>Horário de Atendimento:</label>
                    <span><?php echo htmlspecialchars($horario_legivel); ?></span>
                </div>
                <?php if ($user_role === 'student' && !$is_owner): ?>
                <p class="instrucao-agendamento">
                    Clique em um dia <strong>disponível</strong> para agendar seu atendimento.
                </p>
                <?php endif; ?>
                <?php if ($is_owner): ?>
                <div class="action-area">
                    <button class="btn btn-edit" onclick="openEditModal()">✏️ Alterar Horário</button>
                </div>
                <?php endif; ?>
            </div>

            <div class="legenda">
                <span><span class="cor-disponivel"></span>Disponível</span>
                <span><span class="cor-pendente"></span>Agendado (pendente)</span>
                <span><span class="cor-confirmado"></span>Confirmado</span>
            </div>
            <div id="calendar"></div>
        </div>
    </div>

    <!-- Modal de Edição da Sala (owner) -->
    <?php if ($is_owner): ?>
    <div id="modalEdit" class="modal-overlay">
        <div class="modal-content">
            <h3>Editar Informações da Sala</h3>
            <form method="POST">
                <label for="sala_fisica">Nova Localização:</label>
                <input type="text" name="sala_fisica" id="sala_fisica"
                    value="<?php echo htmlspecialchars($sala_fisica); ?>" required>

                <label>Dias de Atendimento:</label>
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
                        <label for="inicio">Início:</label>
                        <input type="text" name="inicio" id="inicio"
                            value="<?php echo htmlspecialchars($horario['inicio'] ?? ''); ?>" placeholder="08:00"
                            required>
                    </div>
                    <div>
                        <label for="fim">Fim:</label>
                        <input type="text" name="fim" id="fim"
                            value="<?php echo htmlspecialchars($horario['fim'] ?? ''); ?>" placeholder="09:40" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-cancel" onclick="closeEditModal()">Cancelar</button>
                    <button type="submit" name="update_room" class="btn btn-save">Salvar</button>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>

    <!-- Modal de Confirmação (teacher owner) -->
    <?php if ($is_owner): ?>
    <div id="modalConfirm" class="modal-overlay">
        <div class="modal-content">
            <span class="modal-close" id="closeConfirmModalX">&times;</span>
            <h3 id="modalConfirmTitle">Confirmar agendamento</h3>
            <p id="modalConfirmText"></p>
            <div class="modal-footer">
                <button class="btn btn-yes" id="btnConfirmYes">✓ Confirmar</button>
                <button class="btn btn-no" id="btnConfirmNo">✗ Cancelar</button>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Form oculto para agendamento (student) -->
    <?php if ($user_role === 'student' && !$is_owner): ?>
    <form id="form-agendar" method="POST" style="display:none;">
        <input type="hidden" name="agendar_data" id="input-data-agendamento">
    </form>
    <?php endif; ?>

    <?php if ($user_role === 'student'): ?>
    <form id="form-cancelar" method="POST" style="display:none;">
        <input type="hidden" name="cancelar_data" id="input-cancelar-data">
    </form>
    <?php endif; ?>

    <!-- Form oculto para confirmar/cancelar (teacher) -->
    <?php if ($is_owner): ?>
    <form id="form-action" method="POST" style="display:none;">
        <input type="hidden" name="date" id="input-date-action">
        <input type="hidden" name="action" id="input-action-type">
    </form>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Alternância de abas (ícones)
const areaDocs = document.getElementById("area-documentos");
const areaCalendar = document.getElementById("area-calendar");
const btnGrid = document.getElementById("btn-grid");
const btnDoc = document.getElementById("btn-doc");
const btnAdd = document.getElementById("btn-add");

let calendar;

function resetEstado() {
    areaDocs.classList.remove("ativa");
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
                if (calendar) {
                    calendar.render();
                    calendar.updateSize();
                }
            }, 100);
        }
    }
}

btnDoc.addEventListener("click", () => alternarVisualizacao(areaCalendar, btnDoc));
btnGrid.addEventListener("click", () => alternarVisualizacao(areaDocs, btnGrid));
if (btnAdd) {
    btnAdd.addEventListener("click", () => {
        alert('Funcionalidade de upload em desenvolvimento.');
    });
}

// Calendário
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    const events = <?php echo $fc_events_json; ?>;
    const userRole = '<?php echo $user_role; ?>';
    const isOwner = <?php echo $is_owner ? 'true' : 'false'; ?>;
    const todayStr = '<?php echo $today; ?>';
    const endStr = '<?php echo $end_date; ?>';

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
        
        dateClick: function(info) {
            const dateStr = info.dateStr;
            
            // Busca todos os eventos desta data
            const eventosDoDia = calendar.getEvents().filter(function(ev) {
                return ev.startStr.substring(0, 10) === dateStr;
            });
            
            // Filtra eventos específicos
            const disponiveis = eventosDoDia.filter(ev => ev.title.includes('Disponível'));
            const agendados = eventosDoDia.filter(ev => 
                ev.title.includes('Agendado') || ev.title.includes('Confirmado')
            );
            
            // LÓGICA PARA PROFESSOR (owner)
            if (isOwner) {
                if (agendados.length > 0) {
                    const ag = agendados[0];
                    const props = ag.extendedProps;
                    const dataFormatada = formatDate(props.date || dateStr);
                    
                    if (props.status === 'pending') {
                        // Modal para agendamento pendente
                        showCustomModal(
                            'Agendamento Pendente',
                            'Agendamento de <strong>' + props.student + '</strong> para o dia ' + dataFormatada + '.<br><br>O que deseja fazer?',
                            [
                                { 
                                    text: '✓ Confirmar', 
                                    class: 'btn-yes', 
                                    onClick: function() {
                                        closeCustomModal();
                                        document.getElementById('input-date-action').value = props.date;
                                        document.getElementById('input-action-type').value = 'confirm';
                                        document.getElementById('form-action').submit();
                                    }
                                },
                                { 
                                    text: '✗ Cancelar Agendamento', 
                                    class: 'btn-no', 
                                    onClick: function() {
                                        closeCustomModal();
                                        showCustomModal(
                                            'Confirmar Cancelamento',
                                            'Tem certeza que deseja cancelar este agendamento?',
                                            [
                                                { 
                                                    text: 'Sim, Cancelar', 
                                                    class: 'btn-no', 
                                                    onClick: function() {
                                                        closeCustomModal();
                                                        document.getElementById('input-date-action').value = props.date;
                                                        document.getElementById('input-action-type').value = 'cancel';
                                                        document.getElementById('form-action').submit();
                                                    }
                                                },
                                                { 
                                                    text: 'Não', 
                                                    class: 'btn-secondary', 
                                                    onClick: closeCustomModal 
                                                }
                                            ]
                                        );
                                    }
                                },
                                { 
                                    text: 'Fechar', 
                                    class: 'btn-secondary', 
                                    onClick: closeCustomModal 
                                }
                            ]
                        );
                    } else if (props.status === 'confirmed') {
                        // Modal para agendamento confirmado
                        showCustomModal(
                            'Agendamento Confirmado',
                            'Este agendamento já está confirmado para <strong>' + props.student + '</strong> no dia ' + dataFormatada + '.',
                            [
                                { 
                                    text: 'Cancelar Agendamento', 
                                    class: 'btn-no', 
                                    onClick: function() {
                                        closeCustomModal();
                                        showCustomModal(
                                            'Confirmar Cancelamento',
                                            'Tem certeza que deseja cancelar este agendamento confirmado?',
                                            [
                                                { 
                                                    text: 'Sim, Cancelar', 
                                                    class: 'btn-no', 
                                                    onClick: function() {
                                                        closeCustomModal();
                                                        document.getElementById('input-date-action').value = props.date;
                                                        document.getElementById('input-action-type').value = 'cancel';
                                                        document.getElementById('form-action').submit();
                                                    }
                                                },
                                                { 
                                                    text: 'Não', 
                                                    class: 'btn-secondary', 
                                                    onClick: closeCustomModal 
                                                }
                                            ]
                                        );
                                    }
                                },
                                { 
                                    text: 'Fechar', 
                                    class: 'btn-secondary', 
                                    onClick: closeCustomModal 
                                }
                            ]
                        );
                    }
                } else {
                    // Nenhum agendamento nesta data
                    showCustomModal(
                        'Sem Agendamentos',
                        'Não há agendamentos para o dia ' + formatDate(dateStr) + '.',
                        [{ text: 'OK', class: 'btn-primary', onClick: closeCustomModal }]
                    );
                }
                return;
            }
            
            // LÓGICA PARA ALUNO (student)
            if (userRole === 'student' && !isOwner) {
                if (agendados.length > 0) {
                    const ag = agendados[0];
                    const props = ag.extendedProps;
                    const dataFormatada = formatDate(props.date);
                    
                    // Modal personalizado para cancelar
                    showCustomModal(
                        'Agendamento Encontrado',
                        'Você tem um agendamento <strong>' + 
                        (props.status === 'confirmed' ? 'confirmado' : 'pendente') + 
                        '</strong> para o dia ' + dataFormatada + '. Deseja cancelar?',
                        [
                            { 
                                text: 'Cancelar Agendamento', 
                                class: 'btn-no', 
                                onClick: function() {
                                    closeCustomModal();
                                    document.getElementById('input-cancelar-data').value = props.date;
                                    document.getElementById('form-cancelar').submit();
                                }
                            },
                            { 
                                text: 'Manter', 
                                class: 'btn-yes', 
                                onClick: closeCustomModal 
                            }
                        ]
                    );
                } else if (disponiveis.length > 0) {
                    // Data disponível para agendamento
                    if (dateStr < todayStr) {
                        showCustomModal(
                            'Data Inválida',
                            'Não é possível agendar para datas passadas.',
                            [{ text: 'OK', class: 'btn-secondary', onClick: closeCustomModal }]
                        );
                        return;
                    }
                    if (dateStr > endStr) {
                        showCustomModal(
                            'Data Inválida',
                            'Agendamento apenas para os próximos 30 dias.',
                            [{ text: 'OK', class: 'btn-secondary', onClick: closeCustomModal }]
                        );
                        return;
                    }
                    
                    const dataFormatada = formatDate(dateStr);
                    showCustomModal(
                        'Agendar Atendimento',
                        'Deseja agendar um atendimento para <strong>' + dataFormatada + '</strong>?',
                        [
                            { 
                                text: 'Confirmar Agendamento', 
                                class: 'btn-yes', 
                                onClick: function() {
                                    closeCustomModal();
                                    document.getElementById('input-data-agendamento').value = dateStr;
                                    document.getElementById('form-agendar').submit();
                                }
                            },
                            { 
                                text: 'Cancelar', 
                                class: 'btn-no', 
                                onClick: closeCustomModal 
                            }
                        ]
                    );
                } else {
                    // Nada nesta data
                    showCustomModal(
                        'Data Indisponível',
                        'Este dia não está disponível para agendamento ou não há atividades programadas.',
                        [{ text: 'OK', class: 'btn-secondary', onClick: closeCustomModal }]
                    );
                }
            }
        }
    });
    calendar.render();
    window.calendar = calendar;

    // Exibe a aba de calendário por padrão
    alternarVisualizacao(areaCalendar, btnDoc);
});

// Funções auxiliares
function formatDate(dateStr) {
    return new Date(dateStr + 'T12:00:00').toLocaleDateString('pt-BR');
}

// Modal de edição
function openEditModal() {
    document.getElementById('modalEdit').style.display = 'flex';
}

function closeEditModal() {
    document.getElementById('modalEdit').style.display = 'none';
}

// FUNÇÕES DO MODAL CUSTOMIZADO (CORRIGIDAS)
function showCustomModal(title, message, buttons) {
    // Remove modal anterior se existir
    const existingModal = document.getElementById('customModal');
    if (existingModal) {
        existingModal.remove();
    }
    
    // Cria o modal dinamicamente
    const modalHTML = `
        <div id="customModal" class="modal-overlay" style="display: flex;">
            <div class="modal-content">
                <span class="modal-close" onclick="closeCustomModal()">&times;</span>
                <h3>${title}</h3>
                <p>${message}</p>
                <div class="modal-footer">
                    ${buttons.map((btn, index) => 
                        `<button class="btn ${btn.class}" id="customBtn${index}">${btn.text}</button>`
                    ).join('')}
                </div>
            </div>
        </div>
    `;
    
    // Adiciona o novo modal
    document.body.insertAdjacentHTML('beforeend', modalHTML);
    
    // Adiciona event listeners aos botões
    buttons.forEach((btn, index) => {
        const buttonEl = document.getElementById(`customBtn${index}`);
        if (buttonEl && btn.onClick) {
            buttonEl.addEventListener('click', btn.onClick);
        }
    });
}

function closeCustomModal() {
    const modal = document.getElementById('customModal');
    if (modal) {
        modal.remove();
    }
}

// Fechar modais ao clicar fora
window.onclick = function(e) {
    if (e.target === document.getElementById('modalEdit')) closeEditModal();
    if (e.target === document.getElementById('customModal')) closeCustomModal();
}
</script>
</body>

</html>