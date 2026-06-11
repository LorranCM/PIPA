<?php

namespace App\Models;

use Google\Cloud\Firestore\FirestoreClient;
use App\Libraries\FirestoreService;

class UserModel {

    public FirestoreClient $db;

    public function __construct() {
        $this->db = (new FirestoreService())->get_database();
    }

    public function find($uid, $return_snapshot = false) {
        // buscar o documento do usuario no firestore usando o uid
        $doc_ref = $this->db->collection('Users')->document($uid);
        $snapshot = $doc_ref->snapshot();

        if (!$snapshot->exists()) {
            return null;
        }
        if ($return_snapshot) {
            return $snapshot;
        }
        return $doc_ref;
    }

    public function create($data) {
        // criar elemento para users junto de authentication do firebase, a tratar
    }

    public function update($uid, $data) {
        // atualizar o documento do usuario no firestore usando o uid
        $doc_ref = $this->db->collection('Users')->document($uid);
        $doc_ref->set($data, ['merge' => true]);
    }

    public function delete($uid) {
        // deletar o documento do usuario no firestore usando o uid
        $doc_ref = $this->db->collection('Users')->document($uid);
        $doc_ref->delete();
    }

}