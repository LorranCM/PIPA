# main.py

import tkinter as tk

# imports das páginas
from salas_page import abrir_salas
from usuarios_page import abrir_usuarios


# configuração da janela principal
root = tk.Tk()

root.title("Painel Administrativo")
root.geometry("500x600")


# título
titulo = tk.Label(
    root,
    text="Painel Administrativo",
    font=("Arial", 20, "bold")
)

titulo.pack(pady=40)


# botão salas
btn_salas = tk.Button(
    root,
    text="Salas",
    width=20,
    height=2,

    # função importada
    command=lambda: abrir_salas(root)
)

btn_salas.pack(pady=10)


# botão usuários
btn_usuarios = tk.Button(
    root,
    text="Usuários",
    width=20,
    height=2,

    # função importada
    command=lambda: abrir_usuarios(root)
)

btn_usuarios.pack(pady=10)


# inicia interface
root.mainloop()