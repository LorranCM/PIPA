<?php

namespace App\Libraries;

require_once COMPOSER_PATH;

use Google\Cloud\Firestore\FirestoreClient;
use Kreait\Firebase\Factory;

class FirestoreService {

    private string $credentials = APPPATH . '/credentials/key-file.json';

    public function get_database() {

        putenv('GOOGLE_APPLICATION_CREDENTIALS=' . $this->credentials);
        
        $db = new FirestoreClient(
            [
                'projectId' => 'pipa-35e1f', 
                'transport' => 'rest'
            ]
        );
        
        return $db;
    }

    public function validate_token($token) {

        try {
            $factory = (new Factory)->withServiceAccount($this->credentials);
            $auth = $factory->createAuth();
            
            // verifica o token recebido, se for valido, obtem o uid do usuario armazenando-o na sessao
            $verified = $auth->verifyIdToken($token);
            $uid = $verified->claims()->get('sub');
        } catch (\Exception $e) {
            $uid = null;
        }
        
        return $uid;
    }
}