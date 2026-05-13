<?php

require __DIR__ . '/../packages/configdb.php';
use Kreait\Firebase\Factory;

// recebe o token do cliente
$data = json_decode(file_get_contents("php://input"), true);
$token = $data['token'] ?? null;

header("Content-Type: application/json");

try {

    if (!$token) throw new Exception("Missing token", 400);
        
    session_start();

    $factory = (new Factory)->withServiceAccount(__DIR__ . '/../packages/credentials.json');
    
    $auth = $factory->createAuth();
    
    // verifica o token recebido, se for valido, obtem o uid do usuario armazenando-o na sessao
    $verified = $auth->verifyIdToken($token);
    $uid = $verified->claims()->get('sub');

    // acessa os dados do usuario no firestore para obter seu papel(role)
    $db = getFirestore();
    $doc_ref = $db->collection('Users')->document($uid);
    $snapshot = $doc_ref->snapshot();

    if (!$snapshot->exists()) {
        throw new Exception("User not found", 404);
    }

    $data = $snapshot->data();
    $role = $data['role'];

    if (!$role) {
        throw new Exception("User role not defined", 400);
    }

    if ($role == "moderator") {
        // a tratar

    } else {
        // dados obrigatorios
        $name = $data['name'];
        $lastname = $data['lastname'];
        // nao trabalhar com instituicao por enquanto $instituitionID = $data['instituitionID'];
        
        // opicionais (pelo menos enquanto nao e o produto final)
        $calendar_data = [];
        $email = $data['email'] ?? "missing-email";
        $contact_number = $data['contact_number'] ?? "missing-contact-number";
        $pfp_rel = $data['profile-picture-rel'] ?? "assets/images/default-pfp.jpg";

        $_SESSION['name'] = $name;
        $_SESSION['lastname'] = $lastname;
        $_SESSION['calendar_data'] = $calendar_data;
        $_SESSION['email'] = $email;
        $_SESSION['contact_number'] = $contact_number;
        $_SESSION['pfp-rel'] = $pfp_rel;

        if ($role === "student") {
            $classrooms = $data['classrooms'] ?? [];
            $_SESSION['classrooms'] = $classrooms;
        }
        else if ($role === "teacher") {
            // a tratar
        }

    }
    
    $_SESSION['role'] = $role;
    $_SESSION['uid'] = $uid;
    $_SESSION['loggedin'] = true;

    echo json_encode([
        "success" => true,
        "redirect" => "index.php"
    ]);

} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "error" => $e->getMessage(),
    ]);
    
}