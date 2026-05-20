<?php

require __DIR__ . "/../../packages/configdb.php";

session_start();
$db = getFirestore();
$data = json_decode(file_get_contents("php://input"), true);
$uid = $data["uid"] ?? $_SESSION["uid"];

$user_doc_ref = $db->collection("Users")->document($uid);
$snapshot = $user_doc_ref->snapshot();
$classroomIDs = $snapshot["classrooms"] ?? [];
$classrooms_newlist = [];

foreach ($classroomIDs as $classroomID) {
    $doc_ref = $db->collection("Classrooms")->document($classroomID);
    $snapshot = $doc_ref->snapshot();
    $curricular_unit = $snapshot["curricular-unit"];

    $teacher_id = $snapshot['tenured-teacher'] ?? "placeholder-teacher-id";
    if ($teacher_id === "") {
        $teacher_id = "placeholder-teacher-id";
    }

    $doc_ref = $db->collection('Users')->document($teacher_id);
    $snapshot = $doc_ref->snapshot();
    if (!$snapshot->exists()) {
        $teacher_name = "Unknown Teacher";       
    } else {
        $teacher_name = $snapshot['name'] ?? "Unkown Teacher";
    }

    $classrooms_newlist[] = [
        "id" => $classroomID,
        "curricular-unit" => $curricular_unit,
        "teacher-name" => $teacher_name
    ];
   
}

echo json_encode(
    [
        "classrooms" => $classrooms_newlist
    ]
);