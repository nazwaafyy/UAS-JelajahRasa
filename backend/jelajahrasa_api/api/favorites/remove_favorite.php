<?php
header("Content-Type: application/json");
include_once("../../config/db.php");

$user_id  = $_POST['user_id'] ?? null;
$post_id  = $_POST['post_id'] ?? null;
$place_id = $_POST['place_id'] ?? null;
$menu_id  = $_POST['menu_id'] ?? null;

if (!$user_id) {
    echo json_encode([
        "status" => false,
        "message" => "User ID wajib diisi"
    ]);
    exit;
}

if (!$post_id && !$place_id && !$menu_id) {
    echo json_encode([
        "status" => false,
        "message" => "Minimal salah satu: post_id / place_id / menu_id"
    ]);
    exit;
}

$stmt = $conn->prepare("
    DELETE FROM favorites
    WHERE user_id = ?
    AND (post_id <=> ?)
    AND (place_id <=> ?)
    AND (menu_id <=> ?)
");
$stmt->bind_param("iiii", $user_id, $post_id, $place_id, $menu_id);

if ($stmt->execute()) {
    echo json_encode([
        "status" => true,
        "message" => "Favorite berhasil dihapus"
    ]);
} else {
    echo json_encode([
        "status" => false,
        "message" => "Gagal menghapus favorite"
    ]);
}
