<?php
header("Content-Type: application/json");
include_once "../../config/db.php";

if (!isset($_GET['user_id'])) {
    echo json_encode([
        "status" => false,
        "message" => "user_id wajib dikirim"
    ]);
    exit;
}

$user_id = $_GET['user_id'];

$query = "
SELECT 
    vh.history_id,
    vh.visited_at,
    vh.people,
    vh.total_budget,
    vh.total_calories,
    p.place_id,
    p.name AS place_name,
    p.image_url,
    p.category
FROM visit_history vh
JOIN places p ON vh.place_id = p.place_id
WHERE vh.user_id = ?
ORDER BY vh.visited_at DESC
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();
$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode([
    "status" => true,
    "data" => $data
]);
