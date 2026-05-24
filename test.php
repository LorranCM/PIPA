<?php

require __DIR__ . "/packages/configdb.php";

$db = getFirestore();
$docref = $db->collection("Classrooms")->document("tes-id");
$Snapshot = $docref->snapshot();
if ($Snapshot->exists()) {
    echo $Snapshot["curricular-unit"];}
else echo "a";