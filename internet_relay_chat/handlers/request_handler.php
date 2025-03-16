<?php
header("Content-Type: application/json");
header("Cache-Control: no-cache, must-revalidate");

$testAssoc = array(
    0 => array(
        "username" => "My Username",
        "text" => "this is my test message - there are many like it, but this one is mine",
        "date" => "12:00 2025"
    )
);


echo json_encode($testAssoc);
