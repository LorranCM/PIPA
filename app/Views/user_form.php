<?php echo $this->extend('master'); ?>

<!-- 
#--------------------------------------------------------------------
#   Estilos
#--------------------------------------------------------------------
-->

<?= $this->section('styles-ref'); ?>

    <link rel="stylesheet" href=<?= base_url("assets/css/user_form.css") ?>>

<?= $this->endSection(); ?>

<!-- 
#--------------------------------------------------------------------
#   Script
#--------------------------------------------------------------------
-->
<?= $this->section('scripts-ref'); ?>

    <script>
    document.addEventListener("DOMContentLoaded", function () {

        // Função global para adicionar novos campos de sala
        window.addClassroom = function () {
            const container = document.getElementById("classrooms-container");
            if (!container) return;

            const div = document.createElement("div");
            div.className = "classroom-item";

            // Nova estrutura mantendo o padrão visual e o ícone SVG
            div.innerHTML = `
                <input type="text" name="classrooms[]" placeholder="ID da sala" value="">
                <button type="button" class="remove-btn" title="Remover sala">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"></path><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path></svg>
                </button>
            `;

            container.appendChild(div);
        };

        // Escuta cliques no documento para remover a linha
        document.addEventListener("click", function (e) {
            // O closest garante que se o clique for no SVG/Path dentro do botão, ainda funcione
            const removeBtn = e.target.closest(".remove-btn");
            if (removeBtn) {
                removeBtn.parentElement.remove();
            }
        });

    });
    </script>

<?= $this->endSection(); ?>

<!-- 
#--------------------------------------------------------------------
#   Conteúdo
#--------------------------------------------------------------------
-->

<?= $this->section('content'); ?>

<form method="post" action="<?= base_url('my/admin/update_user/' . ($id ?? "")) ?>" class="modern-form">
    
    <header class="form-header">
        <h2>Editar Perfil do Usuário</h2>

        <p>Atualize as informações acadêmicas e de registro.</p>
        <p>ID de usuário: <?= $id ?? "" ?><p>
    </header>

    <fieldset class="form-section">
        <legend>Informações Gerais</legend>
        
        <div class="form-grid">
            <div class="form-group">
                <label for="name">Nome</label>
                <input type="text" id="name" name="name" placeholder="Ex: Fulano" value="<?= esc($user['name'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="lastname">Sobrenome</label>
                <input type="text" id="lastname" name="lastname" placeholder="Ex: de Tal" value="<?= esc($user['lastname'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="registration">Matrícula</label>
                <input type="text" id="registration" name="registration" placeholder="Ex: alunoex" value="<?= esc($user['registration'] ?? '') ?>">
            </div>
            
            </div>
    </fieldset>

    <?php if (($user['role'] ?? '') === 'teacher'): ?>
    <fieldset class="form-section">
        <legend>Disponibilidade (Horários)</legend>
        
        <?php 
        $diasSemana = [
            'monday'    => 'Segunda-feira',
            'tuesday'   => 'Terça-feira',
            'wednesday' => 'Quarta-feira',
            'thursday'  => 'Quinta-feira',
            'friday'    => 'Sexta-feira',
            'saturday'  => 'Sábado',
            'sunday'    => 'Domingo'
        ];
        ?>

        <div class="availability-list">
            <?php foreach ($diasSemana as $key => $label): ?>
                <?php 
                    // Pega o valor do horário no map (ex: "11:00-12:00") se ele existir
                    $timeRange = $user['availability'][$key] ?? '';
                    // Se tiver horário, marca a checkbox como checked
                    $isChecked = !empty($timeRange) ? 'checked' : '';
                ?>
                <div class="availability-item">
                    <label class="checkbox-label">
                        <input type="checkbox" name="availability_active[<?= $key ?>]" value="1" <?= $isChecked ?>>
                        <span class="day-name"><?= $label ?></span>
                    </label>
                    
                    <input type="text" name="availability[<?= $key ?>]" placeholder="Ex: 08:00-12:00" 
                        value="<?= esc($timeRange) ?>"
                        pattern="^([0-1]?[0-9]|2[0-3]):[0-5][0-9]-([0-1]?[0-9]|2[0-3]):[0-5][0-9]$"
                        title="Formato exigido: XX:XX-YY:YY (Ex: 14:00-18:00)">
                </div>
            <?php endforeach; ?>
        </div>
    </fieldset>
    <?php endif; ?>

    <fieldset class="form-section">
    <legend>Dados Acadêmicos</legend>
    
    <div class="form-grid">
        <?php if (($user['role'] ?? '') !== 'teacher'): ?>
            <div class="form-group grid-col-span-2">
                <label for="course">Curso</label>
                <input type="text" id="course" name="course" placeholder="Ex: Informática para Internet" value="<?= esc($user['course'] ?? '') ?>">
            </div>
        <?php endif; ?>

        <div class="form-group">
            <label for="entry-semester">Semestre de Entrada</label>
            <input type="text" id="entry-semester" name="entry-semester" placeholder="Ex: 2023/2" value="<?= esc($user['entry-semester'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="registration-status">Status da Matrícula</label>
            <input type="text" id="registration-status" name="registration-status" placeholder="Ex: active" value="<?= esc($user['registration-status'] ?? '') ?>">
        </div>
        </div>
    </fieldset>

    <fieldset class="form-section">
        <legend>Salas de Aula (Classrooms)</legend>
        
        <div id="classrooms-container" class="classrooms-list">
            <?php foreach (($user['classrooms'] ?? []) as $room): ?>
                <div class="classroom-item">
                    <input type="text" name="classrooms[]" placeholder="ID da sala" value="<?= esc($room) ?>">
                    <button type="button" class="remove-btn" title="Remover sala">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"></path><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path></svg>
                    </button>
                </div>
            <?php endforeach; ?>
        </div>

        <button type="button" onclick="addClassroom()" class="btn-secondary">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            Adicionar sala
        </button>
    </fieldset>

    <footer class="form-actions">
        <button type="submit" class="btn-primary">Salvar Alterações</button>
    </footer>
</form>

<?= $this->endSection(); ?>
