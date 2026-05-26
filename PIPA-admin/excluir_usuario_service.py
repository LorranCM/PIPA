# excluir_usuario_service.py

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


def excluir_usuario(uid):

    # exclui documento do firestore
    db.collection("Users").document(uid).delete()

    # exclui authentication
    auth.delete_user(uid)