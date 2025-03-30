<?php
require ".\\handlers\\common.php";
define("MAIN_LABEL", "Internet Relay Chat - " . getLan("title_logout"));
define("NAV_LOGIN", getLan("nav_chat"));

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    die();
}

?>

<!DOCTYPE html>
<html lang=<?php echo $_SESSION["lang"] ?? "cs" ?>>
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/base.css">
    <link rel="stylesheet" href="styles/logout.css">
    <script defer src="js/logout.js" type="module"></script>
    <title><?php echo MAIN_LABEL; ?></title>
</head>
<body>
    <div class="pageContainer retro-fx">    
        <?php require ".\\layout\\header.php"; ?>

        <main>
            <div id="logout_container">
                <p><?php echo getLan("logout_logged_in_as"); ?></p>
                <p id="username_field">NIKDO</p> <!-- TO-DO -->
                <a href="index.php"><?php echo getLan("logout_go_to_chat"); ?></a>
                <button id="in_logout"><?php echo getLan("logout_log_out"); ?></button>
            </div>
        </main>

        <?php require ".\\layout\\footer.php"; ?>
    </div>
</body>
</html>