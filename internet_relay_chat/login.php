<?php
session_start();

require ".\\language.php";
define("MAIN_LABEL", "Internet Relay Chat - " . getLan("title_login"));

?>
<!DOCTYPE html>
<html lang=<?php echo $_SESSION["lang"] ?? "cs" ?>>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/base.css">
    <link rel="stylesheet" href="styles/login.css">
    <title><?php echo MAIN_LABEL; ?></title>
</head>
<body>
    <div class="pageContainer retro-fx">    
        <?php require ".\\layout\\header.php"; ?>

        <main>
            <div id="login_container">
                <label for="username"><?php echo getLan("login_label_username"); ?></label>
                <input type="text" name="username" id="in_username" required
                    minlength="3" maxlength="32"/>
                
                <label for="password"><?php echo getLan("login_label_password"); ?></label>
                <input type="password" name="password" id="in_password" required />

                <button type="submit" id="in_submit">Přihlásit</button>
            </div>
            <div>
                <h2>Upozornění!</h2>
                <p>Tento web <b>není</b> nijak zabezpečený, a proto se silně <b>nedoporučuje</b> zadávat jakékoliv citlivé udáje.</p>
            </div>
        </main>

        <?php require ".\\layout\\footer.php"; ?>
        <script src="js/header.js"></script>
    </div>
</body>
</html>