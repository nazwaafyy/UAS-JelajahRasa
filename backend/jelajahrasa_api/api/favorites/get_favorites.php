<?php
header("Content-Type: application/json");
include_once("../../config/db.php");

$user_id = $_GET['user_id'] ?? null;

if (!$user_id) {
    echo json_encode([
        "status" => false,
        "message" => "User ID wajib diisi"
    ]);
    exit;
}

$stmt = $conn->prepare("
    SELECT 
        f.fav_id,
        f.created_at,

        -- post
        p.post_id,
        p.caption,
        p.image_url AS post_image,

        -- place
        pl.place_id,
        pl.name AS place_name,
        pl.category,
        pl.image_url AS place_image

    FROM favorites f
    LEFT JOIN posts p ON f.post_id = p.post_id
    LEFT JOIN places pl ON 
        pl.place_id = COALESCE(p.place_id, f.place_id)

    WHERE f.user_id = ?
    ORDER BY f.created_at DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();
$data = [];

while ($row = $result->fetch_assoc()) {

    $type = null;
    if ($row['post_id']) $type = 'post';
    elseif ($row['place_id']) $type = 'place';

    $data[] = [
        "fav_id" => $row['fav_id'],
        "type" => $type,
        "created_at" => $row['created_at'],
        "post" => $row['post_id'] ? [
            "post_id" => $row['post_id'],
            "caption" => $row['caption'],
            "image_url" => $row['post_image']
        ] : null,
        "place" => $row['place_id'] ? [
            "place_id" => $row['place_id'],
            "name" => $row['place_name'],
            "category" => $row['category'],
            "image_url" => $row['place_image']
        ] : null
    ];
}

echo json_encode([
    "status" => true,
    "data" => $data
]);
