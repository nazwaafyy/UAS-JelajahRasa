<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json; charset=UTF-8");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(200);
  exit();
}


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        "status" => false,
        "message" => "Method not allowed"
    ]);
    exit;
}

include_once(__DIR__ . '/../../config/db.php');

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if ($username === '' || $password === '') {
    echo json_encode([
        "status" => false,
        "message" => "Username dan password wajib diisi"
    ]);
    exit;
}

$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode([
        "status" => false,
        "message" => "User tidak ditemukan"
    ]);
    exit;
}

$user = $result->fetch_assoc();

/*
⚠️ Karena password di DB kamu BELUM hash
langsung bandingkan dulu
*/
if ($password !== $user['password']) {
    echo json_encode([
        "status" => false,
        "message" => "Password salah"
    ]);
    exit;
}

echo json_encode([
    "status" => true,
    "message" => "Login berhasil",
    "data" => [
        "user_id" => $user['user_id'],
        "username" => $user['username'],
        "name" => $user['name']
    ]
]);
