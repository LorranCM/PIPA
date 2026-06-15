<?php

namespace App\Models;

use Google\Cloud\Firestore\FirestoreClient;
use App\Libraries\FirestoreService;

class ClassroomModel {

    public FirestoreClient $db;

    public function __construct() {
        $this->db = (new FirestoreService())->get_database();
    }

    public function find($id) {
        // buscar o documento do usuario no firestore usando o uid
        $doc_ref = $this->db->collection('Classrooms')->document($id);
        $snapshot = $doc_ref->snapshot();
        return $snapshot;
    }

    public function create($data) {
        // criar elemento para users junto de authentication do firebase, a tratar
    }

    public function update($id, $data) {
        // atualizar o documento do usuario no firestore usando o uid
        $doc_ref = $this->db->collection('Classrooms')->document($id);
        $doc_ref->set($data, ['merge' => true]);
    }

    public function delete($id) {
        // deletar o documento do usuario no firestore usando o uid
        $doc_ref = $this->db->collection('Classrooms')->document($id);
        $doc_ref->delete();
    }

}