# criar_sala_window.py

import tkinter as tk

from tkinter import messagebox
from criar_sala_service import criar_sala

# referência da janela
janela_criar_sala = None


def abrir_criar_sala(root):

    global janela_criar_sala

    # previne duplicatas
    if (
        janela_criar_sala is not None
        and janela_criar_sala.winfo_exists()
    ):
        janela_criar_sala.focus()
        return

    # cria janela
    janela_criar_sala = tk.Toplevel(root)

    janela_criar_sala.title("Criar Sala")

    janela_criar_sala.geometry("500x350")

    # container
    frame = tk.Frame(
        janela_criar_sala
    )

    frame.pack(
        fill="both",
        expand=True,
        padx=20,
        pady=20
    )

    # utilitário
    def criar_campo(label_text):

        label = tk.Label(
            frame,
            text=label_text
        )

        label.pack(
            anchor="w",
            pady=(10, 0)
        )

        entry = tk.Entry(
            frame
        )

        entry.pack(
            fill="x"
        )

        return entry

    # campos
    entry_disciplina = criar_campo(
        "Nome da disciplina"
    )

    entry_professor = criar_campo(
        "Professor"
    )

    # submit
    def submit():

        disciplina = entry_disciplina.get().strip()

        professor = entry_professor.get().strip()

        # validação
        if (
            disciplina == ""
            or professor == ""
        ):

            messagebox.showerror(
                "Erro",
                "Preencha todos os campos."
            )

            return

        # dados coletados
        dados_sala = {

            "curricular-unit": disciplina,

            "tenured-teacher": professor
        }

        # temporário
        sala_id = criar_sala(dados_sala)

        messagebox.showinfo(
            "Sucesso",
            f"Sala criada.\nID: {sala_id}"
        )

        janela_criar_sala.destroy()

    # botão submit
    btn_submit = tk.Button(
        frame,
        text="Criar Sala",
        height=2,
        command=submit
    )

    btn_submit.pack(
        fill="x",
        pady=20
    )