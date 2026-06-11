<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href=<?= base_url("assets/css/partials/navbar.css") ?>>
    <link rel="stylesheet" href=<?= base_url("assets/css/partials/footer.css") ?>>

    <?php echo $this->renderSection('styles-ref'); ?>

</head>
<body>

    <?php echo view('partials/navbar') ?>
    
    <?php echo $this->renderSection('content'); ?>
    
    <?php echo view('partials/footer') ?>
    
    <?php echo $this->renderSection('scripts-ref'); ?>
    
</body>
</html>