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
$user_role = $_SESSION['role'] ?? 'student'; // 'teacher' ou 'student'

// Verifica se o professor logado é o dono desta sala específica
$is_owner = ($user_role === 'teacher' && $matricula_logada === $id_professor);

// Dados fictícios da sala (podem ser movidos para o users.json depois)
$disciplina = $teacher_data['materia'] ?? "Desenvolvimento de Sistemas";
$sala_fisica = "Laboratório 203 - Prédio Central";
$horario = "Segundas e Quartas, 19:00 - 20:40";
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Sala - <?php echo $disciplina; ?></title>
    <link rel="stylesheet" href="colors.css">
    <link rel="stylesheet" href="styles/navbar.css">
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
    </style>
</head>
<body>
    <nav id="navbar">
        <ul>
            <li id="navbar-PIPA-clickable"><a href="student_profile.php">PIPA</a></li>
            <li><a href="<?php echo ($user_role === 'teacher') ? 'teacher_profile.php' : 'student_profile.php'; ?>">Início</a></li>
            <li><a href="login.php">Sair</a></li>
        </ul>
    </nav>

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
                    <button class="btn btn-edit" onclick="alert('Abrir modal de edição de horário')"> Alterar Horário da Aula</button>
                <?php elseif ($user_role === 'student'): ?>
                    <button class="btn btn-schedule" onclick="alert('Aula agendada com sucesso!')">Agendar Aula Particular</button>
                <?php endif; ?>
            </div>
        </div>
    </section>
</body>
</html>