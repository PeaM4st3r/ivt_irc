<?php
header("Content-Type: application/json");
header("Cache-Control: no-cache, must-revalidate");

require ".\\db_handler.php";

if(!isset($_POST)){
    exit();
}

// Get POST data
$requestData = file_get_contents("php://input");
$request = json_decode($requestData, true);
$responseData;

// Connect to IRC database
$db_config = parse_ini_file("./../irc_cfg.ini");
$irc_db = DBH\connectToDB($db_config["irc_db_server"], $db_config["irc_db_name"],
    $db_config["irc_db_username"], $db_config["irc_db_password"]);


switch ($request["command"]) {
    case "getSign": // Returns the sign of the current channel
        $responseData = DBH\getChannelSignHash($irc_db, $request["channel"]);
        break;
    default:
        $responseData = array("Unknown command received!");
        break;
}

echo json_encode($responseData);