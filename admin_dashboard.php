<?php
session_start();

// ตรวจสอบว่า session 'role' เป็น 'admin' หรือไม่
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    // ถ้าไม่ใช่แอดมินให้พาผู้ใช้กลับไปหน้า home
    header("Location: home.php");  // เปลี่ยนเป็นเส้นทางที่ถูกต้อง
    exit();
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แดชบอร์ดแอดมิน</title>
    <style>
        body {
    font-family: 'Arial', sans-serif;
}

nav {
    background-color: #60B7FE;
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

nav .logo img {
    width: 120px;
    height: auto;
}

nav a {
    color:rgb(0, 0, 0);
    text-decoration: none;
    margin: 15px;
    font-size: 18px;
}

nav a:hover {
    color: white; /* เปลี่ยนสีเป็นสีขาวเมื่อเลื่อนเมาส์ */
}

.nav-links {
    display: flex;
    align-items: center;
}

.container {
    width: 100%;
    margin: auto;
    padding: 20px;
}

h1 {
    text-align: center;
    font-size: 40px;
}

.card {
    background-color: #BFE2FF;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    line-height: 1.5; /* เพิ่มระยะห่างระหว่างบรรทัด */
    margin: 20px 40px; /* จัดให้อยู่ตรงกลางและมีระยะห่างด้านบน/ล่าง */
    transition: transform 0.3s ease, box-shadow 0.3s ease; /* เพิ่ม transition สำหรับการเคลื่อนไหว */
}

.card:hover {
    transform: translateY(-10px); /* ขยับขึ้นเมื่อเมาส์เลื่อนไป */
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); /* เพิ่มเงาเมื่อเมาส์เลื่อนไป */
}

.card h3 {
    margin-bottom: 15px; /* เว้นระยะห่างจากเนื้อหาด้านล่าง */
    font-size: 30px;
}

.card p {
    margin-bottom: 15px; /* เว้นระยะห่างจากเนื้อหาด้านล่าง */
    font-size: 20px;
    color: #333;
}

.card a {
    font-size: 15px;
    margin-top: 10px;
    display: inline-block;
    color: #60B7FE;
    font-weight: bold;
    text-decoration: none;
    transition: transform 0.3s ease, color 0.3s ease; /* เพิ่ม transition สำหรับการเคลื่อนไหว */
}

.card a:hover {
    text-decoration: none;
    transform: scale(1.1); /* เพิ่มขนาดลิงค์เมื่อเมาส์เลื่อนไป */
    color: #0067bb; /* เปลี่ยนสีเมื่อเมาส์เลื่อนไป */
}
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav>
        <div class="logo">
            <a href="admin_dashboard.php">
                <img src="img/logo2.png" alt="Logo">
            </a>
        </div>

        <div class="nav-links">
            <a href="admin_dashboard.php">หน้าหลัก</a>
            <a href="manage_users.php">ข้อมูลผู้ใช้</a>
            <a href="manage_places.php">ข้อมูลสถานที่ท่องเที่ยว</a>
            <a href="logout.php">ออกจากระบบ</a>
        </div>
    </nav>

    <div class="container">
        <h1>MY ADMIN</h1>

        <div class="card">
            <h3>จัดการข้อมูลผู้ใช้</h3>
            <p>ดู, แก้ไข หรือ ลบข้อมูลผู้ใช้จากระบบ</p>
            <a href="manage_users.php">ไปที่การจัดการผู้ใช้</a>
        </div>

        <div class="card">
            <h3>จัดการข้อมูลสถานที่ท่องเที่ยว</h3>
            <p>ดู, เพิ่ม, แก้ไข หรือ ลบข้อมูลสถานที่ท่องเที่ยว</p>
            <a href="manage_places.php">ไปที่การจัดการสถานที่ท่องเที่ยว</a>
        </div>

    </div>
</body>
</html>
