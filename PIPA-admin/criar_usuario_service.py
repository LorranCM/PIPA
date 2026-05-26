# criar_usuario_service.py

import firebase_admin

from firebase_admin import credentials
from firebase_admin import firestore
from firebase_admin import auth


# inicializa firebase apenas uma vez
if not firebase_admin._apps:

    cred = credentials.Certificate(
        "firebase-key.json"
    )

    firebase_admin.initialize_app(cred)


db = firestore.client()


def criar_usuario(dados_usuario):

    # cria usuário no authentication
    user = auth.create_user(

        email= dados_usuario["matricula"] + "@app.com",

        password= dados_usuario["senha"]
    )

    # uid do authentication
    uid = user.uid

    if dados_usuario["funcao"].lower() == "aluno" :
        role = "student"
    else :
        role = "teacher"

    # cria documento no firestore
    db.collection("Users").document(uid).set({

        "name": dados_usuario["nome"],

        "lastname": dados_usuario["sobrenome"],

        "contact-number": dados_usuario["celular"],

        "role": role,

        "classrooms": dados_usuario["salas"],

        "registration": dados_usuario["matricula"],

        "email": dados_usuario["email"]
    })

    return uid