<?php
session_start();

// รวมการเชื่อมต่อฐานข้อมูล
include 'db_connect.php';

// เมื่อมีการ submit ฟอร์ม
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // รับข้อมูลจากฟอร์ม
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // ตรวจสอบว่ารหัสผ่านเป็นตัวเลขไม่เกิน 8 ตัว
    if (strlen($password) > 8) {
        echo "<script>alert('รหัสผ่านต้องไม่เกิน 8 ตัวอักษร');</script>";
    } else {
        // ตรวจสอบว่า confirm password ตรงกับ password หรือไม่
        if ($password !== $confirm_password) {
            echo "<script>alert('รหัสผ่านและยืนยันรหัสผ่านไม่ตรงกัน');</script>";
        } else {
            // การแปลงรหัสผ่านก่อนบันทึก
            $hashed_password = $password; // ใช้ password_hash()

            // เช็คว่าอีเมลนี้มีผู้ใช้งานหรือไม่
            $query = "SELECT * FROM users WHERE email = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                echo "<script>alert('อีเมลนี้มีผู้ใช้งานแล้ว');</script>";
            } else {
                // เพิ่มผู้ใช้ใหม่ลงในฐานข้อมูล
                $query = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("sss", $name, $email, $hashed_password);
                
                if ($stmt->execute()) {
                    echo "<script>alert('สมัครสมาชิกสำเร็จ'); window.location.href = 'login.php';</script>";
                } else {
                    echo "<script>alert('เกิดข้อผิดพลาดในการสมัครสมาชิก');</script>";
                }
            }
        }
    }
}
?>


<!-- ฟอร์มการสร้างบัญชี -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Thani Registration</title>
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
            <h1>สมัครสมาชิก</h1>
            <form action="register.php" method="post">
            <div class="input-group">
                    <label for="name">ชื่อ :</label>
                    <input type="text" id="name" name="name" placeholder="กรอกชื่อ" required>
                </div>
                <div class="input-group">
                    <label for="email">อีเมล :</label>
                    <input type="email" id="email" name="email" placeholder="กรอกอีเมล" required>
                </div>
                <div class="input-group">
                    <label for="password">รหัสผ่าน :</label>
                    <input type="password" id="password" name="password" placeholder="กรอกรหัสผ่าน" required>
                </div>
                <div class="input-group">
                    <label for="confirm_password">ยืนยันรหัสผ่าน :</label>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="ยืนยันรหัสผ่าน" required>
                </div>
                <button class="btn" type="submit">สมัครสมาชิก</button>
            </form>
            <div class="link-container">
                <a href="login.php">มีบัญชีแล้ว? เข้าสู่ระบบ</a>
            </div>
        </div>
        <div class="right-column">
            <img src="img/login1.png" alt="Image" />
        </div>
    </div>
</body>
</html>
