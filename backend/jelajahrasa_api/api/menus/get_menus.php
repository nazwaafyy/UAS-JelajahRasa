<?php
include_once '../../config/db.php';
header('Content-Type: application/json');

$place_id = $_GET['place_id'] ?? null;

$query = "SELECT * FROM menus WHERE place_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $place_id);
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
