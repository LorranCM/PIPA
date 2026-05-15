<?php

require __DIR__ . "/../../packages/configdb.php";

$data = json_decode(file_get_contents("php://input"), true);
$event_id = $data["event_id"] ?? null;

session_start();
$uid = $_SESSION["uid"];
$role = $_SESSION["role"];

if (!$event_id) throw new Exception("Missing eventID", 400);

$db = getFirestore();

if ($role == "student") {

    $event_doc_ref = $db->collection("Events")->document($event_id);
    $user_doc_ref = $db->collection("Users")->document($uid);

    $snapshot = $user_doc_ref->snapshot();
    $user_calendar = $snapshot["calendar"] ?? [];

    $snapshot = $event_doc_ref->snapshot();
    $event_participants = $snapshot["participants"] ?? [];
    
    if (($key = array_search($uid, $event_participants)) !== false) {
        unset($event_participants[$key]);
    }
    if (($key = array_search($event_id, $user_calendar)) !== false) {
        unset($user_calendar[$key]);
    }

    $user_calendar = array_values($user_calendar);
    $event_participants = array_values($event_participants);

    $user_doc_ref->update([
        ["path" => "calendar", "value" => $user_calendar]
    ]);
    
    if (count($event_participants) <= 0) {
        $event_doc_ref->delete();
    } else {
        $event_doc_ref->update([
            ["path" => "participants", "value" => $event_participants] 
        ]);
    }

} else if ($role == "teacher") {
    // a tratar
}

header("Content-Type: application/json");
echo json_encode(
    [   
        "success" => true
    ]
);