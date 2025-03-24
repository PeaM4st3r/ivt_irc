<?php
require ".\\language.php";

?>
<!DOCTYPE html>
<html lang=<?php echo $_SESSION["lang"] ?? "cs" ?>>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/base.css">
    <title>Internet Relay Chat - <?php echo getLan("title_login"); ?></title>
</head>
<body>
    <div class="pageContainer retro-fx">    
        <?php require ".\\layout\\header.php"; ?>

        <?php require ".\\layout\\footer.php"; ?>
        <script src="js/header.js"></script>
    </div>
</body>
</html>