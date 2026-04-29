<?php

require __DIR__ . '/../packages/vendor/autoload.php';
use Kreait\Firebase\Factory;

$data = json_decode(file_get_contents("php://input"), true);
$token = $data['token'] ?? null;

if (!$token) {
    http_response_code(400);
    echo "Token não enviado";
    exit;
}
    
try {
        
    session_start();
    $factory = (new Factory)
    ->withServiceAccount(__DIR__ . '/../packages/credentials.json');
    
    $auth = $factory->createAuth();
    
    $verified = $auth->verifyIdToken($token);
    $uid = $verified->claims()->get('sub');
    
    $_SESSION['uid'] = $uid;

    header("Content-Type: application/json");
    
    echo json_encode([
        "ok" => true,
        "uid" => $uid,
        "redirect" => "index.php"
    ]);

} catch (Exception $e) {
    http_response_code(401);
    echo "Erro: " . $e->getMessage();
}