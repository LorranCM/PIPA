<?php echo $this->extend('master'); ?>

<!-- 
#--------------------------------------------------------------------
#   Estilos
#--------------------------------------------------------------------
-->

<?= $this->section('styles-ref'); ?>

    <link rel="stylesheet" href=<?= base_url("assets/css/student_profile.css") ?>>
    <link rel="stylesheet" href=<?= base_url("assets/css/colors.css") ?>>
    <link rel="stylesheet" href=<?= base_url("assets/css/modals.css") ?>>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<?= $this->endSection(); ?>

<!-- 
#--------------------------------------------------------------------
#   Scripts
#--------------------------------------------------------------------
-->

<?= $this->section('scripts-ref'); ?>

    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.20/index.global.min.js'></script>
    <script src="services/view/interactive_calendar.js" type='module'></script>
    <script src="services/view/classrooms.js"></script>

<?= $this->endSection(); ?>

<!-- 
#--------------------------------------------------------------------
#   Conteúdo
#--------------------------------------------------------------------
-->

<?= $this->section('content'); ?>
    
    <section class="topo">
        <div class="perfil">
            <img src='' alt="perfil">
        </div>
        <h2>Bem vindo, <?php echo session()->get('user_data')['name'] ?>!</h2>
    </section>
    
    <section class="page-content">
        <h2>Suas salas</h2>
        <div id="classrooms-visualizer"></div>
        <div id="calendar-wrapper">
            <div id='calendar'></div>
        </div>
    </section>

<?= $this->endSection(); ?>
