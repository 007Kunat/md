<?php
session_start();
include 'db_connect.php';

// ตรวจสอบว่าเป็นแอดมินหรือไม่
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: home.php");
    exit();
}

// ตรวจสอบการส่งข้อมูลจากฟอร์ม
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $image_url = $_POST['image_url'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];

    // เพิ่มข้อมูลสถานที่ท่องเที่ยวในฐานข้อมูล
    $insert_query = "INSERT INTO categories (name, description, image_url, latitude, longitude) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("sssdd", $name, $description, $image_url, $latitude, $longitude);

    if ($stmt->execute()) {
        echo "<script>alert('เพิ่มสถานที่ท่องเที่ยวสำเร็จ!'); window.location.href = 'manage_places.php';</script>";
    } else {
        echo "<script>alert('เกิดข้อผิดพลาดในการเพิ่มข้อมูล');</script>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่มสถานที่ท่องเที่ยวใหม่</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
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

        #map {
            height: 400px;
            width: 100%;
            margin-bottom: 20px;
            border-radius: 8px;
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
            display: block;
            margin: 20px auto;
        }

        button:hover {
            background-color: #1863a0;
        }
    </style>
    <script>
        function initMap() {
            var defaultLocation = { lat: 9.138238, lng: 99.321748}; // สุราษ

            var map = new google.maps.Map(document.getElementById("map"), {
                center: defaultLocation,
                zoom: 13
            });

            var marker = new google.maps.Marker({
                position: defaultLocation,
                map: map,
                draggable: true
            });

            google.maps.event.addListener(map, 'click', function(event) {
                marker.setPosition(event.latLng);
                document.getElementById('latitude').value = event.latLng.lat();
                document.getElementById('longitude').value = event.latLng.lng();
            });

            google.maps.event.addListener(marker, 'dragend', function(event) {
                document.getElementById('latitude').value = event.latLng.lat();
                document.getElementById('longitude').value = event.latLng.lng();
            });
        }

        function fetchImage() {
            var placeUrl = document.getElementById('image_url').value;
            document.getElementById('preview').src = placeUrl;
        }
    </script>
</head>
<body>
    <nav>
        <div class="logo">
            <a href="admin_dashboard.php">
                <img src="img/logo2.png" alt="Logo">
            </a>
        </div>
        <div class="nav-links">
            <a href="admin_dashboard.php">หน้าหลัก</a>
            <a href="manage_users.php">จัดการผู้ใช้</a>
            <a href="manage_places.php">จัดการสถานที่ท่องเที่ยว</a>
            <a href="logout.php">ออกจากระบบ</a>
        </div>
    </nav>

    <div class="container">
        <h1>เพิ่มสถานที่ท่องเที่ยว</h1>
        <div class="form-container">
            <form action="add_spot.php" method="POST">
                <label for="name">ชื่อสถานที่:</label>
                <input type="text" id="name" name="name" required>

                <label for="description">คำอธิบาย:</label>
                <textarea id="description" name="description" required></textarea>

                <label for="image_url">ลิงค์ภาพสถานที่:</label>
                <input type="text" id="image_url" name="image_url" onblur="fetchImage()" required>
                <img id="preview" src="" alt="Preview" style="width: 100%; max-height: 300px; margin-top: 10px; display: block;">

                <label for="latitude">ละติจูด:</label>
                <input type="text" id="latitude" name="latitude" required>

                <label for="longitude">ลองจิจูด:</label>
                <input type="text" id="longitude" name="longitude" required>

                <div id="map"></div>

                <button type="submit">บันทึกสถานที่</button>
            </form>
        </div>
    </div>

    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBEvMe9qRl4PccTLklhZrYGRU9Mzm_RMoo&callback=initMap"></script>
</body>
</html>
