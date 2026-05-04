<?php

require __DIR__ . '/../packages/configdb.php';
use Kreait\Firebase\Factory;

$data = json_decode(file_get_contents("php://input"), true);
$token = $data['token'] ?? null;

header("Content-Type: application/json");

try {

    if (!$token) throw new Exception("Missing token", 400);
        
    session_start();

    $factory = (new Factory)->withServiceAccount(__DIR__ . '/../packages/credentials.json');
    
    $auth = $factory->createAuth();
    
    $verified = $auth->verifyIdToken($token);
    $uid = $verified->claims()->get('sub');

    $db = getFirestore();
    $docRef = $db->collection('Users')->document($uid);
    $snapshot = $docRef->snapshot();

    if (!$snapshot->exists()) {
        throw new Exception("User not found", 404);
    }

    $data = $snapshot->data();

    $role = $data['role'] ?? null;
    $classrooms = $data['classrooms'] ?? [];

    if (!$role) {
        throw new Exception("User role not defined", 400);
    }
    
    $_SESSION['uid'] = $uid;
    $_SESSION['role'] = $role;
    $_SESSION['loggedin'] = true;
    $_SESSION['classrooms_ids'] = $classrooms;

    echo json_encode([
        "ok" => true,
        "uid" => $uid,
        "role" => $role,
        "redirect" => "index.php"
    ]);

} catch (Exception $e) {
    echo json_encode([
        "ok" => false,
        "error" => $e->getMessage(),
    ]);
    
}