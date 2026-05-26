# criar_usuario_window.py

import tkinter as tk

from tkinter import messagebox
from criar_usuario_service import criar_usuario


# referência da janela
janela_criar_usuario = None


def abrir_criar_usuario(root):

    global janela_criar_usuario

    # previne duplicatas
    if (
        janela_criar_usuario is not None
        and janela_criar_usuario.winfo_exists()
    ):
        janela_criar_usuario.focus()
        return

    # cria janela
    janela_criar_usuario = tk.Toplevel(root)

    janela_criar_usuario.title("Criar Usuário")
    janela_criar_usuario.geometry("500x750")

    # canvas principal
    canvas = tk.Canvas(
        janela_criar_usuario
    )

    scrollbar = tk.Scrollbar(
        janela_criar_usuario,
        orient="vertical",
        command=canvas.yview
    )

    frame = tk.Frame(canvas)

    frame.bind(
        "<Configure>",
        lambda e: canvas.configure(
            scrollregion=canvas.bbox("all")
        )
    )

    canvas.create_window(
        (0, 0),
        window=frame,
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

    # utilitário
    def criar_campo(label_text, password=False):

        label = tk.Label(
            frame,
            text=label_text
        )

        label.pack(
            anchor="w",
            pady=(10, 0)
        )

        entry = tk.Entry(
            frame,
            show="*" if password else ""
        )

        entry.pack(
            fill="x"
        )

        return entry

    # campos
    entry_nome = criar_campo("Nome")

    entry_sobrenome = criar_campo("Sobrenome")

    entry_senha = criar_campo(
        "Senha",
        password=True
    )

    entry_celular = criar_campo(
        "Número de celular"
    )

    entry_email = criar_campo(
        "Email"
    )

    entry_matricula = criar_campo(
        "Matrícula"
    )

    # título ids de salas
    label_salas = tk.Label(
        frame,
        text="IDs de salas"
    )

    label_salas.pack(
        anchor="w",
        pady=(15, 5)
    )

    # container com scroll
    salas_container = tk.Frame(
        frame,
        bd=1,
        relief="solid"
    )

    salas_container.pack(
        fill="x"
    )

    salas_canvas = tk.Canvas(
        salas_container,
        height=120,
        highlightthickness=0
    )

    salas_scrollbar = tk.Scrollbar(
        salas_container,
        orient="vertical",
        command=salas_canvas.yview
    )

    salas_frame = tk.Frame(
        salas_canvas
    )

    salas_frame.bind(
        "<Configure>",
        lambda e: salas_canvas.configure(
            scrollregion=salas_canvas.bbox("all")
        )
    )

    salas_canvas.create_window(
        (0, 0),
        window=salas_frame,
        anchor="nw"
    )

    salas_canvas.configure(
        yscrollcommand=salas_scrollbar.set
    )

    salas_canvas.pack(
        side="left",
        fill="both",
        expand=True
    )

    salas_scrollbar.pack(
        side="right",
        fill="y"
    )

    # lista de entries
    sala_entries = []

    # adiciona campo
    def adicionar_sala_entry():

        entry = tk.Entry(
            salas_frame
        )

        entry.pack(
            fill="x",
            padx=5,
            pady=2
        )

        sala_entries.append(entry)

        # enter cria novo campo
        entry.bind(
            "<Return>",
            lambda e: adicionar_sala_entry()
        )

        # backspace remove campo vazio
        def remover_se_vazio(event):

            if (
                event.keysym == "BackSpace"
                and entry.get() == ""
                and len(sala_entries) > 1
            ):

                sala_entries.remove(entry)

                entry.destroy()

        entry.bind(
            "<KeyPress>",
            remover_se_vazio
        )

    # primeiro campo
    adicionar_sala_entry()

    # função
    label_funcao = tk.Label(
        frame,
        text="Função"
    )

    label_funcao.pack(
        anchor="w",
        pady=(15, 5)
    )

    funcao_var = tk.StringVar(
        value="aluno"
    )

    radio_aluno = tk.Radiobutton(
        frame,
        text="Aluno",
        variable=funcao_var,
        value="aluno"
    )

    radio_professor = tk.Radiobutton(
        frame,
        text="Professor",
        variable=funcao_var,
        value="professor"
    )

    radio_aluno.pack(
        anchor="w"
    )

    radio_professor.pack(
        anchor="w"
    )

    # submit
    def submit():

        nome = entry_nome.get().strip()

        sobrenome = entry_sobrenome.get().strip()

        senha = entry_senha.get().strip()

        celular = entry_celular.get().strip()

        email = entry_email.get().strip()

        matricula = entry_matricula.get().strip()

        salas = [
            entry.get().strip()
            for entry in sala_entries
            if entry.get().strip() != ""
        ]

        funcao = funcao_var.get()

        # validação
        if (
            nome == ""
            or sobrenome == ""
            or senha == ""
            or celular == ""
            or email == ""
            or matricula == ""
            or len(salas) == 0
        ):

            messagebox.showerror(
                "Erro",
                "Preencha todos os campos."
            )

            return

        # dados coletados
        dados_usuario = {

            "nome": nome,
            "sobrenome": sobrenome,
            "senha": senha,
            "celular": celular,
            "email": email,
            "matricula": matricula,
            "salas": salas,
            "funcao": funcao
        }

        # temporário
        uid = criar_usuario(dados_usuario)

        messagebox.showinfo(
            "Sucesso",
            f"Usuário criado.\nUID: {uid}"
        )

        janela_criar_usuario.destroy()

    # botão submit
    btn_submit = tk.Button(
        frame,
        text="Criar Usuário",
        height=2,
        command=submit
    )

    btn_submit.pack(
        fill="x",
        pady=20
    )