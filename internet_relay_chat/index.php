<?php
require ".\\handlers\\common.php";
define("MAIN_LABEL", "Internet Relay Chat - " . getLan("title_index"));
define("NAV_LOGIN", getLan("nav_user_account"));
//$mainElementClass = (isset($_SESSION["user_id"]) ? "" : "no_user");

?>

<!DOCTYPE html>
<html lang=<?php echo $_SESSION["lang"] ?? "cs" ?>>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="styles/base.css">
    <link rel="stylesheet" href="styles/main.css">
    <script defer src="js/main.js" type="module"></script>
    <title><?php echo MAIN_LABEL; ?></title>
</head>

<body>
    <div class="pageContainer retro-fx">
        <?php require ".\\layout\\header.php"; ?>

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
                <h2>institut</h2>
                <div id="chat">
                    <!-- <div class="chat_element">
                        <div class="chat_element_author">
                            <p>mrFitness</p>
                            <span class="chat_time_style">@12:00 </span>
                        </div>
                        <div class="chat_element_content">The fitness gram pacer test is a multi-stage aerobic capacity test that gets
                            progressively more difficult as it continues.
                        </div>
                    </div> -->
                </div>
                <div id="message_input_container">
                    <textarea id="message_input" name="message_input_field"
                        placeholder='<?php echo getLan("message_input_placeholder")?>'></textarea>
                    <button type="submit" id="b_chat_send"><?php echo getLan("button_send_message");?></button>
                </div>
            </div>
        </main>
        
        <?php require ".\\layout\\footer.php"; ?>
    </div>
    
</body>
</html>