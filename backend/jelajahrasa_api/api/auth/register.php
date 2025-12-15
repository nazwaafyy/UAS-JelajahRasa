<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json; charset=UTF-8");
header("Content-Type: application/json");
include_once("../../config/db.php");

$username = $_POST['username'] ?? null;
$password = $_POST['password'] ?? null;
$name     = $_POST['name'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(200);
  exit();
}

if (!$username || !$password || !$name) {
    echo json_encode([
        "status" => false,
        "message" => "Username, password, dan nama wajib diisi"
    ]);
    exit;
}

/* Cek username sudah ada */
$check = $conn->prepare("SELECT user_id FROM users WHERE username = ?");
$check->bind_param("s", $username);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    echo json_encode([
        "status" => false,
        "message" => "Username sudah digunakan"
    ]);
    exit;
}

/* Simpan user */
$stmt = $conn->prepare("
    INSERT INTO users (username, password, name)
    VALUES (?, ?, ?)
");
$stmt->bind_param("sss", $username, $password, $name);

if ($stmt->execute()) {
    echo json_encode([
        "status" => true,
        "message" => "Registrasi berhasil"
    ]);
} else {
    echo json_encode([
        "status" => false,
        "message" => "Registrasi gagal"
    ]);
}
