<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PIPA</title>
    <link rel="icon" type="image/svg+xml" href=<?= base_url("assets/icons/kite-origami-paper-svgrepo-com.svg") ?>>
    <link rel="stylesheet" href=<?= base_url("assets/css/partials/navbar.css") ?>>

    <?php echo $this->renderSection('styles-ref'); ?>

</head>
<body>
    
    
    <?php echo view('partials/navbar') ?>
    
    <?php echo $this->renderSection('content'); ?>
    
    <script> const baseUrl = window.location.origin; </script>
    <?php echo $this->renderSection('scripts-ref'); ?>
    
</body>
</html>