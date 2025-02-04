<?php
// รวมการเชื่อมต่อฐานข้อมูล
include 'db_connect.php'; 

// รับคำค้นหาจาก URL
$query = isset($_GET['query']) ? $_GET['query'] : '';

// ถ้าไม่มีคำค้นหาจะไม่ดำเนินการค้นหาข้อมูลและเปลี่ยนหน้าไปยังหน้า home.php
if (empty($query)) {
    echo "<script>alert('กรุณากรอกคำค้นหาก่อน'); window.location.href='home.php';</script>";
    exit(); // ออกจากสคริปต์ทันที
}

// คำค้นหาที่ใช้ในการค้นหา
$search_term = "%" . $query . "%"; // การหาคำที่ตรงกับคำค้นหา

// ดึงข้อมูลจากตาราง categories
$sql_categories = "SELECT id, name, description, image_url, latitude, longitude FROM categories WHERE name LIKE ?";
$stmt_categories = $conn->prepare($sql_categories);
$stmt_categories->bind_param("s", $search_term);
$stmt_categories->execute();
$result_categories = $stmt_categories->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ผลการค้นหา</title>
    <style>
        /* Reset styles */
        * {
            box-sizing: border-box;
            border-radius: 8px;
        }

        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            background-color: #f4f4f4;
            color: #333;
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

        /* Container for content */
        .container {
            background-color: #f4f4f4;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            padding: 1.5rem;
            background-color: #e6f7ff;
            margin: 1.5rem auto;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            font-size: 30px;
            color: #000000;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); /* 3 columns */
            gap: 2rem;
            margin: 2rem auto;
            padding: 0 1rem;
            max-width: 1200px;
        }

        .card {
            background-color: #daeeff;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            padding: 2rem;
            position: relative;
            transition: transform 0.3s ease;
        }

        .card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .card h3 {
            font-size: 25px;
            margin-top: 10px;
            color: #333;
        }

        .card p {
            color: #666;
            font-size: 16px;
            margin: 5px 0;
        }

        .card a {
            color: #60B7FE;
            text-decoration: none;
            font-size: 18px;
            font-weight: bold;
        }

        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .heart-icon {
            position: absolute;
            bottom: 20px;
            right: 40px;
            font-size: 30px;
            color: rgb(192, 22, 22);
            cursor: pointer;
        }

        .heart-icon:hover {
            color: #f38f8f;
        }

        footer {
            background-color: #60B7FE;
            color: rgb(0, 0, 0);
            text-align: center;
            padding: 1rem;
            font-size: 1rem;
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

    <script>
        // ฟังก์ชันที่ใช้เปิด/ปิดเมนูดรอปดาวน์
        function toggleDropdown() {
            var dropdown = document.getElementById('dropdown-menu');

            if (dropdown.style.display === "block") {
                dropdown.style.display = "none";
            } else {
                dropdown.style.display = "block";
            }
        }

        window.onclick = function(event) {
            var dropdown = document.getElementById('dropdown-menu');
            var menuIcon = document.querySelector('.menu-icon');

            if (!menuIcon.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.style.display = "none";
            }
        }
    </script>

    <div class="container">
        <h1>ผลลัพธ์สำหรับการค้นหา :</h1>
    </div>

    <section class="grid">
    <?php
    if ($result_categories->num_rows > 0) {
        while ($row = $result_categories->fetch_assoc()) {

            $detailLink = "details.php?id=" . $row["id"];
            // สร้างลิงก์ Google Maps
            $googleMapsLink = "https://www.google.com/maps?ll=" . $row["latitude"] . "," . $row["longitude"] . "&q=" . $row["latitude"] . "," . $row["longitude"] . " (" . urlencode($row["name"]) . ")";

            echo "<div class='card'>";
            echo "<img src='" . $row["image_url"] . "' alt='" . $row["name"] . "'>";
            echo "<h3>" . $row["name"] . "</h3>";
            echo "<p>" . $row["description"] . "</p>";
            echo "<p><strong>ตำแหน่ง:</strong> <a href='" . $googleMapsLink . "' target='_blank'>ดูตำแหน่งบนแผนที่</a></p>";
            echo "<i class='heart-icon' onclick='addToFavorites(" . $row["id"] . ")'>&#10084;</i>";
            echo "</div>";
        }
    } else {
        echo "<p>ไม่พบที่ตรงกับคำค้นหา</p>";
    }
    ?>
</section>


    <footer>
        <p>ติดต่อเรา: 095-4875455 | Email: SURATTHANI555@gmail.com</p>
    </footer>

</body>
</html>
