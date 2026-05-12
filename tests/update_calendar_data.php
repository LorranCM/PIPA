<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php 
        require __DIR__. "/../components/preset.php"; 
        require __DIR__. "/../services/db.reqs/get_calendar_data.php"; 
        //print bonito do calendar data
        foreach ($_SESSION['calendar_data'] as $event) {
            echo "<pre>";
            print_r($event);
            echo "</pre>";
        }

    ?>
</body>
</html>