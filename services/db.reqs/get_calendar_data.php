<?php

require __DIR__ . "/../../packages/configdb.php";

session_start();
$db = getFirestore();
$uid = $_SESSION['uid'];

// obtem o campo calendar do usuario, que e um array de eventIDs
$user_doc_ref = $db->collection('Users')->document($uid);
$snapshot = $user_doc_ref->snapshot();
$event_IDs = $snapshot['calendar'] ?? [];

$events = [];

foreach ($event_IDs as $eventID) {
    // obtem dados do evento a partir do eventID, caso o id nao exista na colecao de eventos, remove o id do array de eventos do usuario
    $doc_ref = $db->collection('Events')->document($eventID);
    $snapshot = $doc_ref->snapshot();
    if (!$snapshot->exists()) {
        unset($event_IDs[array_search($eventID, $event_IDs)]);
        continue;
    } 

    $event_participants = $snapshot["participants"] ?? [];
    if (!in_array($uid, $event_participants)) {
        $event_participants[] = $uid;
    }
    
    $doc_ref->update([
        ['path' => 'participants', 'value' => $event_participants]
    ]);

    $event_data = $snapshot->data();

    // obtem o id da classe a partir dos dados do evento, caso nao exista a classe na colecao de classes
    // define o nome do professor como "Unknown Teacher"
    $classroom_id = $event_data['classroom-id'] ?? "placeholder-classroom-id";
    if ($classroom_id === "") {
        $classroom_id = "placeholder-classroom-id";
    }

    $doc_ref = $db->collection('Classroom')->document($classroom_id);
    $snapshot = $doc_ref->snapshot();
    $curricular_unit = "Unknown unit";
    
    if (!$snapshot->exists()) {
        $teacher_name = "Unknown Teacher";      

    } else {       
        $curricular_unit = $snapshot['curricular-unit'] ?? "Unknown unit";
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
    }
    
    $event_data['curricular-unit'] = $curricular_unit;
    $event_data['classroom-id'] = $classroom_id;
    $event_data['teacher'] = $teacher_name;
    $event_data['event-id'] = $eventID;

    $events[] = $event_data;
}

$user_doc_ref->update([
    ['path' => 'calendar', 'value' => $event_IDs]
]);

echo json_encode($events);