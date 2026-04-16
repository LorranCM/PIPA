<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

$users = json_decode(file_get_contents('users.json'), true) ?? [];

// Identifica quem é o professor dono desta sala
$id_professor = $_GET['id'] ?? '';
$teacher_data = $users[$id_professor] ?? null;

if (!$teacher_data || $teacher_data['role'] !== 'teacher') {
    echo "Sala não encontrada.";
    exit;
}

// Dados do usuário logado
$matricula_logada = $_SESSION['matricula'];
$user_role = $_SESSION['role'] ?? 'student';

// Verifica se o professor logado é o dono desta sala
$is_owner = ($user_role === 'teacher' && $matricula_logada === $id_professor);

// --- LÓGICA DE ATUALIZAÇÃO ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_room']) && $is_owner) {
    $nova_sala = $_POST['sala_fisica'];
    $novo_horario = $_POST['horario'];

    // Atualiza os dados no array
    $users[$id_professor]['sala_fisica'] = $nova_sala;
    $users[$id_professor]['horario'] = $novo_horario;

    // Salva de volta no users.json
    file_put_contents('users.json', json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    
    // Atualiza os dados locais para exibir na página sem precisar de refresh manual
    $teacher_data = $users[$id_professor];
}

// Puxa os dados do JSON ou usa o padrão caso não existam
$disciplina = $teacher_data['materia'] ?? "Desenvolvimento de Sistemas";
$sala_fisica = $teacher_data['sala_fisica'] ?? "Laboratório 203 - Bloco 2";
$horario = $teacher_data['horario'] ?? "Segundas e Quartas, 10:00 - 12:40";
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Sala - <?php echo $disciplina; ?></title>
    <link rel="stylesheet" href="colors.css">
    <link rel="stylesheet" href="styles/navbar.css">
    <link rel="stylesheet" href="styles/student_profile.css">
    <link rel="stylesheet" href="styles/footer.css">
    <style>
        .classroom-container { padding: 40px 50px; font-family: 'Encode Sans', sans-serif; }
        .room-card { background: white; border: 1px solid #ddd; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .room-header { border-bottom: 2px solid var(--primary-color, #2E8E5A); margin-bottom: 20px; padding-bottom: 10px; }
        .info-group { margin-bottom: 15px; }
        .info-group label { font-weight: bold; color: #555; display: block; }
        .info-group span { font-size: 1.1em; color: #222; }
        .action-area { margin-top: 30px; }
        .btn { padding: 12px 25px; border-radius: 5px; border: none; cursor: pointer; font-weight: bold; text-decoration: none; display: inline-block; }
        .btn-schedule { background-color: #2E8E5A; color: white; }
        .btn-edit { background-color: #f39c12; color: white; }
        .btn:hover { opacity: 0.9; }

        /* Estilos do Modal */
        #modalEdit { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); }
        .modal-content { background: white; margin: 10% auto; padding: 25px; border-radius: 8px; width: 400px; }
        .modal-content input { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .modal-footer { text-align: right; margin-top: 20px; }
        .btn-cancel { background: #ccc; color: #333; margin-right: 10px; }
    </style>
</head>
<body>
    <nav id="navbar">
        <ul>
            <li id="navbar-PIPA-clickable">
                <a href="index.php">
                    <img src="assets/icons/kite-origami-paper-svgrepo-com.svg" alt="icone do pipa">PIPA
                </a>
            </li>
        </ul>
    </nav>
    <section class="banner"></section>
    <section class="classroom-container">
        <div class="room-card">
            <div class="room-header">
                <h1><?php echo $disciplina; ?></h1>
                <p>Professor(a): <strong><?php echo $teacher_data['name']; ?></strong></p>
            </div>

            <div class="info-group">
                <label>Localização:</label>
                <span><?php echo $sala_fisica; ?></span>
            </div>

            <div class="info-group">
                <label>Horário das Aulas:</label>
                <span><?php echo $horario; ?></span>
            </div>

            <div class="action-area">
                <?php if ($is_owner): ?>
                    <button class="btn btn-edit" onclick="openModal()"> Alterar Horário da Aula</button>
                <?php elseif ($user_role === 'student'): ?>
                    <button class="btn btn-schedule" onclick="alert('Aula agendada com sucesso!')">Agendar Atendimento</button>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <div id="modalEdit">
        <div class="modal-content">
            <h3>Editar Informações da Sala</h3>
            <form method="POST">
                <label>Nova Localização:</label>
                <input type="text" name="sala_fisica" value="<?php echo $sala_fisica; ?>" required>
                
                <label>Novo Horário:</label>
                <input type="text" name="horario" value="<?php echo $horario; ?>" required>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-cancel" onclick="closeModal()">Cancelar</button>
                    <button type="submit" name="update_room" class="btn btn-schedule">Salvar Alterações</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById('modalEdit').style.display = 'block';
        }
        function closeModal() {
            document.getElementById('modalEdit').style.display = 'none';
        }
        // Fecha o modal se clicar fora dele
        window.onclick = function(event) {
            if (event.target == document.getElementById('modalEdit')) closeModal();
        }
    </script>
</body>
</html>