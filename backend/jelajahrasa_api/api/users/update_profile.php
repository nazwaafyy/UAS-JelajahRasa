<?php
header("Content-Type: application/json");
include_once("../../config/db.php");

// Ambil body JSON
$input = json_decode(file_get_contents("php://input"), true);

$user_id   = $input['user_id']   ?? null;
$name      = $input['name']      ?? null;
$bio       = $input['bio']       ?? null;
$photo_url = $input['photo_url'] ?? null;

if (!$user_id) {
    echo json_encode([
        "status" => false,
        "message" => "user_id wajib dikirim"
    ]);
    exit;
}

$sql = "UPDATE users 
        SET name = ?, bio = ?, photo_url = ?
        WHERE user_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssi", $name, $bio, $photo_url, $user_id);

if ($stmt->execute()) {
    echo json_encode([
        "status" => true,
        "message" => "Profil berhasil diperbarui"
    ]);
} else {
    echo json_encode([
        "status" => false,
        "message" => "Gagal memperbarui profil"
    ]);
}
