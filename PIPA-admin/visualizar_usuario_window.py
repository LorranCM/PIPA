# visualizar_usuario_window.py

import tkinter as tk


# referência da janela
janela_visualizar_usuario = None


def abrir_visualizar_usuario(root, usuario):

    global janela_visualizar_usuario

    # previne duplicatas
    if (
        janela_visualizar_usuario is not None
        and janela_visualizar_usuario.winfo_exists()
    ):
        janela_visualizar_usuario.focus()
        return

    janela_visualizar_usuario = tk.Toplevel(root)

    janela_visualizar_usuario.title(
        f"Usuário {usuario['id']}"
    )

    janela_visualizar_usuario.geometry("500x600")

    # container principal
    container = tk.Frame(
        janela_visualizar_usuario
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
        text="Dados do Usuário",
        font=("Arial", 18, "bold")
    )

    titulo.pack(
        anchor="w",
        pady=(0, 20)
    )

    dados = usuario["data"]

    # função utilitária
    def criar_linha(campo, valor):

        frame = tk.Frame(container)

        frame.pack(
            fill="x",
            pady=5
        )

        label_campo = tk.Label(
            frame,
            text=f"{campo}:",
            font=("Arial", 10, "bold"),
            width=18,
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

    # dados básicos
    criar_linha("ID", usuario["id"])

    criar_linha(
        "Nome",
        dados.get("name", "")
    )

    criar_linha(
        "Sobrenome",
        dados.get("lastname", "")
    )

    criar_linha(
        "Número",
        dados.get("contact-number", "")
    )

    criar_linha(
        "Email",
        dados.get("email", "")
    )

    criar_linha(
        "Função",
        dados.get("role", "")
    )

    criar_linha(
        "Matrícula",
        dados.get("registration", "")
    )

    # salas
    salas = dados.get(
        "classrooms",
        []
    )

    criar_linha(
        "Salas",
        ", ".join(salas)
    )