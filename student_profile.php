<?php
require 'components/preset.php';
loggedOut_verification();

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

$users = json_decode(file_get_contents('users.json'), true) ?? [];
// Pega a matrícula de quem está LOGADO
$matricula_logada = $_SESSION['matricula'];

// Pega a matrícula do DONO da página (via URL) ou assume que é o próprio logado
$matricula_perfil = $_GET['id'] ?? $matricula_logada;

// Verifica se o usuário logado é o dono da página
$is_own_profile = ($matricula_logada === $matricula_perfil);
$student_name = $users[$matricula_perfil]['name'] ?? 'Aluno';
// Pega o email do aluno ou usa um valor padrão
$student_email = $users[$matricula_perfil]['email'] ?? 'Email não disponível';

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
                    'professor' => $user['name'] ?? 'Professor',
                    'professor_id' => $id
                ];
            }
        }
    }
}

$meus_agendamentos_json = json_encode($meus_agendamentos);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $novo_nome  = $_POST['name'] ?? '';
    $novo_email = $_POST['email'] ?? '';
    $nova_senha = $_POST['password'] ?? '';

    $users = json_decode(file_get_contents('users.json'), true) ?? [];

    if (isset($users[$matricula_logada])) {
        $users[$matricula_logada]['name']  = $novo_nome;
        $users[$matricula_logada]['email'] = $novo_email;

        // Atualiza senha só se foi preenchida, armazenando em texto puro
        if (!empty($nova_senha)) {
            $users[$matricula_logada]['password'] = $nova_senha;   // ✅ sem hash
        }

        file_put_contents('users.json', json_encode($users, JSON_PRETTY_PRINT));

        $student_name  = $novo_nome;
        $student_email = $novo_email;

        header("Location: " . $_SERVER['PHP_SELF'] . "?updated=1");
exit;
    }
}

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil do Aluno - PIPA</title>
    <link rel="stylesheet" href="styles/student_profile.css">
    <link rel="stylesheet" href="colors.css">
    <link rel="stylesheet" href="styles/navbar.css">
    <link rel="stylesheet" href="styles/footer.css">
    <link rel="stylesheet" href="styles/modals.css">
    <link rel="icon" type="image/svg+xml" href="assets/icons/kite-origami-paper-svgrepo-com.svg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
                allDay: true,
                extendedProps: {
                    status: ag.status,
                    professor: ag.professor,
                    professor_id: ag.professor_id,
                    date: ag.date
                }
            };
        });

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'pt-br',
            height: 'auto',
            events: eventosFormatados,
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth'
            },
            dateClick: function(info) {
                const dateStr = info.dateStr;
                const eventosDoDia = calendar.getEvents().filter(function(ev) {
                    return ev.startStr.substring(0, 10) === dateStr;
                });

                if (eventosDoDia.length > 0) {
                    // Tem eventos neste dia
                    const evento = eventosDoDia[0];
                    const props = evento.extendedProps;
                    const dataFormatada = formatDate(dateStr);

                    showCustomModal(
                        'Agendamento Encontrado',
                        'Você tem um agendamento <strong>' +
                        (props.status === 'confirmed' ? 'confirmado' : 'pendente') +
                        '</strong> com ' + props.professor +
                        ' para o dia ' + dataFormatada + '.<br><br>' +
                        'Deseja acessar a sala do professor ou cancelar este agendamento?',
                        [{
                                text: 'Ir para Sala',
                                class: 'btn-primary',
                                onClick: function() {
                                    window.location.href = 'teacher_profile.php?id=' + props
                                        .professor_id;
                                }
                            },
                            {
                                text: 'Cancelar Agendamento',
                                class: 'btn-danger',
                                onClick: function() {
                                    showCustomModal(
                                        'Confirmar Cancelamento',
                                        'Tem certeza que deseja cancelar o agendamento com ' +
                                        props.professor + ' no dia ' + dataFormatada +
                                        '?',
                                        [{
                                                text: 'Sim, Cancelar',
                                                class: 'btn-danger',
                                                onClick: function() {
                                                    // Cria um formulário dinâmico para cancelar
                                                    var form = document
                                                        .createElement('form');
                                                    form.method = 'POST';
                                                    form.action =
                                                        'teacher_profile.php?id=' +
                                                        props.professor_id;

                                                    var input = document
                                                        .createElement('input');
                                                    input.type = 'hidden';
                                                    input.name = 'cancelar_data';
                                                    input.value = props.date;

                                                    form.appendChild(input);
                                                    document.body.appendChild(form);
                                                    form.submit();
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
                } else {
                    // Nenhum evento neste dia
                    showCustomModal(
                        'Nada nesta data',
                        'Não há agendamentos para o dia ' + formatDate(dateStr) + '.',
                        [{
                            text: 'OK',
                            class: 'btn-primary',
                            onClick: closeCustomModal
                        }]
                    );
                }
            }
        });
        calendar.render();
    });

    // Funções auxiliares
    function formatDate(dateStr) {
        return new Date(dateStr + 'T12:00:00').toLocaleDateString('pt-BR');
    }

    // Modal personalizado genérico
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
                            `<button class="modal-btn ${btn.class}" id="customBtn${index}">${btn.text}</button>`
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

    // Fechar modal ao clicar fora
    window.onclick = function(e) {
        if (e.target === document.getElementById('customModal')) {
            closeCustomModal();
        }
    }

    function togglePopup() {
        var popup = document.getElementById('popup-menu');
        popup.style.display = popup.style.display === 'grid' ? 'none' : 'grid';
    }

    function abrirModal() {
        document.getElementById("modal").style.display = "flex";

        // Preenche com valores atuais
        inputNome.value = nome.innerText;
        inputSemestre.value = semestre.innerText;
        inputCurso.value = curso.innerText;
    }

    function fecharModal() {
        document.getElementById("modal").style.display = "none";
    }

    window.onclick = function(event) {
        const modal = document.getElementById("modal");

        if (event.target === modal) {
            modal.style.display = "none";
        }
    }

    function salvar() {
        nome.innerText = inputNome.value;
        semestre.innerText = inputSemestre.value;
        curso.innerText = inputCurso.value;

        fecharModal();
    }
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
                    <a href="logout.php">Sair <i class="fa-solid fa-right-from-bracket"></i></a>
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
                    <div class="card-part1">
                        <div class="perfil1" onclick="abrirModal()">
                            <button>
                                <img class="avatar" src="assets/images/avatar_aluno.jpg" alt="perfil">
                                <img class="edit-icon" src="assets/icons/pencil-svgrepo-com.svg" alt="lapis">
                            </button>
                        </div>
                        <p class="n">
                            <?php if ($is_own_profile): ?>
                            <?php echo $student_name; ?>
                            <?php else: ?>
                            <?php echo ($disciplina ?? "Perfil") . " - " . $student_name; ?>
                            <?php endif; ?>
                        </p>
                        <p>
                            <?php if ($is_own_profile): ?>
                            <?php echo $student_name; ?>
                            <?php else: ?>
                            <?php echo ($disciplina ?? "Perfil") . " - " . $student_name; ?>
                            <?php endif; ?>
                        </p>
                        <p>
                            <?php if ($is_own_profile): ?>
                            <?php if (str_contains($matricula_logada, "tiimi")): ?>
                            <?php echo "Informática para internet"; ?>
                            <?php else: ?>
                            <?php echo "Aluno"; ?>
                            <?php endif; ?>
                            <?php else: ?>
                            <?php echo ($disciplina ?? "Perfil") . " - " . $student_name; ?>
                            <?php endif; ?>
                        </p>
                    </div>
                    <div class="modal" id="modal">
                        <div class="modal-content">
                            <form method="POST" action="">

                                <h2>Editar Perfil</h2>

                                <label>Nome:</label>
                                <input type="text" name="name" placeholder="<?php echo htmlspecialchars($student_name); ?>"
                                    required>

                                <label>Email:</label>
                                <input type="email" name="email" placeholder="<?php echo $student_email; ?>">

                                <label>Matrícula:</label>
                                <input type="text" value="<?php echo htmlspecialchars($matricula_logada); ?>" disabled>

                                <label>Curso:</label>
                                <input type="text" value="Informática para internet" disabled>

                                <label>Período:</label>
                                <input type="text" value="<?php echo htmlspecialchars($matricula_logada); ?>" disabled>

                                <label>Senha:</label>
                                <input type="password" name="password" placeholder="Digite a nova senha">

                                <button type="submit" class="botao">Salvar</button>

                            </form>
                        </div>
                    </div>
                    <div class="card-part2">
                        <p>
                            <?php if ($is_own_profile): ?>
                            Matrícula: <?php echo $matricula_logada; ?>
                            <?php else: ?>
                            <?php echo ($disciplina ?? "Perfil") . " - " . $matricula_logada; ?>
                            <?php endif; ?>
                        </p>
                        <p class="qrc"></p>
                        <p>
                            Matrícula: ativa
                        </p>
                    </div>
                </div>
            </div>

            <div class="contato">
                <strong>Contato institucional</strong><br><br>
                Email
                <div class="box1">
                    <input type="text" value="<?php echo $student_email; ?>" disabled>
                    Telefone
                    <input type="text" value="(27) 99999-9999" disabled>
                </div>
            </div>
        </section>
    </div>

</body>

</html>