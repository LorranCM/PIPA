let documentModal = null;
let currentDocument = null;
let currentClassroomId = null;
let currentrole = null;

export async function renderDocuments(classroom_id, role) {

    currentClassroomId = classroom_id;
    currentrole = role;

    const response = await fetch(
        baseUrl + "/requires/get_documents/" + classroom_id,
        { method: "POST" }
    );

    const data = await response.json();
    const documents = data.documents;

    const container = document.getElementById("documents-wrapper");

    // limpa apenas cards, mantém botão fixo
    Array.from(container.children).forEach(child => {
        if (child.id !== "add-document") {
            child.remove();
        }
    });

    ensureDocumentModal();

    Object.entries(documents).forEach(([link, doc]) => {
        const card = createDocumentCard(link, doc, container);
        container.insertBefore(card, container.firstChild);
    });
}

function ensureDocumentModal() {

    if (documentModal) return;

    documentModal = document.createElement("div");
    documentModal.classList.add("modal");

    let btn_delete = `<button id="btn-delete" style="background:red; color:white;">
                    Excluir
                </button>`
    
    if (currentrole === "student") {
        btn_delete = "";
    }

    documentModal.innerHTML = `
        <div class="modal-content">
            <h2 id="doc-title"></h2>
            <p id="doc-description"></p>

            <div style="display:flex; gap:10px; justify-content:center; flex-wrap:wrap;">

                <button id="btn-open">
                    Abrir documento
                </button>` + btn_delete + `
                <button id="btn-close">
                    Fechar
                </button>

            </div>
        </div>
    `;

    document.body.appendChild(documentModal);

    bindModalEvents();
}

function bindModalEvents() {

    const title = documentModal.querySelector("#doc-title");
    const description = documentModal.querySelector("#doc-description");

    const openBtn = documentModal.querySelector("#btn-open");
    const deleteBtn = documentModal.querySelector("#btn-delete");
    const closeBtn = documentModal.querySelector("#btn-close");

    closeBtn.onclick = () => {
        documentModal.style.display = "none";
        currentDocument = null;
    };

    openBtn.onclick = () => {
        if (!currentDocument) return;
        downloadImage(currentDocument.link, currentDocument.name);
    };

    if (currentrole !== "student") {
        deleteBtn.onclick = async () => {
            if (!currentDocument) return;
            await deleteDocument();
        };
    }
}

async function downloadImage(url, filename = "imagem.jpg") {
    try {
        const response = await fetch(url);
        const blob = await response.blob();

        const blobUrl = window.URL.createObjectURL(blob);

        const a = document.createElement("a");
        a.href = blobUrl;
        a.download = filename;

        document.body.appendChild(a);
        a.click();

        a.remove();
        window.URL.revokeObjectURL(blobUrl);

    } catch (error) {
        console.error("Erro ao baixar:", error);
    }
}

function createDocumentCard(link, doc, container) {

    const card = document.createElement("button");
    card.classList.add("card");

    card.innerHTML = `<strong>${doc.name}</strong>`;

    card.addEventListener("click", () => {

        currentDocument = {
            link,
            name: doc.name,
            description: doc.description ?? "Sem descrição"
        };

        openDocumentModal(currentDocument);
    });

    return card;
}

function openDocumentModal(doc) {

    const title = documentModal.querySelector("#doc-title");
    const description = documentModal.querySelector("#doc-description");

    title.textContent = doc.name;
    description.textContent = doc.description;

    documentModal.style.display = "flex";
}

function createConfirmModal(message) {

    const modal = document.createElement("div");
    modal.classList.add("modal");

    modal.innerHTML = `
        <div class="modal-content">
            <p>${message}</p>

            <div style="display:flex; gap:10px; justify-content:center;">
                <button id="yes" style="background:red; color:white;">Excluir</button>
                <button id="no">Cancelar</button>
            </div>
        </div>
    `;

    document.body.appendChild(modal);

    modal.style.display = "flex";

    return new Promise(resolve => {

        modal.querySelector("#yes").onclick = () => {
            modal.remove();
            resolve(true);
        };

        modal.querySelector("#no").onclick = () => {
            modal.remove();
            resolve(false);
        };
    });
}

function createLoadingModal(text = "Processando...") {
    const modal = document.createElement("div");
    modal.classList.add("modal");

    modal.innerHTML = `
        <div class="modal-content">
            <p>${text}</p>
        </div>
    `;

    document.body.appendChild(modal);
    modal.style.display = "flex"; 

    return modal;
}

async function deleteDocument() {
    const confirmed = await createConfirmModal(
        "Tem certeza que deseja excluir este documento?"
    );

    if (!confirmed) return;

    const loading = createLoadingModal("Excluindo documento...");

    try {
        const response = await fetch(
            baseUrl + "/requires/delete_document",
            {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    link: currentDocument.link,
                    classroom_id: currentClassroomId
                })
            }
        );

        const result = await response.json();

        loading.remove(); // Remove o loading assim que a resposta chega

        if (result.success) {
            documentModal.style.display = "none";
            currentDocument = null;

            // CORREÇÃO: Mantém o 'currentrole' ativo ao renderizar novamente
            console.log(result.ext);
            await renderDocuments(result.classroom_id, currentrole); 
        } else {
            alert("Não foi possível excluir o documento. Tente novamente.");
        }

    } catch (err) {
        loading.remove(); // Garante que o loading some se o servidor cair
        console.error(err);
        alert("Erro de conexão ao tentar excluir o documento.");
    }
}