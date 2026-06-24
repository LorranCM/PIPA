<?php

namespace App\Models;

use Google\Cloud\Firestore\FirestoreClient;

class ClassroomModel {

    public FirestoreClient $db;

    public function __construct() {

        $this->db = service('firestore')->db;

    }

    public function find($id) {
        
        $doc_ref = $this->db->collection('Classrooms')->document($id);
        $snapshot = $doc_ref->snapshot();
        return $snapshot;
        
    }

    public function create($data) {
       
        $this->db->collection('Classrooms')->add($data);
        return true;

    }

    public function find_all() {
        return $this->db->collection('Classrooms')->documents();
    }

    public function update($id, $data) {
        
        $doc_ref = $this->db->collection('Classrooms')->document($id);
        $doc_ref->set($data, ['merge' => true]);
        return true;

    }

    public function update_fields($id, $data) {
        
        $doc_ref = $this->db->collection('Classrooms')->document($id);
        
        // Converte o array associativo do PHP para o formato exigido pelo SDK do Firestore
        $formattedData = [];
        foreach ($data as $path => $value) {
            $formattedData[] = [
                'path'  => $path,
                'value' => $value
            ];
        }
        
        // Agora o SDK vai aceitar sem erros
        $doc_ref->update($formattedData);
    }

    public function delete($id) {
        
        $doc_ref = $this->db->collection('Classrooms')->document($id);
        $doc_ref->delete();
        return true;

    }

}