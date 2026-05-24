<?php

require __DIR__ . "/../../packages/configdb.php";

$data = json_decode(file_get_contents("php://input"), true);
$event_id = $data["event_id"] ?? null;

if (!$event_id) throw new Exception("Missing eventID", 400);

$db = getFirestore();
$doc_ref = $db->collection("Events")->document($event_id);
$doc_ref->update([
    ["path" => "status", "value" => "confirmed"] 
]);

// header("Content-Type: application/json");
echo json_encode(
    [   
        "success" => true
    ]
);