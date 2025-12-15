<?php
header("Content-Type: application/json");
include_once "../../config/db.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        "status" => false,
        "message" => "Method not allowed"
    ]);
    exit;
}

$user_id = $_POST['user_id'] ?? null;
$place_id = $_POST['place_id'] ?? null;
$people = $_POST['people'] ?? null;
$total_budget = $_POST['total_budget'] ?? null;
$total_calories = $_POST['total_calories'] ?? null;

if (!$user_id || !$place_id) {
    echo json_encode([
        "status" => false,
        "message" => "user_id dan place_id wajib diisi"
    ]);
    exit;
}

$query = "
INSERT INTO visit_history 
(user_id, place_id, people, total_budget, total_calories)
VALUES (?, ?, ?, ?, ?)
";

$stmt = $conn->prepare($query);
$stmt->bind_param(
    "iiiii",
    $user_id,
    $place_id,
    $people,
    $total_budget,
    $total_calories
);

if ($stmt->execute()) {
    echo json_encode([
        "status" => true,
        "message" => "Riwayat kunjungan berhasil ditambahkan"
    ]);
} else {
    echo json_encode([
        "status" => false,
        "message" => "Gagal menambahkan riwayat"
    ]);
}
