<?php echo $this->extend('master'); ?>

<?= $this->section('styles-ref'); ?>
    <link rel="stylesheet" href="<?= base_url("assets/css/user_form.css") ?>">
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>

<form method="post" action="<?= base_url('my/admin/update_classroom/' . ($id ?? "")) ?>" class="modern-form">
    <?= csrf_field() ?>
    
    <header class="form-header">
        <h2>Editar Sala de Aula</h2>
        <p>Atualize as informações da unidade curricular e responsabilidade.</p>
        <p>ID de sala: <?= $id ?? "" ?><p>
    </header>

    <fieldset class="form-section">
        <legend>Dados da Sala</legend>
        
        <div class="form-grid">
            <div class="form-group grid-col-span-2">
                <label for="curricular_unit">Unidade Curricular</label>
                <input type="text" id="curricular_unit" name="curricular_unit" 
                       placeholder="Ex: Algoritmos e Estruturas de Dados" 
                       value="<?= esc($classroom['curricular-unit'] ?? '') ?>" required>
            </div>

            <div class="form-group grid-col-span-2">
                <label for="tenured_teacher">ID do Professor Titular</label>
                <input type="text" id="tenured_teacher" name="tenured_teacher" 
                       placeholder="ID do docente responsável" 
                       value="<?= esc($classroom['tenured-teacher'] ?? '') ?>" required>
            </div>
        </div>
    </fieldset>

    <footer class="form-actions">
        <button type="submit" class="btn-primary">Salvar Alterações</button>
    </footer>
</form>

<?= $this->endSection(); ?>