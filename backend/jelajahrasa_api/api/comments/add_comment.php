<?php
header("Content-Type: application/json");
include "../../config/db.php";

$input = json_decode(file_get_contents("php://input"), true);

$post_id = $input['post_id'] ?? null;
$user_id = $input['user_id'] ?? null;
$comment_text = $input['comment_text'] ?? null;

if (!$post_id || !$user_id || !$comment_text) {
    echo json_encode([
        "status" => false,
        "message" => "post_id, user_id, dan comment_text wajib diisi"
    ]);
    exit;
}

$query = "
    INSERT INTO comments (post_id, user_id, comment_text)
    VALUES (?, ?, ?)
";

$stmt = $conn->prepare($query);
$stmt->bind_param("iis", $post_id, $user_id, $comment_text);

if ($stmt->execute()) {
    echo json_encode([
        "status" => true,
        "message" => "Komentar berhasil ditambahkan"
    ]);
} else {
    echo json_encode([
        "status" => false,
        "message" => "Gagal menambahkan komentar"
    ]);
}
