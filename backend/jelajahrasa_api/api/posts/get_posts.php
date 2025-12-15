<?php
header("Content-Type: application/json");
require_once "../../config/db.php";

try {
    $sql = "
        SELECT
            p.post_id,
            p.caption,
            p.image_url,
            p.video_url,
            p.created_at,

            u.user_id,
            u.username,
            u.name,

            pl.place_id,
            pl.name AS place_name,
            pl.category,
            pl.image_url AS place_image,

            (SELECT COUNT(*) FROM comments c WHERE c.post_id = p.post_id) AS total_comments,
            (SELECT COUNT(*) FROM favorites f WHERE f.post_id = p.post_id) AS total_favorites

        FROM posts p
        JOIN users u ON p.user_id = u.user_id
        LEFT JOIN places pl ON p.place_id = pl.place_id
        ORDER BY p.created_at DESC
    ";

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();

    $posts = [];

    while ($row = $result->fetch_assoc()) {
        $posts[] = [
            "post_id" => (int)$row["post_id"],
            "caption" => $row["caption"],
            "image_url" => $row["image_url"],
            "video_url" => $row["video_url"],
            "created_at" => $row["created_at"],

            "user" => [
                "user_id" => (int)$row["user_id"],
                "username" => $row["username"],
                "name" => $row["name"]
            ],

            "place" => $row["place_id"] ? [
                "place_id" => (int)$row["place_id"],
                "name" => $row["place_name"],
                "category" => $row["category"],
                "image_url" => $row["place_image"]
            ] : null,

            "total_comments" => (int)$row["total_comments"],
            "total_favorites" => (int)$row["total_favorites"]
        ];
    }

    echo json_encode([
        "status" => true,
        "data" => $posts
    ]);

} catch (Exception $e) {
    echo json_encode([
        "status" => false,
        "message" => $e->getMessage()
    ]);
}
