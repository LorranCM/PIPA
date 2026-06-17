<?php

namespace App\Libraries;

require_once COMPOSER_PATH;

use Google\Cloud\Firestore\FirestoreClient;
use Kreait\Firebase\Factory;

class FirestoreService {

    private string $credentials = APPPATH . '/credentials/key-file.json';
    public $db;

    public function __construct() {

        $this->db = $this->get_database();

    }

    private function get_database() {

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

    public function create_auth_user(string $email, string $password): ?string {
        try {
            $factory = (new Factory)->withServiceAccount($this->credentials);
            $auth = $factory->createAuth();

            $user = $auth->createUser([
                'email'    => $email . '@app.com',
                'password' => $password,
            ]);

            return $user->uid;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function delete_auth_user(string $uid): bool {
        try {
            $factory = (new Factory)->withServiceAccount($this->credentials);
            $auth = $factory->createAuth();

            $auth->deleteUser($uid);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

}