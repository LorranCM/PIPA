<?php echo $this->extend('master'); ?>

<?= $this->section('styles-ref'); ?>
    <link rel="stylesheet" href="<?= base_url("assets/css/user_form.css") ?>">
<?= $this->endSection(); ?>

<?= $this->section('scripts-ref'); ?>

    <script>

    document.addEventListener("DOMContentLoaded", function () {

    // 1. Função global para adicionar novos campos de sala
    window.addClassroom = function () {
        const container = document.getElementById("classrooms-container");
        if (!container) return;

        const div = document.createElement("div");
        div.className = "classroom-item";

        div.innerHTML = `
            <input type="text" name="classrooms[]" placeholder="ID da sala" value="">
            <button type="button" class="remove-btn" title="Remover sala">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"></path><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path></svg>
            </button>
        `;

        container.appendChild(div);
    };

    // 2. Escuta cliques no documento para remover a linha de salas
    document.addEventListener("click", function (e) {
        const removeBtn = e.target.closest(".remove-btn");
        if (removeBtn) {
            removeBtn.parentElement.remove();
        }
    });

    // 3. Mapeamento dos elementos para controle de exibição dinâmica
    const roleSelect = document.getElementById("role");
    const sectionAvailability = document.getElementById("section-availability");
    const sectionAcademic = document.getElementById("section-academic");
    const groupCourse = document.getElementById("group-course");
    const sectionClassrooms = document.getElementById("section-classrooms");
    
    // Seletores para ocultar Nome e Sobrenome na role Admin
    const groupName = document.getElementById("group-name");
    const groupLastname = document.getElementById("group-lastname");

    const checkboxes = document.querySelectorAll('.day-checkbox');
    checkboxes.forEach(cb => {
        cb.addEventListener('change', function() {
            const input = this.closest('.availability-item').querySelector('input[type="text"]');
            input.disabled = !this.checked; // Se não estiver marcado, desabilita o input
        });
    });

    function handleRoleChange() {
            if (!roleSelect) return;
            
            const selectedRole = roleSelect.value;

            // Agora Nome e Sobrenome SEMPRE aparecem, independentemente do papel
            if (groupName) groupName.style.display = "block";
            if (groupLastname) groupLastname.style.display = "block";

            // Reset padrão: Esconde as seções condicionais
            if (sectionAvailability) sectionAvailability.style.display = "none";
            if (sectionAcademic) sectionAcademic.style.display = "none";
            if (groupCourse) groupCourse.style.display = "none";
            if (sectionClassrooms) sectionClassrooms.style.display = "none";

            // Aplica as regras específicas apenas para o que for aparecer extra
            if (selectedRole === "student") {
                if (sectionAcademic) sectionAcademic.style.display = "block";
                if (groupCourse) groupCourse.style.display = "block";
                if (sectionClassrooms) sectionClassrooms.style.display = "block";
            } else if (selectedRole === "teacher") {
                if (sectionAvailability) sectionAvailability.style.display = "block";
                if (sectionAcademic) sectionAcademic.style.display = "block";
                if (sectionClassrooms) sectionClassrooms.style.display = "block";
            }
        }

    // Registra o evento de mudança e executa a primeira vez no carregamento da página
    if (roleSelect) {
        roleSelect.addEventListener("change", handleRoleChange);
        handleRoleChange();
    }
});
    </script>

<?= $this->endSection(); ?>

<?= $this->section('content'); ?>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
        <span><?= session()->getFlashdata('success') ?></span>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-error">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
        <span><?= session()->getFlashdata('error') ?></span>
    </div>
<?php endif; ?>

<form method="post" action="<?= base_url('my/admin/store_user') ?>" class="modern-form">
    
    <header class="form-header">
        <h2>Cadastrar Novo Usuário</h2>
        <p>Preencha os dados abaixo para registrar o perfil no sistema.</p>
    </header>

   <fieldset class="form-section">
        <legend>Informações Gerais</legend>
        
        <div class="form-grid">
            <div class="form-group" id="group-name">
                <label for="name">Nome</label>
                <input type="text" id="name" name="name" placeholder="Ex: Fulano" value="<?= old('name') ?>">
            </div>

            <div class="form-group" id="group-lastname">
                <label for="lastname">Sobrenome</label>
                <input type="text" id="lastname" name="lastname" placeholder="Ex: de Tal" value="<?= old('lastname') ?>">
            </div>

            <div class="form-group">
                <label for="registration">Matrícula</label>
                <input type="text" id="registration" name="registration" placeholder="Ex: alunoex" value="<?= old('registration') ?>">
            </div>

            <div class="form-group">
                <label for="role">Papel (Role)</label>
                <select id="role" name="role">
                    <option value="student" <?= old('role') === 'student' ? 'selected' : '' ?>>Estudante (Student)</option>
                    <option value="teacher" <?= old('role') === 'teacher' ? 'selected' : '' ?>>Professor (Teacher)</option>
                    <option value="admin" <?= old('role') === 'admin' ? 'selected' : '' ?>>Administrador (Admin)</option>
                </select>
            </div>

            <div class="form-group">
                <label for="password">Senha de Acesso</label>
                <input type="password" id="password" name="password" placeholder="Digite uma senha segura" required>
            </div>
        </div>
    </fieldset>

    <fieldset id="section-availability" class="form-section" style="display: none;">
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
                    $oldActive = old("availability_active.{$key}");
                    $oldTime = old("availability.{$key}");
                ?>
                <div class="availability-item">
                    <label class="checkbox-label">
                        <input type="checkbox" name="availability_active[<?= $key ?>]" value="1" <?= $oldActive ? 'checked' : '' ?>>
                        <span class="day-name"><?= $label ?></span>
                    </label>
                    
                    <input type="text" name="availability[<?= $key ?>]" placeholder="Ex: 08:00-12:00" 
                        value="<?= esc($oldTime ?? '') ?>"
                        pattern="^([0-1]?[0-9]|2[0-3]):[0-5][0-9]-([0-1]?[0-9]|2[0-3]):[0-5][0-9]$"
                        title="Formato exigido: XX:XX-YY:YY (Ex: 14:00-18:00)">
                </div>
            <?php endforeach; ?>
        </div>
    </fieldset>

    <fieldset id="section-academic" class="form-section" style="display: none;">
        <legend>Dados Acadêmicos</legend>
        
        <div class="form-grid">
            <div id="group-course" class="form-group grid-col-span-2" style="display: none;">
                <label for="course">Curso</label>
                <input type="text" id="course" name="course" placeholder="Ex: Informática para Internet" value="<?= old('course') ?>">
            </div>

            <div class="form-group">
                <label for="entry-semester">Semestre de Entrada</label>
                <input type="text" id="entry-semester" name="entry-semester" placeholder="Ex: 2023/2" value="<?= old('entry-semester') ?>">
            </div>

            <div class="form-group">
                <label for="registration-status">Status da Matrícula</label>
                <input type="text" id="registration-status" name="registration-status" placeholder="Ex: active" value="<?= old('registration-status') ?>">
            </div>
        </div>
    </fieldset>

    <fieldset id="section-classrooms" class="form-section" style="display: none;">
        <legend>Salas de Aula (Classrooms)</legend>
        
        <div id="classrooms-container" class="classrooms-list">
            <?php if (is_array(old('classrooms'))): ?>
                <?php foreach (old('classrooms') as $room): ?>
                    <div class="classroom-item">
                        <input type="text" name="classrooms[]" placeholder="ID da sala" value="<?= esc($room) ?>">
                        <button type="button" class="remove-btn" title="Remover sala">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"></path><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path></svg>
                        </button>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <button type="button" onclick="addClassroom()" class="btn-secondary">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            Adicionar sala
        </button>
    </fieldset>

    <footer class="form-actions">
        <button type="submit" class="btn-primary">Cadastrar Usuário</button>
    </footer>
</form>

<?= $this->endSection(); ?>