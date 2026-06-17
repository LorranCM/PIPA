<?php echo $this->extend('master'); ?>

<!-- 
#--------------------------------------------------------------------
#   Estilos
#--------------------------------------------------------------------
-->

<?= $this->section('styles-ref'); ?>
    <link rel="stylesheet" href="<?= base_url("assets/css/admin_view.css") ?>">
<?= $this->endSection(); ?>

<!-- 
#--------------------------------------------------------------------
#   Scripts
#--------------------------------------------------------------------
-->

<?= $this->section('scripts-ref'); ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('deleteModal');
        const deleteForm = document.getElementById('deleteForm');
        const confirmBtn = document.getElementById('confirmDeleteBtn');
        const cancelBtn = document.getElementById('cancelBtn');

        // Seleciona todos os botões de exclusão na página
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function() {
                // Pega a URL de exclusão específica do atributo data-url (se houver) 
                // ou concatena o ID base com o data-id
                const itemId = this.getAttribute('data-id');
                
                // IMPORTANTE: Ajuste este base_url conforme a página atual
                // Ou melhor: defina o link completo diretamente no botão com data-url
                const actionUrl = this.getAttribute('data-url') || "<?= base_url('my/admin/delete/') ?>" + itemId;
                
                deleteForm.action = actionUrl;
                modal.classList.add('show');
            });
        });

        if (confirmBtn) {
            confirmBtn.addEventListener('click', () => deleteForm.submit());
        }

        if (cancelBtn) {
            cancelBtn.onclick = () => modal.classList.remove('show');
        }

        // Fecha se clicar fora do conteúdo
        window.onclick = (event) => {
            if (event.target === modal) modal.classList.remove('show');
        };
    });
</script>

<?= $this->endSection(); ?>

<!-- 
#--------------------------------------------------------------------
#   Conteúdo
#--------------------------------------------------------------------
-->

<?= $this->section('content'); ?>

<a href="<?= url_to('admin_create_classroom_form') ?>" class="fab-button" title="Criar nova sala">
    +
</a>

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

<div class="items-box">
    <ul class="items-list">
        <?php foreach ($items ?? [] as $item): ?>
            <li class="item-row">
                <a class="item-link" href="<?= base_url('my/admin/manage_classroom/') . $item['id'] ?>">
                    <?= $item['display'] ?>
                </a>

                <button class="delete-btn" 
                    data-id="<?= $item['id'] ?>" 
                    data-url="<?= base_url('my/admin/delete_classroom/') . $item['id'] ?>" 
                    title="Excluir sala">
                    
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 6h18"></path>
                        <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                        <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                    </svg>
                </button>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<div id="deleteModal" class="modal">
    <div class="modal-content">
        <h3>Confirmar Exclusão</h3>
        <p>Você tem certeza que deseja excluir esta sala de aula? Esta ação não pode ser desfeita.</p>
        <div class="modal-actions">
            <button type="button" id="cancelBtn" class="btn-secondary">Cancelar</button>
            <button type="button" id="confirmDeleteBtn" class="btn-danger">Excluir</button>
        </div>
    </div>
</div>

<form id="deleteForm" method="POST" action="" style="display: none;">
    <?= csrf_field() ?>
</form>

<?= $this->endSection(); ?>