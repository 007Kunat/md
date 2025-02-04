<?php
// รวมการเชื่อมต่อฐานข้อมูล
include 'db_connect.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Thani</title>
    <style>
        /* Reset styles */
        * {
            margin: 0;
            padding: 0;
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

        .search-bar {
            text-align: center;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            gap: 100rem;
            background-color: #e6f7ff;
            margin: 1.5rem auto;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .search-bar input {
            width: 50%;
            padding: 0.75rem;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        .search-bar button {
            padding: 0.75rem 1.5rem;
            background-color: #60B7FE;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
        }

        .search-bar button:hover {
            background-color: #0067bb;
            transform: scale(1.09);
        }

        .search-bar select {
            padding: 0.75rem;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #ffffff;
            cursor: pointer;
            transition: border-color 0.3s;
        }

        .hero {
            text-align: center;
            background: url('img/img1.png') no-repeat center/cover;
            color: white;
            padding: 7rem 1rem;
            font-size: 2.5rem;
            font-weight: bold;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.5);
        }

        .intro {
            background-color: #BFE2FF;
            padding: 2rem;
            margin: 2rem auto;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            line-height: 1.8;
            color: #000000;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); /* กำหนดแถวละ 3 รูป */
            gap: 2rem;
            margin: 2rem auto;
            padding: 0 1rem;
            max-width: 1200px;
        }

        /* Card styles */
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
            object-fit: cover; /* ทำให้รูปภาพพอดีกับขนาดการ์ด */
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

        footer {
            background-color: #60B7FE;
            color: rgb(0, 0, 0);
            text-align: center;
            padding: 1rem;
            margin-top: 2rem;
            font-size: 0.9rem;
        }
        .heart-icon{
            position: absolute;
            bottom: 20px;
            right: 40px;
            font-size: 30px;
            color:rgb(192, 22, 22);
            cursor: pointer;
        }

        .heart-icon:hover {
            color: #f38f8f; /* เปลี่ยนสีเมื่อวางเมาส์ */
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

        <form action="search.php" method="get" class="search-bar">
                <input type="text" name="query" placeholder="ค้นหาสถานที่ท่องเที่ยว">
                <button type="submit">ค้นหา</button>
                <select id="radius" name="radius">
                    <option value="radius">รัศมี</option>
                    <option value="5 km">5 กิโลเมตร</option>
                    <option value="10 km">10 กิโลเมตร</option>
                    <option value="15 km">15 กิโลเมตร</option>
                    <option value="20 km">20 กิโลเมตร</option>
                </select>
        </form>


    <section class="hero">
        <h2>SURAT THANI</h2>
    </section>

    <section class="intro">
        <p>
            “สุราษฎร์ธานี เมืองร้อยเกาะ หอยใหญ่ ไข่แดง แหล่งธรรมมะ” จังหวัดที่มีพื้นที่มากที่สุดของภาคใต้ มีหมู่เกาะที่มีชื่อเสียง ไม่เพียงแต่หมู่เกาะสวยน้อยใหญ่ที่เรียงรายในน่านน้ำทะเลอ่าวไทยจนกลายเป็น
            เกาะในฝันสวรรค์ของคนรักทะเล ในเรื่องของธรรมชาติ ป่าเขา ไม่แพ้ที่ใด เพราะชื่อของเขื่อนเชี่ยวหลาน เขาสก ยังคงเป็นจุดหมายปลายทางอันดับต้น จึงเป็นจังหวัดที่เหมาะจะหลบความวุ่นวาย
            มาพักผ่อนได้อย่างมีความสุข
        </p>
    </section>

    <section class="grid" id="tourist-grid">
        <!-- ข้อมูลจะถูกสร้างแบบไดนามิก -->
    </section>
    
    <script>
        
    </script>
    <script>
        // ดึงข้อมูลจากไฟล์ fetch_tourist_spots.php และแสดงผลในหน้าเว็บ
fetch('fetch_tourist_spots.php')
    .then(response => response.json())
    .then(data => {
        const grid = document.getElementById('tourist-grid');
        data.forEach(spot => {
            const card = document.createElement('div');
            card.classList.add('card');
            
            // สร้างลิงก์ Google Maps
            const googleMapsLink = `https://www.google.com/maps?q=${spot.latitude},${spot.longitude}`;
            
            // เพิ่มลิงก์ไปยังหน้ารายละเอียด
            card.innerHTML = `
                <a href="details.php?id=${spot.id}" style="text-decoration: none; color: inherit;">
                    <img src="${spot.image_url}" alt="${spot.name}">
                    <h3>${spot.name}</h3>
                    <p>${spot.description}</p>
                </a>
                <p><strong>ตำแหน่ง:</strong> <a href="${googleMapsLink}" target="_blank">ดูตำแหน่งบนแผนที่</a></p>
                <i class="heart-icon" onclick="addToFavorites(${spot.id})">&#10084;</i>
            `;
            
            grid.appendChild(card);
        });
    })
    .catch(error => console.error('Error fetching data:', error));

    </script>

    <footer>
        <p>ติดต่อเรา: 095-4875455 | Email: SURATTHANI555@gmail.com</p>
    </footer>
</body>
</html>
