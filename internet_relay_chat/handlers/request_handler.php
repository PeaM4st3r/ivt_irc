<?php
header("Content-Type: application/json");
header("Cache-Control: no-cache, must-revalidate");

require ".\\login_handler.php";
require ".\\common.php";

if(!isset($_POST)){
    exit();
}

// Get POST data
$requestString = file_get_contents("php://input");
$requestData = json_decode($requestString, true);
$responseData = null;

// Connect to IRC database
$db_config = parse_ini_file("./../irc_cfg.ini");
$pdo_irc = DBH\connectToDB($db_config["irc_db_server"], $db_config["irc_db_name"],
    $db_config["irc_db_username"], $db_config["irc_db_password"]);

if (!$pdo_irc) {
    $responseData = RequestStatus::makeNewLanError("error_failed_to_contact_database")->getArray();
    echo json_encode($responseData);
    exit();
}


// TO-DO: add $requestData sanitization (mainly null-checking)
switch ($requestData["command"]) {
    case "getSign":
        $responseData = DBH\getChannelSignHash($pdo_irc, $requestData["channel"])->getArray();
        break;
    case "getChat": // Returns an associative array of chat messages in the current channel.
        $responseData = DBH\getChatMessages($pdo_irc, $requestData["channel"], $requestData["offset"]);
        break;
    case "findUser": // Returns true when the queried username exists in the 'users' table.
        $responseData = DBH\findUsername($pdo_irc, $requestData["username"]);
        break;
    case "logIn":
        $responseData = USRH\logIntoAccount($pdo_irc, $requestData["username"], $requestData["password"])->getArray();
        break;
    case "logOut":
        $responseData = USRH\logOut()->getArray();
        break;
    case "createAccount":
        $responseData = USRH\createAccount($pdo_irc, $requestData["username"], $requestData["password"])->getArray();
        break;
    case "sendMessage":
        $authorPK = ($_SESSION["user_id"] ?? 0);
        $responseData = DBH\sendChatMessage($pdo_irc, $requestData["channel"], $authorPK, $requestData["message"])->getArray();
        break;
    default:
        $responseData = RequestStatus::makeNewError("Invalid command received!");
        break;
}

if ($responseData == null) {
    $responseData = RequestStatus::makeNewError("Invalid request received!");
}

echo json_encode($responseData);