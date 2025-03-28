<?php

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
    <script defer src="js/login.js"></script>
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

                <button type="submit" id="in_submit" disabled>Přihlásit</button>
            </div>
            <div id="disclaimer">
                <h1>Upozornění!</h1>
                <p>Před zadáváním <b>jakýchkoliv</b> citlivých udájů vězte následující:</p>
                <ul>
                    <li>Mezi klientem a serverem není <b>žádné</b> šifrování</li>
                    <li>Server nepodporuje HTTPS (viz první bod)</li>
                    <li>Heslo, které odešlete serveru, je v požadavku <b>prostý text</b></li>
                    <li>Serverová databáze ukládá Vaše heslo jako hash</li>
                </ul>
                <p>Na základě těchto bodů je Vám <b>silně</b> doporučeno <b>nezadávat</b> jakékoliv citlivé informace.</p>
                <p>Autor neručí za jakékoliv škody způsobené Vaší ignorancí.</p>
            </div>
        </main>

        <?php require ".\\layout\\footer.php"; ?>
        
    </div>
</body>
</html>