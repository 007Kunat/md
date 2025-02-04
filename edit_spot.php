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

// ดึงข้อมูลสถานที่ท่องเที่ยวจากฐานข้อมูล
$query = "SELECT * FROM categories WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $spot_id);
$stmt->execute();
$result = $stmt->get_result();
$spot = $result->fetch_assoc();

// ตรวจสอบว่าเจอข้อมูลสถานที่ท่องเที่ยวหรือไม่
if (!$spot) {
    echo "ไม่พบข้อมูลสถานที่ท่องเที่ยว!";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // รับข้อมูลจากฟอร์ม
    $name = $_POST['name'];
    $description = $_POST['description'];
    $image_url = $_POST['image_url'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];

    // อัปเดตข้อมูลสถานที่ท่องเที่ยวในฐานข้อมูล
    $update_query = "UPDATE categories SET name = ?, description = ?, image_url = ?, latitude = ?, longitude = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("sssddi", $name, $description, $image_url, $latitude, $longitude, $spot_id);

    if ($update_stmt->execute()) {
        echo "<script>alert('อัปเดตสถานที่ท่องเที่ยวสำเร็จ!'); window.location.href = 'manage_places.php';</script>";
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
    <title>แก้ไขสถานที่ท่องเที่ยว</title>
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

        /* ฟอร์มการแก้ไขสถานที่ท่องเที่ยว */
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

        input[type="text"], textarea {
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

    <!-- ฟอร์มการแก้ไขสถานที่ท่องเที่ยว -->
    <div class="container">
        <h1>แก้ไขสถานที่ท่องเที่ยว</h1>
        <div class="form-container">
            <form action="edit_spot.php?id=<?php echo $spot['id']; ?>" method="POST">
                <label for="name">ชื่อสถานที่:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($spot['NAME']); ?>" required>

                <label for="description">คำอธิบาย:</label>
                <textarea id="description" name="description" required><?php echo htmlspecialchars($spot['description']); ?></textarea>

                <label for="image_url">ลิงค์ภาพสถานที่:</label>
                <input type="text" id="image_url" name="image_url" value="<?php echo htmlspecialchars($spot['image_url']); ?>" required>

                <label for="latitude">ละติจูด:</label>
                <input type="text" id="latitude" name="latitude" value="<?php echo htmlspecialchars($spot['latitude']); ?>" required>

                <label for="longitude">ลองจิจูด:</label>
                <input type="text" id="longitude" name="longitude" value="<?php echo htmlspecialchars($spot['longitude']); ?>" required>

                <button type="submit">บันทึกการเปลี่ยนแปลง</button>
            </form>
        </div>
    </div>
</body>
</html>
