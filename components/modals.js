// Modal personalizado genérico
export function show_custom_modal(title, message, buttons) {
    // Remove modal anterior se existir
    const existingModal = document.getElementById('customModal');
    if (existingModal) {
        existingModal.remove();
    }

    // Cria o modal dinamicamente
    const modalHTML = `
        <div id="customModal" class="modal-overlay" style="display: flex;">
            <div class="modal-content">
                <span class="modal-close" onclick="close_custom_modal()">&times;</span>
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

// Fechar modal ao clicar fora
window.onclick = function(event) {
    if (event.target === document.getElementById('customModal')) {
        close_custom_modal();
    }
}

function toggle_popup() {
    var popup = document.getElementById('popup-menu');
    popup.style.display = popup.style.display === 'grid' ? 'none' : 'grid';
}

function open_modal() {
    document.getElementById("modal").style.display = "flex";

    // Preenche com valores atuais
    inputNome.value = nome.innerText;
    inputSemestre.value = semestre.innerText;
    inputCurso.value = curso.innerText;
}

function close_modal() {
    document.getElementById("modal").style.display = "none";
}

window.onclick = function(event) {
    const modal = document.getElementById("modal");

    if (event.target === modal) {
        modal.style.display = "none";
    }
}

function save() {
    nome.innerText = inputNome.value;
    semestre.innerText = inputSemestre.value;
    curso.innerText = inputCurso.value;

    fecharModal();
}