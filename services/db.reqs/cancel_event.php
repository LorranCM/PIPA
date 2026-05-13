<?php

require __DIR__ . "/../../packages/configdb.php";

$data = json_decode(file_get_contents("php://input"), true);
$event_id = $data["event_id"] ?? null;

try {

    session_start();
    $uid = $_SESSION["uid"];
    $role = $_SESSION["role"];

    if (!$event_id) throw new Exception("Missing eventID", 400);

    $db = getFirestore();

    if ($role == "student") {

        $event_docref = $db->collection("Events")->document($event_id);
        $user_docref = $db->collection("Users")->document($uid);

        $snapshot = $event_docref->snapshot();
        $event_participants = $snapshot["participants"] ?? [];
        // unset($event_participants[array_search($uid, $event_participants)]);

    } else if ($role == "teacher") {
        // a tratar
    }

    header("Content-Type: application/json");
    echo json_encode(
        [   
            "success" => true,
            "event-participants" => $event_participants
        ]
    );

} catch (Exception $e) {
    echo json_encode(
        [   
            "success" => false,
            "error" => $e->getMessage()
        ]
    );
}