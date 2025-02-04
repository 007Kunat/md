<?php
session_start();
include 'db_connect.php'; // เชื่อมต่อฐานข้อมูล

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // รับข้อมูลจากฟอร์ม
    $email = $_POST['email'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // เช็คว่ารหัสผ่านใหม่และการยืนยันรหัสตรงกันหรือไม่
    if ($new_password !== $confirm_password) {
        echo "<script>alert('รหัสผ่านใหม่และยืนยันรหัสผ่านไม่ตรงกัน!');</script>";
    } else {
        // ตรวจสอบความยาวของรหัสผ่าน
        if (strlen($new_password) > 8) {
            echo "<script>alert('รหัสผ่านต้องไม่เกิน 8 ตัวอักษร');</script>";
        } else {
            // ไม่เข้ารหัสรหัสผ่านใหม่ (ตามที่ต้องการ)
            $hashed_password = $new_password; // ส่งรหัสผ่านที่กรอกไปตรงๆ

            // ตรวจสอบว่าผู้ใช้มีอีเมลในฐานข้อมูลหรือไม่
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
                // อัปเดตรหัสผ่านใหม่
                $update_query = "UPDATE users SET password = ? WHERE email = ?";
                $update_stmt = $conn->prepare($update_query);

                if ($update_stmt === false) {
                    die("เกิดข้อผิดพลาดในการเตรียมคำสั่ง SQL: " . $conn->error);
                }

                $update_stmt->bind_param("ss", $hashed_password, $email);
                if ($update_stmt->execute()) {
                    echo "<script>alert('รหัสผ่านของคุณถูกเปลี่ยนเรียบร้อยแล้ว!'); window.location.href='login.php';</script>";
                } else {
                    echo "<script>alert('เกิดข้อผิดพลาดในการเปลี่ยนรหัสผ่าน');</script>";
                }
            } else {
                // ถ้าไม่พบอีเมลในฐานข้อมูล
                echo "<script>alert('อีเมลนี้ไม่ได้ลงทะเบียนในระบบ');</script>";
            }
        }
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Thani Forgot Password</title>
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
            <h1>ลืมรหัสผ่าน</h1>
            <form action="reset_password.php" method="post">
                <div class="input-group">
                    <label for="email">อีเมล :</label>
                    <input type="email" id="email" name="email" placeholder="กรอกอีเมลที่ใช้ลงทะเบียน" required>
                </div>
                <div class="input-group">
                    <label for="new_password">รหัสผ่านใหม่ :</label>
                    <input type="password" id="new_password" name="new_password" placeholder="กรอกรหัสผ่านใหม่" required>
                </div>
                <div class="input-group">
                    <label for="confirm_password">ยืนยันรหัสผ่านใหม่ :</label>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="ยืนยันรหัสผ่านใหม่" required>
                </div>
                <button class="btn" type="submit">เปลี่ยนรหัสผ่าน</button>
            </form>
            <div class="link-container">
                <a href="login.php">กลับไปที่หน้าล็อกอิน</a>
            </div>
        </div>
        <div class="right-column">
            <img src="img/login1.png" alt="Image" />
        </div>
    </div>
</body>
</html>
