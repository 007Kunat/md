<?php
session_start();
include 'db_connect.php';

// ตรวจสอบว่าเป็นแอดมินหรือไม่
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: home.php");
    exit();
}

// รับ ID ผู้ใช้จาก URL
$user_id = $_GET['id'];

// ลบผู้ใช้จากฐานข้อมูล
$query = "DELETE FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);

if ($stmt->execute()) {
    echo "<script>alert('ลบผู้ใช้สำเร็จ!'); window.location.href = 'manage_users.php';</script>";
} else {
    echo "<script>alert('เกิดข้อผิดพลาดในการลบผู้ใช้');</script>";
}

$conn->close();
?>
