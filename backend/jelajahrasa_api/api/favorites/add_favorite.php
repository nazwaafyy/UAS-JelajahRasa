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

// validasi minimal 1 target
if (!$post_id && !$place_id && !$menu_id) {
    echo json_encode([
        "status" => false,
        "message" => "Minimal salah satu: post_id / place_id / menu_id harus diisi"
    ]);
    exit;
}

// cek duplikat
$check = $conn->prepare("
    SELECT fav_id FROM favorites
    WHERE user_id = ?
    AND (post_id <=> ?)
    AND (place_id <=> ?)
    AND (menu_id <=> ?)
");
$check->bind_param("iiii", $user_id, $post_id, $place_id, $menu_id);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    echo json_encode([
        "status" => false,
        "message" => "Favorite sudah ada"
    ]);
    exit;
}

// insert
$stmt = $conn->prepare("
    INSERT INTO favorites (user_id, post_id, place_id, menu_id)
    VALUES (?, ?, ?, ?)
");
$stmt->bind_param("iiii", $user_id, $post_id, $place_id, $menu_id);

if ($stmt->execute()) {
    echo json_encode([
        "status" => true,
        "message" => "Favorite berhasil ditambahkan"
    ]);
} else {
    echo json_encode([
        "status" => false,
        "message" => "Gagal menambahkan favorite"
    ]);
}
