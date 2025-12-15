<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "jelajahrasa_db";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die(json_encode([
        "status" => false,
        "message" => "Database connection failed"
    ]));
}
