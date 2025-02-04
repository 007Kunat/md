<?php
include 'db_connect.php';

// รับค่า ID จาก URL
$spot_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// ดึงข้อมูลสถานที่ท่องเที่ยวจากฐานข้อมูล
$query = "SELECT * FROM tourist_spots WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $spot_id);
$stmt->execute();
$result = $stmt->get_result();
$spot = $result->fetch_assoc();


// ตรวจสอบว่าเจอข้อมูลหรือไม่
if (!$spot) {
    echo "<p>ไม่พบข้อมูลสถานที่ท่องเที่ยว</p>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายละเอียดสถานที่ท่องเที่ยว</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
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

        /* Logo */
        .navbar .logo img {
            width: 100px;
            height: auto;
        }

        /* Center Section */
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

        /* Right Section with Menu Icon */
        .navbar .nav-right {
            display: flex;
            align-items: center;
        }

        .navbar .nav-right .menu-icon {
            justify-content: center;
            align-items: center;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .navbar .nav-right .menu-icon img {
            width: 50px;
            height: 50px;
        }

        .navbar .nav-right .menu-icon:hover {
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
            padding: 0.4rem ;
            margin: 0.3rem;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            font-size: 1rem;
        }

        .dropdown-menu a:hover {
            background-color: #f1f1f1;
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


        .container {
            display: flex;
            margin: 2rem auto;
            padding: 1rem;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            max-width: 1200px;
        }

        .image-section {
            margin-right: 1rem;
        }

        .image-section img {
            height: 300px;
            border-radius: 5px;
            object-fit: cover;
            margin-top: 80px;
        }

        .details-section {
            padding: 1rem;
        }

        .details-section h1 {
            font-size: 2.5rem;
            color:#007AFF;
        }

        .details-section p {
            font-size: 1.2rem;
            color: #555;
        }

        .map {
            margin-top: 1rem;
        }

        .map iframe {
            width: 90%;
            height: 200px;
            border: none;
            border-radius: 8px;
        }

        .view-more {
            display: inline-block;
            margin-top: 1rem;
            padding: 0.75rem 1.5rem;
            background-color: #60B7FE;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 1rem;
            transition: background-color 0.3s;
        }

        .view-more:hover {
            background-color: #0067bb;
        }
    </style>
</head>
<body>
<nav class="navbar">
        <!-- Logo Section -->
        <div class="logo">
            <a href="home.php">
            <img src="img/logo2.png" alt="Logo">
            </a>
        </div>

        <!-- Center Section -->
        <div class="nav-center">
            <span class="part1">อยาก</span> 
            <span class="part2">ไปที่ไหน?</span>
        </div>

        <!-- Right Section with Menu Icon -->
        <div class="nav-right">
            <div class="menu-icon" onclick="toggleDropdown()">
                <img src="img/menu.png" alt="Logo">
            </div>
            <!-- Dropdown Menu -->
            <div class="dropdown-menu" id="dropdown-menu">
                <a href="edit_profile.php">แก้ไขโปรไฟล์</a>
                <a href="favorites.php">รายการโปรด</a>
                <a href="logout.php">ออกจากระบบ</a>
            </div>
        </div>
    </nav>

    <!-- Add Font Awesome for the Menu Icon -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

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

    <!-- Main Content -->
    <div class="container">
        <!-- รูปภาพทางซ้าย -->
        <div class="image-section">
            <img src="<?php echo htmlspecialchars($spot['image_url']); ?>" alt="<?php echo htmlspecialchars($spot['name']); ?>">
        </div>

        <!-- รายละเอียดทางขวา -->
        <div class="details-section">
            <h1><?php echo htmlspecialchars($spot['name']); ?></h1>
            <p><?php echo htmlspecialchars($spot['description']); ?></p>
            
            <!-- แผนที่ -->
            <div class="map">
                <iframe
                    src="https://www.google.com/maps/embed/v1/place?key=AIzaSyBEvMe9qRl4PccTLklhZrYGRU9Mzm_RMoo&q=<?php echo $spot['latitude']; ?>,<?php echo $spot['longitude']; ?>"
                    allowfullscreen>
                </iframe>
                <a href="https://www.google.com/maps?q=<?php echo $spot['latitude']; ?>,<?php echo $spot['longitude']; ?>" 
                   target="_blank" 
                   class="view-more">
                    ดูตำแหน่งเพิ่มเติม
                </a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <p>ติดต่อเรา: 095-4875455 | Email: SURATTHANI555@gmail.com</p>
    </footer>
</body>
</html>
