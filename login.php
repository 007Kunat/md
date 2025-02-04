<?php
session_start();

// รวมการเชื่อมต่อฐานข้อมูล
include 'db_connect.php';

// เช็คว่าเมื่อมีการ submit แบบฟอร์มหรือไม่
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // รับข้อมูลจากฟอร์ม
    $email = $_POST['email'];
    $password = $_POST['password'];

    // ค้นหาผู้ใช้จากฐานข้อมูล
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query); // ใช้ prepare query

    if ($stmt === false) {
        // ถ้าเตรียมคำสั่ง SQL ไม่สำเร็จ ให้แสดงข้อผิดพลาด
        die("เกิดข้อผิดพลาดในการเตรียมคำสั่ง SQL: " . $conn->error);
    }

    // การ bind parameter
    $stmt->bind_param("s", $email); // binding parameter
    $stmt->execute();
    $result = $stmt->get_result();

    // ถ้าพบผู้ใช้
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // ตรวจสอบรหัสผ่าน
        if ($password == $user['password']) { // เช็คว่า password ที่กรอกตรงกับในฐานข้อมูล
            // บันทึกข้อมูลลงใน session
            $_SESSION['user_id'] = $user['id'];  // ตั้งค่า user_id
            $_SESSION['user_email'] = $user['email'];  // ตั้งค่า email
            $_SESSION['role'] = $user['role']; // เก็บ role ของผู้ใช้ใน session

            // ตรวจสอบว่าเป็นแอดมินหรือไม่
            if ($_SESSION['role'] == 'admin') {
                // ถ้าเป็นแอดมินให้ไปที่หน้าแอดมิน
                header("Location: admin_dashboard.php"); // เปลี่ยนไปหน้าแอดมิน
                exit();
            } else {
                // ถ้าเป็นผู้ใช้ทั่วไปให้ไปที่หน้า home
                header("Location: home.php");
                exit();
            }
        } else {
            echo "<script>alert('รหัสผ่านไม่ถูกต้อง!');</script>";
        }
    } else {
        echo "<script>alert('อีเมลไม่ถูกต้อง!');</script>";
    }
}

$conn->close();
?>


<!-- ฟอร์มการเข้าสู่ระบบ -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Thani Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
    /* Reset styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f7fc;
        }

        .login-container {
            display: flex;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #ddd;
        }

        /* Left Column (Form Section) */
        .left-column {
            flex: 1;
            padding: 3rem;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            background-color: #fff;
        }

        .logo {
            margin-bottom: 20px;
        }

        .logo img {
            width: 100px;
        }

        h1 {
            font-size: 32px;
            color: #333;
            margin-bottom: 20px;
        }

        .input-group {
            margin-bottom: 20px;
            width: 100%;
        }

        .input-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            margin-bottom: 10px;
        }

        .input-group input:focus {
            outline: none;
            border-color: #60B7FE;
        }

        .input-group label {
            font-size: 14px;
            color: #777;
            margin-bottom: 5px;
            display: inline-block;
        }

        .btn {
            background-color: #60B7FE;
            color: white;
            padding: 14px;
            font-size: 18px;
            border-radius: 5px;
            width: 100%;
            cursor: pointer;
            border: none;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #0067bb;
        }

        .link-container {
            display: flex;
            justify-content: space-between;
            width: 100%;
            margin-top: 15px;
        }

        .link-container a {
            color: #60B7FE;
            font-size: 14px;
            text-decoration: none;
        }

        .link-container a:hover {
            text-decoration: underline;
        }

        /* Right Column (Image Section) */
        .right-column {
            flex: 1;
            background-color: #60B7FE;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .right-column img {
            max-width: 80%;
            height: auto;
        }

    </style>
</head>
<body>
    <div class="login-container">
        <div class="left-column">
            <div class="logo">
                <img src="img/logo.png" alt="Logo">
            </div>
            <h1>ยินดีต้อนรับ</h1>
            <form action="login.php" method="post">
                <div class="input-group">
                    <label for="email">อีเมล :</label>
                    <input type="email" id="email" name="email" placeholder="กรอกอีเมล" required>
                </div>
                <div class="input-group">
                    <label for="password">รหัสผ่าน :</label>
                    <input type="password" id="password" name="password" placeholder="กรอกรหัสผ่าน" required>
                </div>
                <button class="btn" type="submit">เข้าสู่ระบบ</button>
            </form>
            <div class="link-container">
                <a href="forgot-password.html">ลืมรหัสผ่าน?</a>
                <a href="register.php">สร้างบัญชีใหม่</a>
            </div>
        </div>
        <div class="right-column">
            <img src="img/login1.png" alt="Image" />
        </div>
    </div>
</body>
</html>
