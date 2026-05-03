<?php

require __DIR__ . '/../packages/configdb.php';
use Kreait\Firebase\Factory;

$data = json_decode(file_get_contents("php://input"), true);
$token = $data['token'] ?? null;

header("Content-Type: application/json");

try {

    if (!$token) throw new Exception("Missing token", 400);
        
    session_start();

    $factory = (new Factory)
    ->withServiceAccount(__DIR__ . '/../packages/credentials.json');
    
    $auth = $factory->createAuth();
    
    $verified = $auth->verifyIdToken($token);
    $uid = $verified->claims()->get('sub');

    $db = getFirestore();

    $docRef = $db->collection('Users')->document($uid);
    $snapshot = $docRef->snapshot();
    $role = $snapshot->get('role');
    
    $_SESSION['uid'] = $uid;
    $_SESSION['role'] = $role;
    $_SESSION['loggedin'] = true;

    echo json_encode([
        "ok" => true,
        "uid" => $uid,
        "redirect" => "index.php"
    ]);

} catch (Exception $e) {
    $_SESSION = [];
    session_destroy();

    echo json_encode([
        "ok" => false,
        "error" => $e->getMessage(),
    ]);
}