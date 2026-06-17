<?php

namespace App\Models;

use Google\Cloud\Firestore\FirestoreClient;

class UserModel {

    public FirestoreClient $db;

    public function __construct() {

        $this->db = service('firestore')->db;

    }

    public function find($id) {

        $doc_ref = $this->db->collection('Users')->document($id);
        $snapshot = $doc_ref->snapshot();
        return $snapshot;

    }

    public function create($data, $email, $password) {
        // 1. Cria o autenticador e obtém o UID
        $uid = $this->create_auth($email, $password);

        if ($uid) {
            // 2. Cria o documento no Firestore usando o UID como ID do documento
            $this->db->collection('Users')
                     ->document($uid) // Define o ID manualmente aqui
                     ->set($data);    // Usa set() em vez de add() para especificar o ID
            
            return $uid; // Retorna o ID para o controller saber que deu certo
        }

        return null; // Falha na criação da autenticação
    }

    public function find_all() {
        return $this->db->collection('Users')->documents();
    }

    public function update($id, $data) {

        $doc_ref = $this->db->collection('Users')->document($id);
        $doc_ref->set($data, ['merge' => true]);
        
    }

    public function update_fields($id, $data) {
        
        $doc_ref = $this->db->collection('Users')->document($id);
        
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
        // 1. Exclui o usuário do Firebase Authentication
        $authDeleted = service('firestore')->delete_auth_user($id);

        if ($authDeleted) {
            // 2. Exclui o documento do Firestore se o Auth foi removido com sucesso
            $doc_ref = $this->db->collection('Users')->document($id);
            $doc_ref->delete();
            return true;
        }

        return false; // Falha ao excluir a autenticação
    }
    
    public function create_auth($email, $password) {
        return service('firestore')->create_auth_user($email, $password);
    }

}