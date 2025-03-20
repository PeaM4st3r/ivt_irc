<?php
require ".\\language.php";

// Helper layout functions
function setLanguageButtonSelected($testLanguage) {
    if ($_SESSION["lang"] == $testLanguage) {
        echo "class='b_sel'";
    }
}



// Database interaction


$irc_pdo = false;
/* $irc_pdo = DBH\connectToDB($db_config["irc_db_server"], $db_config["irc_db_name"],
    $db_config["irc_db_username"], $db_config["irc_db_password"]);
 */
if ($irc_pdo) {
    //$messages = DBH\getChatMessages($irc_pdo);
}
?>

<!DOCTYPE html>
<html lang=<?php echo $_SESSION["lang"] ?? "cs" ?>>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="styles/main.css">
    <title>Internet Relay Chat</title>
</head>
<script src="js/main.js" type="module"></script>
<body>
    <div class="pageContainer retro-fx">
        <header>
            <h1>Internet Relay Chat v1.0</h1>
            <p id='clock'>Unknown</p>

            <div id="language_option_container">
                <button <?php setLanguageButtonSelected("cs")?> id="b_lang_cs">CZ</button>
                <button <?php setLanguageButtonSelected("en")?> id="b_lang_en">EN</button>
            </div>
        </header>

        <main>
            <div id="channel_window">
                <h2><?php echo getLan("channel_container")?></h2>
                <div id="channels">
                    <div class="channel_element">
                        <p>Channel 1</p>
                    </div>
                    <div class="channel_element">
                        <p>Channel 2</p>
                    </div>
                </div>
            </div>
            <div id="chat_window">
                <h2>|current_chat_name|</h2>
                <div id="chat">
                    <div class="chat_element">
                        <div class="chat_element_author">
                            <span class="chat_time_style">@12:00 </span>
                            <p>mrFitness</p>
                        </div>
                        <div class="chat_element_content">The fitness gram pacer test is a multi-stage aerobic capacity test that gets
                            progressively more difficult as it continues.
                        </div>
                    </div>
                    <div class="chat_element">
                        <div class="chat_element_author">
                            <span class="chat_time_style">@12:00 </span>
                            <p>slugger2001</p>
                        </div>
                        <div class="chat_element_content">
                            What the hell does that mean?
                        </div>
                    </div>
                </div>
                <div id="message_input_container">
                    <textarea id="message_input" name="message_input_field" placeholder="Send message to #"></textarea>
                    <button type="submit" id="b_chat_send">Send</button>
                </div>
            </div>
        </main>
        
        <footer>
            <?php
            echo "<p>" . getLan("footer_for") . "</p>";
            echo "<p>" . getLan("footer_author") . " <a href='#'>David B.</a> 2025</p>";
            ?>
        </footer>
    </div>
    
</body>
</html>