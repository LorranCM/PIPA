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
    <script src=<?= base_url("assets/js/fullcalendar/interactive_calendar.js") ?> type='module'></script>
    <script>
        const btn_documents = document.getElementById("btn-documents");
        const btn_calendar = document.getElementById("btn-calendar");

        const calendar_wrapper = document.getElementById("calendar-wrapper");
        const documents_wrapper = document.getElementById("documents-wrapper");

        documents_wrapper.classList.add('hidden');   

        btn_calendar.addEventListener('click', function() {
            calendar_wrapper.classList.remove('hidden');   
            documents_wrapper.classList.add('hidden');   
            window.calendar.render();
        });
        
        btn_documents.addEventListener('click', function() {
            documents_wrapper.classList.remove('hidden');   
            calendar_wrapper.classList.add('hidden');   
        });

    </script>

<?= $this->endSection(); ?>

<!-- 
#--------------------------------------------------------------------
#   Conteúdo
#--------------------------------------------------------------------
-->

<?= $this->section('content'); ?>
    
    <section class="page-content">
        <div id="content-selector"> 
            <img id="btn-calendar" src= <?= base_url("assets/icons/calendar-days-svgrepo-com.svg") ?> alt="Calendário">
            <img id="btn-documents" src= <?= base_url("assets/icons/list-paper-school-svgrepo-com.svg") ?> alt="Documentos">
        </div>

        <div id="calendar-wrapper">
            <div id='calendar'></div>
        </div>
        <div id="documents-wrapper">
            <?php 
                if (session()->getFlashdata('isOwner')) {
                    echo '
                    <script type="module">
                        import { loadCreateDocumentButton }
                        from "' . base_url('assets/js/create_document_button.js') . '";
                        loadCreateDocumentButton("' . session()->getFlashdata('classroom_id') . '");
                    </script>
                    ';
                }
            ?>
        </div>
    </section>

<?= $this->endSection(); ?>
