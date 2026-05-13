let enable_close = true;

export function toggle_enable_close() {
    enable_close = !enable_close;
};

// Modal personalizado genérico
export function show_custom_modal(title, message, buttons = []) {
    // Remove modal anterior se existir
    const existingModal = document.getElementById('customModal');
    
    if (existingModal) {
        existingModal.remove();
    }
    
    // Cria o modal dinamicamente
    const modalHTML = `
    <div id="customModal" class="modal-overlay" style="display: flex;">
    <div class="modal-content">
    <span class="modal-close">&times;</span>
    <h3>${title}</h3>
    <p>${message}</p>
    <div class="modal-footer">
    ${buttons.map((btn, index) => 
        `<button class="modal-btn ${btn.class}" id="customBtn${index}">${btn.text}</button>`
    ).join('')}
    </div>
    </div>
    </div>
    `;
    
    // Adiciona o novo modal
    document.body.insertAdjacentHTML('beforeend', modalHTML);
    
    const modal = document.getElementById('customModal');
    
    modal.querySelector('.modal-close')
    .addEventListener('click', function() {
            if (!enable_close) return;
            close_custom_modal()
        }
    );
    
    modal.addEventListener('click', function(event) {
            if (!enable_close) return;

            if (event.target === modal) {
                close_custom_modal();
            }
        } 
    );

// Adiciona event listeners aos botões
buttons.forEach((btn, index) => {
    const buttonEl = document.getElementById(`customBtn${index}`);
    if (buttonEl && btn.onClick) {
        buttonEl.addEventListener('click', btn.onClick);
    }
});
}

export function close_custom_modal() {
    const modal = document.getElementById('customModal');
    
    if (modal) {
        modal.remove();
    }
}