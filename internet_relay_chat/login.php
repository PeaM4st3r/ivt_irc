<?php
require ".\\handlers\\common.php";
define("MAIN_LABEL", "Internet Relay Chat - " . getLan("title_login"));

if (isset($_SESSION["user_id"])) {
    header("Location: logout.php");
    die();
}

?>
<!DOCTYPE html>
<html lang=<?php echo $_SESSION["lang"] ?? "cs" ?>>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/base.css">
    <link rel="stylesheet" href="styles/login.css">
    <script defer src="js/login.js" type="module"></script>
    <title><?php echo MAIN_LABEL; ?></title>
</head>
<body>
    <div class="pageContainer retro-fx">    
        <?php require ".\\layout\\header.php"; ?>

        <main>
            <div id="login_container">
                <label for="in_username"><?php echo getLan("login_label_username"); ?></label>
                <input type="text" name="username" id="in_username" required
                    minlength="3" maxlength="32"/>
                <!-- <p>Testovací text</p> -->
                
                <label for="in_password"><?php echo getLan("login_label_password"); ?></label>
                <input type="password" name="password" id="in_password" required />

                <button id="in_submit" disabled>Log in</button>
            </div>
            <div id="disclaimer">
                <h1><?php echo getLan("login_disclaimer_warning"); ?></h1>
                <p><?php echo getLan("login_disclaimer_before"); ?></p>
                <ul>
                    <li><?php echo getLan("login_disclaimer_list_1"); ?></li>
                    <li><?php echo getLan("login_disclaimer_list_2"); ?></li>
                    <li><?php echo getLan("login_disclaimer_list_3"); ?></li>
                    <li><?php echo getLan("login_disclaimer_list_4"); ?></li>
                </ul>
                <p><?php echo getLan("login_disclaimer_based_on"); ?></p>
                <p><?php echo getLan("login_disclaimer_no_responsibility"); ?></p>
            </div>
        </main>

        <?php require ".\\layout\\footer.php"; ?>
    </div>
</body>
</html>