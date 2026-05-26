# usuarios_page.py

import tkinter as tk
from tkinter import messagebox

from firestore_service import obter_usuarios
from criar_usuario_window import abrir_criar_usuario
from excluir_usuario_service import excluir_usuario
from visualizar_usuario_window import abrir_visualizar_usuario

# referência da janela
janela_usuarios = None


def abrir_usuarios(root):

    global janela_usuarios

    # impede duplicatas
    if janela_usuarios is not None and janela_usuarios.winfo_exists():
        janela_usuarios.focus()
        return

    # cria janela
    janela_usuarios = tk.Toplevel(root)

    janela_usuarios.title("Usuários")
    janela_usuarios.geometry("500x600")

    # barra superior
    topbar = tk.Frame(janela_usuarios)

    topbar.pack(
        fill="x",
        pady=10
    )

    # título
    titulo = tk.Label(
        topbar,
        text="Usuários",
        font=("Arial", 18, "bold")
    )

    titulo.pack(
        side="left",
        padx=10
    )

    btn_refresh = tk.Button(
    topbar,
    text="↻",
    font=("Arial", 12, "bold"),
    command=lambda: (
        janela_usuarios.destroy(),
        abrir_usuarios(root)
    )
    )

    btn_refresh.pack(
        side="right",
        padx=10
    )

    # container principal
    container = tk.Frame(
        janela_usuarios,
        bd=1,
        relief="solid",
        bg="#bdbdbd"
    )

    container.pack(
        fill="both",
        expand=True,
        padx=20,
        pady=10
    )

    # canvas
    canvas = tk.Canvas(
        container,
        highlightthickness=0,
        bd=0
    )

    # scrollbar
    scrollbar = tk.Scrollbar(
        container,
        orient="vertical",
        command=canvas.yview
    )

    # frame da lista
    frame_lista = tk.Frame(
        canvas
    )

    frame_lista.bind(
        "<Configure>",
        lambda e: canvas.configure(
            scrollregion=canvas.bbox("all")
        )
    )

    lista_window = canvas.create_window(
        (0, 0),
        window=frame_lista,
        anchor="nw"
    )

    canvas.configure(
        yscrollcommand=scrollbar.set
    )

    canvas.pack(
        side="left",
        fill="both",
        expand=True
    )

    scrollbar.pack(
        side="right",
        fill="y"
    )

    # ajusta largura da lista
    def ajustar_largura(event):

        canvas.itemconfig(
            lista_window,
            width=event.width
        )

    canvas.bind(
        "<Configure>",
        ajustar_largura
    )

    # busca usuários
    usuarios = obter_usuarios()

    # cria elementos da lista
    # cria elementos da lista
    for usuario in usuarios:

        uid = usuario["id"]

        # linha
        linha = tk.Frame(
            frame_lista,
            bd=0
        )

        linha.pack(
            fill="x",
            padx=0,
            pady=0
        )

        # botão do usuário
        btn_usuario = tk.Button(
            linha,
            text=uid,
            anchor="w",
            relief="flat",
            bd=0,
            padx=10,
            pady=10,
            command=lambda u=usuario: abrir_visualizar_usuario(root, u)
        )

        btn_usuario.pack(
            side="left",
            fill="x",
            expand=True
        )

        # confirmação
        def confirmar_exclusao(user_uid):

            resposta = messagebox.askyesno(
                "Excluir usuário",
                f"Tem certeza que deseja excluir o usuário de id {user_uid}?"
            )

            if resposta:

                excluir_usuario(user_uid)

                messagebox.showinfo(
                    "Sucesso",
                    "Usuário excluído."
                )

                # refresh
                janela_usuarios.destroy()

                abrir_usuarios(root)

        # botão excluir
        btn_excluir = tk.Button(
            linha,
            text="🗑",
            relief="flat",
            bd=0,
            padx=10,
            command=lambda u=uid: confirmar_exclusao(u)
        )

        btn_excluir.pack(
            side="right"
        )

    # botão "+"
    btn_add = tk.Button(
    janela_usuarios,
    text="+",
    font=("Arial", 20, "bold"),
    width=3,
    height=1,
    command=lambda: abrir_criar_usuario(root)
)

    btn_add.place(
        relx=0.92,
        rely=0.92,
        anchor="center"
    )

