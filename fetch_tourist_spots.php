<?php
include 'db_connect.php';

// ดึงข้อมูลสถานที่ท่องเที่ยว
$query = "SELECT id, name, description, image_url, latitude, longitude FROM tourist_spots";
$result = $conn->query($query);

$tourist_spots = [];

while ($row = $result->fetch_assoc()) {
    $tourist_spots[] = $row;
}

header('Content-Type: application/json');
echo json_encode($tourist_spots);
?>
