<?php
header("Content-Type: application/json");
include "../../config/db.php";

$post_id = $_GET['post_id'] ?? null;

if (!$post_id) {
    echo json_encode([
        "status" => false,
        "message" => "post_id wajib diisi"
    ]);
    exit;
}

$query = "
    SELECT 
        c.comment_id,
        c.comment_text,
        c.created_at,
        u.user_id,
        u.username,
        u.name
    FROM comments c
    JOIN users u ON c.user_id = u.user_id
    WHERE c.post_id = ?
    ORDER BY c.created_at ASC
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $post_id);
$stmt->execute();
$result = $stmt->get_result();

$comments = [];

while ($row = $result->fetch_assoc()) {
    $comments[] = [
        "comment_id" => (int) $row['comment_id'],
        "comment_text" => $row['comment_text'],
        "created_at" => $row['created_at'],
        "user" => [
            "user_id" => (int) $row['user_id'],
            "username" => $row['username'],
            "name" => $row['name']
        ]
    ];
}

echo json_encode([
    "status" => true,
    "data" => $comments
]);
