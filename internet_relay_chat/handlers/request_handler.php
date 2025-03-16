<?php
header("Content-Type: application/json");
header("Cache-Control: no-cache, must-revalidate");

if(!isset($_POST)){
    exit();
}

$postData = file_get_contents("php://input");
$dataArray = json_decode($postData, true);
$responseData;

switch ($dataArray["command"]) {
    case "getSign":
        $responseData = array("true");
        break;
    default:
        $responseData = array("Unknown command received!");
        break;
}

echo json_encode($responseData);