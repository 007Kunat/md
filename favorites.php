<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // ถ้ายังไม่ได้ล็อกอินให้ไปหน้า login
    exit();
}

$user_id = $_SESSION['user_id'];

// ดึงข้อมูลสถานที่จากรายการโปรด
$query = "SELECT tourist_spots.name, tourist_spots.image_url, tourist_spots.description
          FROM favorites
          JOIN tourist_spots ON favorites.tourist_spot_id = tourist_spots.id
          WHERE favorites.user_id = ?";
$stmt = $conn->prepare($query);

$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายการโปรด</title>
    <style>
        /* Reset styles */
        * {
            border-radius: 8px;
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

        /* Card styles */
        .card {
            background-color: #daeeff;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-bottom: 10px;
            padding: 3rem;
            position: relative; /* เพิ่มการจัดตำแหน่ง */
            transition: transform 0.3s ease;
        }

        .card img {
            width: 500px;
            height: 300px;
            border-radius: 8px;
            display: block; /* ทำให้รูปเป็นบล็อก */
            margin: 0 auto;
        }

        .card h3 {
            font-size: 25px;
            margin-top: 20px;
            color: #333;
        }

        .card p {
            color: #666;
            font-size: 20px;
            margin: 5px 0;
        }

        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .card a {
            color: #60B7FE;
            text-decoration: none;
            font-size: 18px;
            font-weight: bold;
        }

        /* Heart icon style */
        .heart-icon {
            position: absolute;
            bottom: 25px;
            right: 40px;
            font-size: 35px;
            color:rgb(192, 22, 22);
        }

        .heart-icon:hover {
            color: #f38f8f; /* เปลี่ยนสีเมื่อวางเมาส์ */
        }

        /* Grid styles */
        .grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr); /* 3 columns */
            gap: 2rem;
            width: 100%;
            margin: 2rem auto;
        }

        .footer p {
            background-color: #60B7FE;
            color: rgb(0, 0, 0);
            text-align: center;
            padding: 1rem;
            margin-top: 2rem;
            font-size: 0.9rem;
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
    <h1>รายการโปรดของคุณ</h1>

    <div class="grid">
        <?php while ($row = $result->fetch_assoc()) { ?>
            <div class="card">
                <img src="<?php echo $row['image_url']; ?>" alt="<?php echo $row['name']; ?>">
                <h3><?php echo $row['name']; ?></h3>
                <p><?php echo $row['description']; ?></p>
                <p><a href="#">ดูเพิ่มเติม</a></p>
            </div>
        <?php } ?>
    </div>
    
    <footer>
    <p>ติดต่อเรา: 095-4875455 | Email: SURATTHANI555@gmail.com</p>
</footer>
</body>
</html>

<?php
$conn->close();
?>
