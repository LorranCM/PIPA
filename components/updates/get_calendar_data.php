<?php
$db = getFirestore();
$uid = $_SESSION['uid'];

// obtem o campo calendar do usuario, que e um array de eventIDs
$user_doc_ref = $db->collection('Users')->document($uid);
$snapshot = $user_doc_ref->snapshot();
$event_IDs = $snapshot['calendar'] ?? [];

if (empty($event_IDs)) {
    $_SESSION['calendar_data'] = [];
    return;
}

$events = [];

// para cada eventID, obtem os dados na colecao Events e adiciona o nome do professor ao evento
foreach ($event_IDs as $eventID) {
    $doc_ref = $db->collection('Events')->document($eventID);
    $snapshot = $doc_ref->snapshot();
    if (!$snapshot->exists()) {
        unset($event_IDs[array_search($eventID, $event_IDs)]);
        continue;
    }

    $event_data = $snapshot->data();
    $teacher_id = $event_data['teacher-id'] ?? "placeholder-teacher-id";
    if ($teacher_id === "") {
        $teacher_id = "placeholder-teacher-id";
    }

    $doc_ref = $db->collection('Users')->document($teacher_id);
    $snapshot = $doc_ref->snapshot();
    if (!$snapshot->exists()) {
        $teacher_name = "Unknown Teacher";       
    } else {
        $teacher_name = $doc_ref->snapshot()['name'] ?? "Unknown Teacher";
    }

    $event_data['teacher'] = $teacher_name;

    $events[] = $event_data;
}

$user_doc_ref->update([
    ['path' => 'calendar', 'value' => $event_IDs]
]);

// atualiza os dados da sessao
$_SESSION['calendar_data'] = $events;