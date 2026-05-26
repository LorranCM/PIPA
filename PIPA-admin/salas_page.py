# salas_page.py

import tkinter as tk

from tkinter import messagebox

from firestore_service import obter_salas

from criar_sala_window import abrir_criar_sala

from visualizar_sala_window import abrir_visualizar_sala

from excluir_sala_service import excluir_sala


# referência da janela
janela_salas = None


def abrir_salas(root):

    global janela_salas

    # previne duplicatas
    if (
        janela_salas is not None
        and janela_salas.winfo_exists()
    ):
        janela_salas.focus()
        return

    # cria janela
    janela_salas = tk.Toplevel(root)

    janela_salas.title("Salas")

    janela_salas.geometry("500x600")

    # barra superior
    topbar = tk.Frame(
        janela_salas
    )

    topbar.pack(
        fill="x",
        pady=10
    )

    # título
    titulo = tk.Label(
        topbar,
        text="Salas",
        font=("Arial", 18, "bold")
    )

    titulo.pack(
        side="left",
        padx=10
    )

    # botão refresh
    btn_refresh = tk.Button(
        topbar,
        text="↻",
        font=("Arial", 12, "bold"),
        command=lambda: (
            janela_salas.destroy(),
            abrir_salas(root)
        )
    )

    btn_refresh.pack(
        side="right",
        padx=10
    )

    # container principal
    container = tk.Frame(
        janela_salas,
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

    # frame lista
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

    # ajusta largura
    def ajustar_largura(event):

        canvas.itemconfig(
            lista_window,
            width=event.width
        )

    canvas.bind(
        "<Configure>",
        ajustar_largura
    )

    # busca salas
    salas = obter_salas()

    # cria elementos
    for sala in salas:

        sala_id = sala["id"]

        # linha
        linha = tk.Frame(
            frame_lista
        )

        linha.pack(
            fill="x"
        )

        # botão sala
        btn_sala = tk.Button(
            linha,
            text=sala_id,
            anchor="w",
            relief="flat",
            bd=0,
            padx=10,
            pady=10,
            command=lambda s=sala: abrir_visualizar_sala(root, s)
        )

        btn_sala.pack(
            side="left",
            fill="x",
            expand=True
        )

        # confirmação
        def confirmar_exclusao(sala_uid):

            resposta = messagebox.askyesno(
                "Excluir sala",
                f"Tem certeza que deseja excluir a sala de id {sala_uid}?"
            )

            if resposta:

                excluir_sala(sala_uid)

                messagebox.showinfo(
                    "Sucesso",
                    "Sala excluída."
                )

                janela_salas.destroy()

                abrir_salas(root)

        # botão excluir
        btn_excluir = tk.Button(
            linha,
            text="🗑",
            relief="flat",
            bd=0,
            padx=10,
            command=lambda u=sala_id: confirmar_exclusao(u)
        )

        btn_excluir.pack(
            side="right"
        )

    # botão +
    btn_add = tk.Button(
        janela_salas,
        text="+",
        font=("Arial", 20, "bold"),
        width=3,
        height=1,
        command=lambda: abrir_criar_sala(root)
    )

    btn_add.place(
        relx=0.92,
        rely=0.92,
        anchor="center"
    )