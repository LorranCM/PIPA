# excluir_sala_service.py

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


def excluir_sala(sala_id):

    # exclui documento
    db.collection("Classrooms").document(sala_id).delete()  