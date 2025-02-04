<?php
session_start();

// ตรวจสอบว่าเป็นแอดมินหรือไม่
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: home.php");
    exit();
}

// เชื่อมต่อฐานข้อมูล
include 'db_connect.php';

// รับ ID ผู้ใช้จาก URL
$user_id = $_GET['id'];

// ดึงข้อมูลผู้ใช้จากฐานข้อมูล
$query = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// ตรวจสอบว่าเจอข้อมูลผู้ใช้หรือไม่
if (!$user) {
    echo "ไม่พบข้อมูลผู้ใช้!";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // รับข้อมูลจากฟอร์ม
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    // อัปเดตข้อมูลผู้ใช้ในฐานข้อมูล
    $update_query = "UPDATE users SET name = ?, email = ?, role = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("sssi", $name, $email, $role, $user_id);
    
    if ($update_stmt->execute()) {
        echo "<script>alert('อัปเดตข้อมูลผู้ใช้สำเร็จ!'); window.location.href = 'manage_users.php';</script>";
    } else {
        echo "<script>alert('เกิดข้อผิดพลาดในการอัปเดตข้อมูล');</script>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขข้อมูลผู้ใช้</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"> <!-- ไอคอนจาก Font Awesome -->
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        /* Navbar */
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
            color: rgb(0, 0, 0);
            text-decoration: none;
            margin: 15px;
            font-size: 18px;
        }

        nav a:hover {
            color: white;
        }

        .nav-links {
            display: flex;
            align-items: center;
        }

        /* สำหรับ Container */
        .container {
            width: 80%;
            margin: auto;
            padding: 25px;
        }

        h1 {
            text-align: center;
            font-size: 40px;
            margin-bottom: 30px;
        }

        /* ฟอร์มการแก้ไขผู้ใช้ */
        .form-container {
            background-color: #fff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: auto;
        }

        label {
            font-size: 1.1rem;
            color: #333;
            margin-bottom: 8px;
            display: block;
        }

        input[type="text"], input[type="email"], select {
            width: 100%;
            padding: 0.75rem;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        button {
            background-color: #60B7FE;
            color: white;
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-left: auto; /* เลื่อนปุ่มไปทางขวา */
            display: block; /* ให้ปุ่มแสดงเป็นบล็อก */
        }

        button:hover {
            background-color: #1863a0;
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

    <!-- ฟอร์มการแก้ไขข้อมูลผู้ใช้ -->
    <div class="container">
        <h1>แก้ไขข้อมูลผู้ใช้</h1>
        <div class="form-container">
            <form action="edit_user.php?id=<?php echo $user['id']; ?>" method="POST">
                <label for="name">ชื่อผู้ใช้:</label>
                <input type="text" id="name" name="name" value="<?php echo $user['name']; ?>" required>

                <label for="email">อีเมล:</label>
                <input type="email" id="email" name="email" value="<?php echo $user['email']; ?>" required>

                <label for="role">บทบาท:</label>
                <select id="role" name="role">
                    <option value="user" <?php if ($user['role'] == 'user') echo 'selected'; ?>>ผู้ใช้</option>
                    <option value="admin" <?php if ($user['role'] == 'admin') echo 'selected'; ?>>แอดมิน</option>
                </select>

                <button type="submit">บันทึกการเปลี่ยนแปลง</button>
            </form>
        </div>
    </div>
</body>
</html>
