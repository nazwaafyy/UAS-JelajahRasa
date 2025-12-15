<?php
header("Content-Type: application/json");
include_once("../../config/db.php");

$user_id = $_GET['user_id'] ?? null;

if (!$user_id) {
    echo json_encode([
        "status" => false,
        "message" => "user_id wajib dikirim"
    ]);
    exit;
}

$sql = "SELECT 
            user_id,
            username,
            name,
            bio,
            photo_url,
            created_at
        FROM users
        WHERE user_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
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

echo json_encode([
    "status" => true,
    "data" => $user
]);
