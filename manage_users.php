<?php
session_start();

// ตรวจสอบว่าเป็นแอดมินหรือไม่
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: home.php");
    exit();
}

// เชื่อมต่อฐานข้อมูล
include 'db_connect.php';

// ดึงข้อมูลผู้ใช้ทั้งหมด
$query = "SELECT id, name, email, role FROM users";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการผู้ใช้</title>
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

        .container {
            width: 80%;
            margin: auto;
            padding: 25px;
        }
        
        h1 {
            text-align: center;
            font-size: 40px;
        }

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
        /* ปรับลิงก์ "แก้ไข" ให้มีพื้นหลังสีแดง */
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
        <h1>จัดการข้อมูลผู้ใช้</h1>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>ชื่อ</th>
                    <th>อีเมล</th>
                    <th>บทบาท</th>
                    <th>จัดการ</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($user = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $user['id']; ?></td>
                    <td><?php echo $user['name']; ?></td>
                    <td><?php echo $user['email']; ?></td>
                    <td><?php echo $user['role']; ?></td>
                    <td>
                        <a href="edit_user.php?id=<?php echo $user['id']; ?>" class="btn-edit">
                            <i class="fas fa-pencil-alt"></i> แก้ไข
                        </a> &nbsp; | &nbsp;
                        <a href="delete_user.php?id=<?php echo $user['id']; ?>" class="btn-delete" onclick="return confirm('คุณต้องการลบผู้ใช้นี้หรือไม่?')">
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
