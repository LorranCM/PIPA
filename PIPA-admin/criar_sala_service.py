# criar_sala_service.py

import firebase_admin

from firebase_admin import credentials
from firebase_admin import firestore


# inicializa firebase apenas uma vez
if not firebase_admin._apps:

    cred = credentials.Certificate(
        "firebase-key.json"
    )

    firebase_admin.initialize_app(cred)


db = firestore.client()


def criar_sala(dados_sala):

    # cria documento automático
    sala_ref = db.collection("Classrooms").document()

    # id da sala
    sala_id = sala_ref.id

    # cria documento
    sala_ref.set({

        "curricular-unit":
            dados_sala["curricular-unit"],

        "tenured-teacher":
            dados_sala["tenured-teacher"]
    })

    return sala_id