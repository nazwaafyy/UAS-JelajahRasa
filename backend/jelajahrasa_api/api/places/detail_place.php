<?php
header("Content-Type: application/json");
include_once "../../config/db.php";

if (!isset($_GET['place_id'])) {
    echo json_encode([
        "status" => false,
        "message" => "place_id wajib dikirim"
    ]);
    exit;
}

$place_id = $_GET['place_id'];

$query = "SELECT * FROM places WHERE place_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $place_id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode([
        "status" => false,
        "message" => "Tempat tidak ditemukan"
    ]);
    exit;
}

$data = $result->fetch_assoc();

echo json_encode([
    "status" => true,
    "data" => $data
]);
