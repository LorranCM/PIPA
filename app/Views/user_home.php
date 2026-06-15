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
    <script type='module'>
        import { get_self_calendar } from '<?= base_url("assets/js/fullcalendar/load_calendar_data.js") ?>';
        window.load_calendar_data = get_self_calendar
    </script>
    <script src=<?= base_url("assets/js/fullcalendar/interactive_calendar.js") ?> type='module'></script>

<?= $this->endSection(); ?>

<!-- 
#--------------------------------------------------------------------
#   Conteúdo
#--------------------------------------------------------------------
-->

<?= $this->section('content'); ?>
    
    <section class="topo">
        <div class="perfil">
            <img src='<?php
                $image_url = session()->get('user_data')['profile-picture-rel'];
                if (file_exists($image_url)) {
                    echo $image_url;
                } else {
                    echo base_url("assets/images/default-pfp.jpg");
                }
            ?>' alt="pfp">
        </div>
        <h2>Bem vindo, <?= session()->get('user_data')['name'] ?>!</h2>
    </section>
    
    <section class="page-content">
        <h2>Suas salas</h2>
        <div id="classrooms-visualizer"></div>
        <div id="calendar-wrapper">
            <div id='calendar'></div>
        </div>
    </section>

<?= $this->endSection(); ?>
