<?php echo $this->extend('master'); ?>

<!-- 
#--------------------------------------------------------------------
#   Estilos
#--------------------------------------------------------------------
-->

<?= $this->section('styles-ref'); ?>

    <link rel="stylesheet" href=<?= base_url("assets/css/colors.css") ?>>
    <link rel="stylesheet" href=<?= base_url("assets/css/admin_home.css") ?>>

<?= $this->endSection(); ?>

<!-- 
#--------------------------------------------------------------------
#   Conteúdo
#--------------------------------------------------------------------
-->

<?= $this->section('content'); ?>

<div class="admin-container">
    <h1>Painel Administrativo</h1>
    <p>Selecione uma área para gerenciar:</p>

    <div class="admin-grid">
        <a href="<?= url_to('admin_users') ?>" class="admin-card">
            <span class="icon">👤</span>
            <h3>Usuários</h3>
            <p>Gerenciar alunos, professores e admins.</p>
        </a>

        <a href="<?= url_to('admin_classrooms') ?>" class="admin-card">
            <span class="icon">🏫</span>
            <h3>Turmas</h3>
            <p>Gerenciar turmas e salas de aula.</p>
        </a>
    </div>
</div>

<?= $this->endSection(); ?>