<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}
$matricula = $_SESSION['matricula'];
$users = json_decode(file_get_contents('users.json'), true) ?? [];
$student_name    = $users[$matricula]['name'] ?? 'Aluno';
$student_email   = $users[$matricula]['email'] ?? '';
$student_matricula = $matricula;                       // ✅ a chave do JSON é a própria matrícula
$student_curso   = $users[$matricula]['curso'] ?? 'Curso Indefinido';
$student_período = $users[$matricula]['periodo'] ?? 'Período Indefinido';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $novo_nome  = $_POST['name'] ?? '';
    $novo_email = $_POST['email'] ?? '';
    $nova_senha = $_POST['password'] ?? '';

    $users = json_decode(file_get_contents('users.json'), true) ?? [];

    if (isset($users[$matricula])) {
        $users[$matricula]['name']  = $novo_nome;
        $users[$matricula]['email'] = $novo_email;

        // Atualiza senha só se foi preenchida, armazenando em texto puro
        if (!empty($nova_senha)) {
            $users[$matricula]['password'] = $nova_senha;   // ✅ sem hash
        }

        file_put_contents('users.json', json_encode($users, JSON_PRETTY_PRINT));

        $student_name  = $novo_nome;
        $student_email = $novo_email;

        echo "<script>alert('Dados atualizados com sucesso!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informações do Aluno</title>
    <link rel="stylesheet" href="colors.css">
    <link rel="stylesheet" href="styles/student_information.css">
    <link rel="stylesheet" href="styles/navbar.css">
    <link rel="stylesheet" href="styles/footer.css">
    <link rel="icon" type="image/svg+xml" href="assets/icons/kite-origami-paper-svgrepo-com.svg">
    <title>PIPA</title>

</head>

<body>
    <?php include 'components/navbar.php'; ?>

    <section class="banner"></section>

    <section class="perfil_aluno">

        <img src="assets/images/avatar_aluno.jpg" class="avatar" alt="Foto de perfil">
        <img src="assets/icons/edit-profile-svgrepo-com.svg" alt="editar_perfil" class="editar_perfil">
        <h1 class="nome"><?php echo htmlspecialchars($student_name); ?>!</h1>

    </section>

    <section class="container">

        <div class="card detalhes">

            <h2>Detalhes de Usuário</h2>

            <form method="POST" action="">

                <label>Nome:</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($student_name); ?>" required>

                <label>Email:</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($student_email); ?>" required>

                <label>Matrícula:</label>
                <input type="text" value="<?php echo htmlspecialchars($student_matricula); ?>" disabled>

                <label>Curso:</label>
                <input type="text" value="<?php echo htmlspecialchars($student_curso); ?>" disabled>

                <label>Período:</label>
                <input type="text" value="<?php echo htmlspecialchars($student_período); ?>" disabled>

                <label>Senha:</label>
                <input type="password" name="password" placeholder="Digite a nova senha">

                <button type="submit" class="botao">Salvar</button>

            </form>

        </div>

        <div class="card professores">

            <h2>Professores</h2>

            <div class="professor">
                <img src="assets/images/avatar_aluno.jpg">
                <div>
                    <p>Nome do professor</p>
                    <span>Disciplina: xxxx</span>
                </div>
            </div>

            <div class="professor">
                <img src="assets/images/avatar_aluno.jpg">
                <div>
                    <p>Nome do professor</p>
                    <span>Disciplina: xxxx</span>
                </div>
            </div>

            <div class="professor">
                <img src="assets/images/avatar_aluno.jpg">
                <div>
                    <p>Nome do professor</p>
                    <span>Disciplina: xxxx</span>
                </div>
            </div>

            <div class="professor">
                <img src="assets/images/avatar_aluno.jpg">
                <div>
                    <p>Nome do professor</p>
                    <span>Disciplina: xxxx</span>
                </div>
            </div>

            <div class="professor">
                <img src="assets/images/avatar_aluno.jpg">
                <div>
                    <p>Nome do professor</p>
                    <span>Disciplina: xxxx</span>
                </div>
            </div>
        </div>

    </section>
    <?php include 'components/footer.php'; ?>
    <script>
        function togglePopup() {
            var popup = document.getElementById('popup-menu');
            popup.style.display = popup.style.display === 'grid' ? 'none' : 'grid';
        }
    </script>

</body>

</html>