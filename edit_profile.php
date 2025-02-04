<?php
session_start();
include 'db_connect.php'; // เชื่อมต่อกับฐานข้อมูล

// ตรวจสอบว่าผู้ใช้ล็อกอินหรือยัง
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // ถ้าไม่ล็อกอินให้ไปหน้า login
    exit();
}

// ดึงข้อมูลผู้ใช้จากฐานข้อมูล
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    // กรอกข้อมูลผู้ใช้ลงในตัวแปร
    $name = $user['name'];
    $email = $user['email'];
} else {
    echo "ไม่พบข้อมูลผู้ใช้";
    exit();
}

// การอัปเดตข้อมูลผู้ใช้
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];

    // ปรับปรุงข้อมูลในฐานข้อมูล
    $update_query = "UPDATE users SET name = ?, email = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("ssi", $name, $email, $user_id);

    if ($update_stmt->execute()) {
        echo "<script>alert('ข้อมูลของคุณได้รับการอัปเดต'); window.location.href = 'home.php';</script>";
    } else {
        echo "เกิดข้อผิดพลาดในการอัปเดตข้อมูล";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขโปรไฟล์</title>
    <style>
        /* Reset styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }

        /* Navbar Styles */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #60B7FE;
            padding: 1rem 2rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .navbar .logo img {
            width: 100px;
            height: auto;
        }

        .navbar .nav-center {
            flex: 1;
            text-align: center;
        }

        .navbar .part1 {
            color: #000000;
            font-size: 40px;
            font-weight: bold;
        }

        .part2 {
            color: #ffffff;
            font-size: 40px;
            font-weight: bold;
        }

        .navbar .nav-right {
            display: flex;
            align-items: center;
        }

        .navbar .menu-icon {
            justify-content: center;
            align-items: center;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .navbar .menu-icon img {
            width: 50px;
            height: 50px;
        }

        .navbar .menu-icon:hover {
            background-color: #1863a0;
        }

        /* Dropdown Menu */
        .dropdown-menu {
            display: none;
            position: absolute;
            top: 70px;
            right: 30px;
            background-color: #BFE2FF;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            padding: 0.3rem;
            padding-left: -4rem;
            padding-right: -rem;
            margin-top: 1rem;
            width: 150px;
        }

        .dropdown-menu a {
            display: block;
            color: #000000;
            padding: 0.4rem;
            margin: 0.3rem;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            font-size: 1rem;
        }

        .dropdown-menu a:hover {
            background-color: #f1f1f1;
        }

        /* Container for content */
        .container {
            width: 80%;
            max-width: 600px;
            margin: 3rem auto;
            padding: 2rem;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #60B7FE;
            margin-bottom: 2rem;
        }

        label {
            font-size: 1.1rem;
            color: #333;
            margin-bottom: 8px;
            display: block;
        }

        input[type="text"], input[type="email"], textarea {
            width: 100%;
            padding: 1rem;
            margin: 15px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 1rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        input[type="text"]:focus, input[type="email"]:focus, textarea:focus {
            border-color: #60B7FE;
            box-shadow: 0 0 5px rgba(96, 183, 254, 0.6);
        }

        textarea {
            resize: vertical;
            min-height: 150px;
        }

        button {
            background-color: #60B7FE;
            color: white;
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        button:hover {
            background-color: #1863a0;
            transform: scale(1.05);
        }
       
        footer {
        background-color: #60B7FE;
        color: rgb(0, 0, 0);
        text-align: center;
        padding: 1rem;
        font-size: 1rem;
        position: fixed;
        bottom: 0;
        width: 100%;
        z-index: 10;
    }

    </style>
</head>
<body>

   <!-- Navbar -->
   <nav class="navbar">
        <div class="logo">
            <a href="home.php">
                <img src="img/logo2.png" alt="Logo">
            </a>
        </div>

        <div class="nav-center">
            <span class="part1">อยาก</span>
            <span class="part2">ไปที่ไหน?</span>
        </div>

        <div class="nav-right">
            <div class="menu-icon" onclick="toggleDropdown()">
                <img src="img/menu.png" alt="Logo">
            </div>
            <div class="dropdown-menu" id="dropdown-menu">
                <a href="edit_profile.php">แก้ไขโปรไฟล์</a>
                <a href="favorites.php">รายการโปรด</a>
                <a href="logout.php">ออกจากระบบ</a>
            </div>
        </div>
    </nav>

    <!-- Profile Edit Form -->
    <div class="container">
        <h1>แก้ไขโปรไฟล์</h1>

        <form action="update_profile.php" method="POST">
            <label for="name">ชื่อ</label>
            <input type="text" id="name" name="name" value="<?php echo $user['name']; ?>" required>

            <label for="email">อีเมล</label>
            <input type="email" id="email" name="email" value="<?php echo $user['email']; ?>" required>

            <button type="submit">บันทึกการแก้ไข</button>
        </form>
    </div>

    <footer>
        <p>ติดต่อเรา: 095-4875455 | Email: SURATTHANI555@gmail.com</p>
    </footer>

    <script>
        // ฟังก์ชันที่ใช้เปิด/ปิดเมนูดรอปดาวน์
        function toggleDropdown() {
            var dropdown = document.getElementById('dropdown-menu');

            // ถ้าเมนูเปิดอยู่ให้ปิดเมนู, ถ้าเมนูปิดอยู่ให้เปิดเมนู
            if (dropdown.style.display === "block") {
                dropdown.style.display = "none";
            } else {
                dropdown.style.display = "block";
            }
        }

        // ปิดเมนูดรอปดาวน์เมื่อคลิกที่ตำแหน่งอื่นบนหน้า
        window.onclick = function(event) {
            var dropdown = document.getElementById('dropdown-menu');
            var menuIcon = document.querySelector('.menu-icon');

            // ถ้าคลิกที่นอกเมนูหรือไอคอนจะทำให้เมนูปิด
            if (!menuIcon.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.style.display = "none";
            }
        }
    </script>

</body>
</html>
