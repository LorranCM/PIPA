import { renderDocuments } from "./display_documents.js";

export function loadCreateDocumentButton(classroom_id) {

    const container = document.getElementById('documents-wrapper');

    if (!container) {
        console.error(`Container '${containerId}' não encontrado.`);
        return;
    }

    let modal = document.getElementById("create-document-modal");

    if (!modal) {

        modal = document.createElement("div");
        modal.id = "create-document-modal";
        modal.classList.add("modal");

        modal.innerHTML = `
    <div class="modal-content document-modal-content">

        <form id="create-document-form">

            <h2>Novo Documento</h2>

            <input
                type="text"
                id="document-title"
                name="title"
                placeholder="Título do documento"
                required
            >

            <textarea
                id="document-description"
                name="description"
                placeholder="Descrição do documento"
                rows="6"
            ></textarea>

            <div class="file-upload-area">
                <label for="document-file">
                    Selecionar arquivo
                </label>

                <input
                    type="file"
                    id="document-file"
                    name="file"
                    hidden
                    required
                >
            </div>

            <div class="document-modal-actions">

                <button
                    type="submit"
                    id="submit-document"
                >
                    Enviar
                </button>

                <button
                    type="button"
                    id="close-create-document-modal"
                >
                    Cancelar
                </button>

            </div>

        </form>

    </div>
`;

        document.body.appendChild(modal);

        modal.addEventListener("click", (event) => {

            if (event.target === modal) {
                modal.style.display = "none";
            }

        });

        modal
            .querySelector("#close-create-document-modal")
            .addEventListener("click", () => {

                modal.style.display = "none";

            });

    }

    const createDocumentButton = document.createElement("button");

    createDocumentButton.type = "button";
    createDocumentButton.classList.add("document-create-card");
    createDocumentButton.id = "add-document";

    createDocumentButton.innerHTML = `
        <div class="plus-icon">+</div>
        <span>Novo documento</span>
    `;

    createDocumentButton.addEventListener("click", () => {
        modal.style.display = "flex";
    });

    container.prepend(createDocumentButton);

    const form = document.getElementById("create-document-form");

    if (!form) return;

    form.addEventListener("submit", async (event) => {

        event.preventDefault();

        const formData = new FormData(form);
        const statusModal = createUploadStatusModal();

        statusModal.style.display = "flex";
        document.getElementById(
                "create-document-modal"
        ).style.display = "none";

        document.getElementById(
            "upload-status-title"
        ).textContent = "Enviando documento...";

        document.getElementById(
            "upload-status-message"
        ).textContent =
            "Aguarde enquanto processamos seu arquivo.";

        document.getElementById(
            "upload-status-loader"
        ).style.display = "block";

        document.getElementById(
            "upload-status-close"
        ).style.display = "none";
        
        formData.append('classroom_id', classroom_id);

        const response = await fetch(baseUrl + "/requires/upload_document", {
            method: "POST",
            body: formData
        });

        const data = await response.json();

        if (data.success) {

                document.getElementById(
                    "create-document-modal"
                ).style.display = "none";

                document.getElementById(
                    "upload-status-title"
                ).textContent = "Envio concluído";

                document.getElementById(
                    "upload-status-message"
                ).textContent =
                    "O documento foi enviado com sucesso.";

                document.getElementById(
                    "upload-status-loader"
                ).style.display = "none";

                document.getElementById(
                    "upload-status-close"
                ).style.display = "block";

                renderDocuments(classroom_id);
            
        };

    });

    const fileInput = modal.querySelector("#document-file");
const fileLabel = modal.querySelector("label[for='document-file']");

fileInput.addEventListener("change", () => {

    if (fileInput.files.length > 0) {

        fileLabel.textContent = fileInput.files[0].name;

    } else {

        fileLabel.textContent = "Selecionar arquivo";

    }

});

}

function createUploadStatusModal() {

    let modal = document.getElementById("upload-status-modal");

    if (modal) return modal;

    modal = document.createElement("div");

    modal.id = "upload-status-modal";
    modal.classList.add("modal");

    modal.innerHTML = `
        <div class="modal-content">

            <h2 id="upload-status-title">
                Enviando documento...
            </h2>

            <p id="upload-status-message">
                Aguarde enquanto processamos seu arquivo.
            </p>

            <div
                id="upload-status-loader"
                class="loader"
            ></div>

            <button
                id="upload-status-close"
                type="button"
                style="display:none;"
            >
                Fechar
            </button>

        </div>
    `;

    document.body.appendChild(modal);

    modal
        .querySelector("#upload-status-close")
        .addEventListener("click", () => {

            modal.style.display = "none";

        });

    return modal;
}