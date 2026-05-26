# firestore_service.py

import firebase_admin

from firebase_admin import credentials
from firebase_admin import firestore


# evita inicializar o firebase mais de uma vez
if not firebase_admin._apps:

    cred = credentials.Certificate(
        "credentials.json"
    )

    firebase_admin.initialize_app(cred)


db = firestore.client()


def obter_usuarios():

    usuarios_ref = db.collection("Users").stream()

    usuarios = []

    for usuario in usuarios_ref:

        usuarios.append({
            "id": usuario.id,
            "data": usuario.to_dict()
        })

    return usuarios

def obter_salas():

    salas_ref = db.collection("Classrooms").stream()

    salas = []

    for sala in salas_ref:

        salas.append({

            "id": sala.id,

            "data": sala.to_dict()
        })

    return salas