# visualizar_sala_window.py

import tkinter as tk


# referência da janela
janela_visualizar_sala = None


def abrir_visualizar_sala(root, sala):

    global janela_visualizar_sala

    # previne duplicatas
    if (
        janela_visualizar_sala is not None
        and janela_visualizar_sala.winfo_exists()
    ):
        janela_visualizar_sala.focus()
        return

    # cria janela
    janela_visualizar_sala = tk.Toplevel(root)

    janela_visualizar_sala.title(
        f"Sala {sala['id']}"
    )

    janela_visualizar_sala.geometry("500x400")

    # container principal
    container = tk.Frame(
        janela_visualizar_sala
    )

    container.pack(
        fill="both",
        expand=True,
        padx=20,
        pady=20
    )

    # título
    titulo = tk.Label(
        container,
        text="Dados da Sala",
        font=("Arial", 18, "bold")
    )

    titulo.pack(
        anchor="w",
        pady=(0, 20)
    )

    dados = sala["data"]

    # utilitário
    def criar_linha(campo, valor):

        frame = tk.Frame(
            container
        )

        frame.pack(
            fill="x",
            pady=5
        )

        label_campo = tk.Label(
            frame,
            text=f"{campo}:",
            font=("Arial", 10, "bold"),
            width=20,
            anchor="w"
        )

        label_campo.pack(
            side="left"
        )

        label_valor = tk.Label(
            frame,
            text=str(valor),
            anchor="w",
            justify="left",
            wraplength=250
        )

        label_valor.pack(
            side="left",
            fill="x",
            expand=True
        )

    # dados
    criar_linha(
        "ID",
        sala["id"]
    )

    criar_linha(
        "Disciplina",
        dados.get(
            "curricular-unit",
            ""
        )
    )

    criar_linha(
        "Professor",
        dados.get(
            "tenured-teacher",
            ""
        )
    )