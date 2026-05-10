<?php
$db = getFirestore();
$uid = $_SESSION['uid'];

$doc_ref = $db->collection('Users')->document($uid);
$snapshot = $doc_ref->snapshot();
$data = $snapshot['calendar'] ?? [];

if (empty($data)) {
    $_SESSION['calendar_data'] = [];
    return;
}

$events = [];

foreach ($data as $eventID) {
    $doc_ref = $db->collection('Events')->document($eventID);
    $snapshot = $doc_ref->snapshot();
    $data = $snapshot->data();

    $doc_ref = $db->collection('Users')->document($data['teacher-id']);
    $snapshot = $doc_ref->snapshot();
    $teacher_name = $snapshot['name'] ?? "Unknown Teacher";

    $data['teacher'] = $teacher_name;

    $events[] = $data;
}

$_SESSION['calendar_data'] = $events;