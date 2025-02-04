<?php
session_start();

// ตรวจสอบว่าเป็นแอดมินหรือไม่
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: home.php");
    exit();
}

// เชื่อมต่อฐานข้อมูล
include 'db_connect.php';

// ดึงข้อมูลสถานที่ท่องเที่ยวทั้งหมด
$query = "SELECT id, name, description, image_url FROM categories";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการสถานที่ท่องเที่ยว</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"> <!-- ไอคอนถังขยะจาก Font Awesome -->
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
            width: 90%;
            margin: auto;
            padding: 25px;
        }

        h1 {
            text-align: center;
            font-size: 40px;
            margin-bottom: 30px;
        }

        /* ตารางข้อมูลสถานที่ */
        table {
            width: 100%;
            border-collapse: collapse;
            
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #60B7FE;
            color: black;
            font-size: 18px;
            text-align: center;
        }

        td {
            font-size: 17px;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        /* ปรับลิงก์ "แก้ไข" ให้มีสีและฟังก์ชัน */
        .btn-edit {
            color:rgb(255, 50, 4); /* สีแดง */
            text-decoration: none;
        }

        .btn-edit:hover {
            color:rgb(139, 39, 9);
        }

        /* ปรับลิงก์ "ลบ" ให้เป็นไอคอนถังขยะ */
        .btn-delete {
            color:rgb(10, 46, 70); /* สีแดง */
            text-decoration: none;
        }

        .btn-delete:hover {
            color:#60B7FE;
        }

        /* เพิ่มฟังก์ชันการแสดงไอคอนถังขยะ */
        .fa-trash-alt {
            font-size: 20px;
        }

        .add-btn-container {
            text-align: right;
            margin-bottom: 15px;
        }

        .btn-add {
            background-color:rgb(4, 50, 255);
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 18px;
        }

        .btn-add:hover {
            background-color:rgb(0, 0, 0);
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

    <div class="container">
    <h1>จัดการข้อมูลสถานที่ท่องเที่ยว</h1>

    <div class="add-btn-container">
        <a href="add_spot.php" class="btn-add">
            <i class="fas fa-plus-circle"></i> เพิ่มสถานที่ท่องเที่ยว
        </a>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>ชื่อสถานที่</th>
                <th>คำอธิบาย</th>
                <th>ภาพ</th>
                <th>จัดการ</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($spot = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $spot['id']; ?></td>
                <td><?php echo $spot['name']; ?></td>
                <td><?php echo $spot['description']; ?></td>
                <td><img src="<?php echo $spot['image_url']; ?>" alt="Image" width="100"></td>
                <td>
                    <a href="edit_spot.php?id=<?php echo $spot['id']; ?>" class="btn-edit">
                        <i class="fas fa-pencil-alt"></i> แก้ไข
                    </a> &nbsp;  &nbsp;  &nbsp; <div></div>
                    <a href="delete_spot.php?id=<?php echo $spot['id']; ?>" class="btn-delete" onclick="return confirm('คุณต้องการลบสถานที่ท่องเที่ยวนี้หรือไม่?')">
                        <i class="fa fa-trash-alt"></i> ลบ
                    </a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

</body>
</html>
