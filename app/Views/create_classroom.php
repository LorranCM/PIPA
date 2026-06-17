<?php echo $this->extend('master'); ?>

<?= $this->section('styles-ref'); ?>
    <link rel="stylesheet" href="<?= base_url("assets/css/user_form.css") ?>">
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>

<div class="modern-form">
    <h2>Criar Nova Sala</h2>
    
    <form action="<?= url_to('store_classroom') ?>" method="POST">
        <?= csrf_field() ?>

        <div class="form-group">
            <label for="curricular-unit">UNIDADE CURRICULAR</label>
            <input type="text" id="curricular-unit" name="curricular_unit" placeholder="Ex: Matemática Aplicada" required>
        </div>

        <div class="form-group">
            <label for="tenured-teacher">ID DO PROFESSOR TITULAR</label>
            <input type="text" id="tenured-teacher" name="tenured_teacher" placeholder="Cole o ID do professor" required>
        </div>

        <footer class="form-actions">
            <button type="submit" class="btn-primary">Criar Sala</button>
        </footer>
    </form>
</div>

<?= $this->endSection(); ?>