<?php
// การตั้งค่าการเชื่อมต่อฐานข้อมูล
$host = "localhost"; // ชื่อโฮสต์ (ค่าเริ่มต้นคือ localhost)
$user = "root";      // ชื่อผู้ใช้ MySQL (ค่าเริ่มต้นคือ root)
$password = "";      // รหัสผ่าน MySQL (ค่าเริ่มต้นว่าง)
$dbname = "tourism"; // ชื่อฐานข้อมูลของคุณ

// สร้างการเชื่อมต่อ
$conn = new mysqli($host, $user, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
