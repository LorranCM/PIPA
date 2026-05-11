<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php 
        require "components/preset.php"; 
        require "components/updates/get_calendar_data.php";
        print_r($_SESSION['calendar_data']);
    ?>
    <ul>
        <li><a href="components/updates/get_calendar_data.php">Update Calendar Data</a></li>
    </ul>
</body>
</html>