<?php

require __DIR__ . '/vendor/autoload.php';

use Google\Cloud\Firestore\FirestoreClient;

function getFirestore() {

    static $db;
    
    if ($db === null) {

        $credentials = __DIR__ . '/../packages/credentials.json';
        
        if (!file_exists($credentials)) {
            throw new Exception("Credenciais do Google não encontradas.");
        }
        
        putenv('GOOGLE_APPLICATION_CREDENTIALS=' . $credentials);
        
        $db = new FirestoreClient([
            'projectId' => 'pipa-35e1f', 
            'transport' => 'rest'
        ]);
    }

    return $db;
}
