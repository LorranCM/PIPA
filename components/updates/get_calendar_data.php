<?php
$db = getFirestore();
$uid = $_SESSION['uid'];

// obtem o campo calendar do usuario, que e um array de eventIDs
$doc_ref = $db->collection('Users')->document($uid);
$snapshot = $doc_ref->snapshot();
$data = $snapshot['calendar'] ?? [];

if (empty($data)) {
    $_SESSION['calendar_data'] = [];
    return;
}

$events = [];

// para cada eventID, obtem os dados na colecao Events e adiciona o nome do professor ao evento
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

// atualiza os dados da sessao
$_SESSION['calendar_data'] = $events;