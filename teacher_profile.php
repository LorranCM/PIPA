<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

$users = json_decode(file_get_contents('users.json'), true) ?? [];

// Identificação do usuário e do perfil visitado
$matricula_logada = $_SESSION['matricula'];
$id_professor = $_GET['id'] ?? $matricula_logada;

$is_own_profile = ($matricula_logada === $id_professor);
$teacher_name = $users[$id_professor]['name'] ?? 'Professor';
$disciplina = $users[$id_professor]['materia'] ?? 'Desenvolvimento de Sistemas';
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página do Professor - <?php echo $teacher_name; ?></title>
    
    <link rel="stylesheet" href="colors.css">
    <link rel="stylesheet" href="styles/teacher_profile.css">
    <link rel="stylesheet" href="styles/footer.css">
    <link rel="stylesheet" href="styles/navbar.css">
    <link rel="icon" type="image/svg+xml" href="assets/icons/kite-origami-paper-svgrepo-com.svg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.20/index.global.min.js'></script>
</head>

<body>

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
                <div id='calendar'></div>
                <div class="card-wrapper mt-4">
                    <strong>Carteirinha PIPA</strong>
                    <div class="box"></div>
                </div>
            </div>

        </section>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // 1. Seleção de elementos
        const areaDocs = document.getElementById("area-documentos");
        const areaAdd = document.getElementById("area-add");
        const areaCalendar = document.getElementById("area-calendar");

        const btnGrid = document.getElementById("btn-grid");
        const btnDoc = document.getElementById("btn-doc");
        const btnAdd = document.getElementById("btn-add");

        let calendar; // Variável global para controle do calendário

        // 2. Inicialização do Calendário (Executa quando a página carrega)
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');
            
            calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'pt-br',
                height: 'auto',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth'
                },
                dateClick: function(info) {
                    alert('Data selecionada: ' + info.dateStr);
                }
            });

            calendar.render();
        });

        // 3. Função para resetar visibilidade e estilos
        function resetEstado() {
            areaDocs.classList.remove("ativa");
            areaAdd.classList.remove("ativa");
            areaCalendar.classList.remove("ativa");
            
            btnGrid.classList.remove("selected");
            btnDoc.classList.remove("selected");
        }

        // 4. Função principal de troca de visualização
        function alternarVisualizacao(area, botao) {
            const jaAtiva = area.classList.contains("ativa");

            resetEstado();

            if (!jaAtiva) {
                area.classList.add("ativa");
                if (botao) botao.classList.add("selected");
                
                // Correção específica para o FullCalendar "amassado"
                if (area === areaCalendar) {
                    setTimeout(() => {
                        if (calendar) {
                            calendar.render();      // Força o desenho
                            calendar.updateSize();  // Ajusta o tamanho ao container visível
                        }
                    }, 100); 
                }
            }
        }

        // 5. Configuração dos Cliques
        btnDoc.addEventListener("click", () => alternarVisualizacao(areaCalendar, btnDoc));
        btnGrid.addEventListener("click", () => alternarVisualizacao(areaDocs, btnGrid));
        btnAdd.addEventListener("click", () => alternarVisualizacao(areaAdd, null));
    </script>
</body>

</html>