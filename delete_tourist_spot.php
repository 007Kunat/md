<?php
include 'db_connect.php';
session_start();

// ตรวจสอบว่าเป็นแอดมินหรือไม่
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // ลบสถานที่จากฐานข้อมูล
    $sql_delete = "DELETE FROM tourist_spots WHERE id = ?";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bind_param("i", $id);
    
    if ($stmt_delete->execute()) {
        header("Location: admin.php");
        exit();
    } else {
        echo "เกิดข้อผิดพลาดในการลบสถานที่!";
    }
}
?>
