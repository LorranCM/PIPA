<?php
require "components/preset.php";
loggedOut_verification();

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.20/index.global.min.js'></script>
    
</head>

<body>
    
    <?php 
        include 'components/navbar.php'; 
        modular_nav();
        ?>
    
    <section class="topo">
        <div class="perfil">
            <img src=<?php echo $_SESSION['pfp-rel']?> alt="perfil">
        </div>
        <h2>Bem vindo, <?php echo $_SESSION['name']?>!</h2>
    </section>
    
    <section class="page-content">
        <h2>Suas salas</h2>
        <div id="classrooms-visualizer" >
            
            </div>
            <div id="calendar-wrapper">
                <div id='calendar'>
                    </div>
                </div>
            </section>
            
    <script src="services/view/classrooms.js"></script>
    <script src="services/view/interactive_calendar.js" type='module'></script>
    
</body>

</html>