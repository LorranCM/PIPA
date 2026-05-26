<?php

require __DIR__ . "/../../packages/configdb.php";

$data = json_decode(file_get_contents("php://input"), true);
$dateStr = $data["date"] ?? null;
$classroom_id = $data["classroom_id"] ?? null;

session_start();
$uid = $_SESSION["uid"];

if (!$classroom_id) throw new Exception("Missing classroomID", 400);

$db = getFirestore();
$event_doc_ref = $db->collection("Events")->add(
    [
        "classroom-id" => $classroom_id,
        "date" => $dateStr,
        "description" => "none",
        "participants" => [$uid],
        "status" => "pending"
    ]
);

$eventID = $event_doc_ref->id();

$student_doc_ref = $db->collection("Users")->document($uid);
$snapshot = $student_doc_ref->snapshot();
$eventID_list = $snapshot["calendar"];
$eventID_list[] = $eventID;
$student_doc_ref->update(
    [
        ['path' => 'calendar', 'value' => $eventID_list]
    ]
);

$classroom_doc_ref = $db->collection("Classrooms")->document($classroom_id);
$snapshot = $classroom_doc_ref->snapshot();
$teacher_id = $snapshot["tenured-teacher"];

$teacher_doc_ref = $db->collection("Users")->document($teacher_id);
$snapshot = $teacher_doc_ref->snapshot();
$eventID_list = $snapshot["calendar"];
$eventID_list[] = $eventID;

$eventID_list = array_values($eventID_list);
$teacher_doc_ref->update(
    [
        ['path' => 'calendar', 'value' => $eventID_list]
    ]
);

echo json_encode(
    [   
        "success" => true
    ]
);