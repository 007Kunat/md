<?php
session_start();
session_unset(); // ลบข้อมูลใน session
session_destroy(); // ทำลาย session

header("Location: login.php"); // รีไดเร็กต์ไปที่หน้า login
exit();
?>
