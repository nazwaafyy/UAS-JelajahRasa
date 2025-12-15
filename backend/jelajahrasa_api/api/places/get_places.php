<?php
include_once '../../config/db.php';
header('Content-Type: application/json');

$query = "SELECT * FROM places ORDER BY created_at DESC";
$result = $conn->query($query);

$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode([
    "status" => true,
    "data" => $data
]);
