<?php
session_start();
include 'db_connect.php';

// ตรวจสอบว่าเป็นแอดมินหรือไม่
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: home.php");
    exit();
}

// รับ ID สถานที่ท่องเที่ยวจาก URL
$spot_id = $_GET['id'];

// ลบสถานที่ท่องเที่ยวจากฐานข้อมูล
$query = "DELETE FROM categories WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $spot_id);

if ($stmt->execute()) {
    echo "<script>alert('ลบสถานที่ท่องเที่ยวสำเร็จ!'); window.location.href = 'manage_places.php';</script>";
} else {
    echo "<script>alert('เกิดข้อผิดพลาดในการลบสถานที่ท่องเที่ยว');</script>";
}

$conn->close();
?>
