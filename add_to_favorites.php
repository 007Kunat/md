<?php
session_start();
include 'db_connect.php';

$data = json_decode(file_get_contents("php://input"), true);
$user_id = $data['user_id'];
$place_id = $data['place_id'];

if (!$user_id || !$place_id) {
    echo json_encode(["success" => false, "message" => "ข้อมูลไม่ครบถ้วน"]);
    exit();
}

$sql = "INSERT INTO favorites (user_id, place_id) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $user_id, $place_id);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "เกิดข้อผิดพลาด"]);
}
?>
