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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
        
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.20/index.global.min.js'></script>
    <script>
        const role = "<?php echo $_SESSION['role']?>";
        const events = <?php echo json_encode($_SESSION['calendar_data']); ?>;
    </script>
    <script src="services/fullcalendar.conf/interactive_calendar.js" type='module'></script>
    
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
    </section>

        
    <section>
        <div id='calendar'></div>
    </section>

</body>

</html>