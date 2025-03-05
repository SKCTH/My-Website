<?php 
session_start();
include('database.php');

// ดึงข้อมูลนักเรียนจากฐานข้อมูล (ตัวอย่าง)
$student_code = isset($_POST['student_code']) ? $_POST['student_code'] : '';
$student_data = null;

if ($student_code) {
    // สมมติว่าใช้ฐานข้อมูลในการดึงข้อมูล
    $query = "SELECT * FROM students WHERE student_code = '$student_code'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
        $student_data = mysqli_fetch_assoc($result);
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบเข้าเรียน</title>
    <!-- ใช้ Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- ใช้ Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- ใช้ SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.min.css" rel="stylesheet">
    <!-- ใช้ Font Prompt -->
    <link href="https://fonts.googleapis.com/css2?family=Prompt&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Prompt', sans-serif;
            background-color: #f8f9fa; /* ปรับสีพื้นหลังให้สว่าง */
            margin: 0;
            padding: 0;
        }
        .container {
            margin-top: 30px;
        }
        .card-img-top {
            object-fit: cover;
            width: 100%;
            height: 200px;
        }
        .card {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease-in-out;
        }
        .card:hover {
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);
            transform: translateY(-5px);
        }
        .btn-primary, .btn-success {
            border-radius: 25px;
            transition: all 0.3s ease;
        }
        .btn-primary:hover, .btn-success:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }
        .navbar {
            background-color: #007bff; /* สีฟ้าสดใส */
            border-bottom: 3px solid #0056b3;
        }
        .navbar-brand {
            color: white !important;
        }
        .navbar-toggler-icon {
            background-color: white;
        }
        .card-body {
            background-color: #ffffff;
            padding: 20px;
        }
        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
        }
        .form-control {
            border-radius: 25px;
            padding: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        table th, table td {
            text-align: center;
            vertical-align: middle;
        }
        /* เพิ่มแถบเงาภายในฟอร์ม */
        .form-group {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <img src="img/logo1.png" alt="Logo" width="30" height="30" class="d-inline-block align-top">
            แผนกวิชาคอมพิวเตอร์
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
</nav>

<div class="container">
    <div class="row">
        <!-- UI ฝั่งซ้าย -->
        <div class="col-md-4">
            <!-- โลโก้โรงเรียน -->
            <div class="card">
                <img src="img/logo.png" class="card-img-top" alt="Logo" style="height: 250px; object-fit: contain;">
                <div class="card-body text-center">
                    <h5 class="card-title">แผนกวิชาคอมพิวเตอร์</h5>
                    <p class="card-text">Computer Department</p>
                </div>
            </div>

            <!-- ข้อมูลนักเรียน -->
            <div class="card mt-3">
                <div class="card-body">
                    <?php if ($student_data): ?>
                        <img src="uploads/student_photos/<?= $student_data['student_code']; ?>.jpg" class="img-fluid" alt="Student Photo" style="max-height: 200px; object-fit: cover;">
                        <h5 class="card-title">ข้อมูลนักเรียน</h5>
                        <p>รหัสนักเรียน: <strong><?= $student_data['student_code']; ?></strong></p>
                        <p>ชื่อ: <strong><?= $student_data['first_name'] . ' ' . $student_data['last_name']; ?></strong></p>
                        <p>ชั้น: <?= $student_data['grade']; ?></p>
                    <?php else: ?>
                        <p>กรุณากรอกรหัสนักเรียนเพื่อตรวจสอบข้อมูล</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- UI ฝั่งขวา -->
        <div class="col-md-8">
            <!-- กล้องตรวจจับใบหน้า -->
            <div class="card">
                <div class="card-body text-center">
                    <h5>กล้องกำลังตรวจจับใบหน้า</h5>
                    <video id="face-camera" width="100%" height="auto" autoplay></video>
                    <button class="btn btn-success mt-3" id="start-scan">เริ่มการแสกนใบหน้า</button>
                </div>
            </div>

            <!-- UI สำหรับใส่รหัสนักเรียน -->
            <div class="card mt-3">
                <div class="card-body">
                    <h5>กรุณาใส่รหัสนักเรียน</h5>
                    <form method="POST" action="">
                        <input type="text" name="student_code" class="form-control" placeholder="กรอกรหัสนักเรียน" maxlength="20" required>
                        <button type="submit" class="btn btn-primary mt-3">ยืนยันรหัสนักเรียน</button>
                    </form>
                </div>
            </div>

            <!-- ตารางแสดงข้อมูลนักเรียน -->
            <div class="card mt-3">
                <div class="card-body">
                    <h5>ข้อมูลการเข้าเรียนล่าสุด</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>รหัสนักเรียน</th>
                                <th>ชื่อ - นามสกุล</th>
                                <th>ชั้นเรียน</th>
                                <th>สถานะ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>12345</td>
                                <td>นายสมชาย ใจดี</td>
                                <td>ประถมศึกษาปีที่ 1</td>
                                <td><span class="badge bg-success">สำเร็จ</span></td>
                            </tr>
                            <!-- เพิ่มแถวข้อมูลตามที่มี -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- เพิ่ม Script สำหรับการแสกน QR และแสดงการแจ้งเตือน -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs"></script>
<script src="https://cdn.jsdelivr.net/npm/@tensorflow-models/blazeface"></script>
<script>
    // เมื่อคลิกปุ่ม "ยืนยันรหัสนักเรียน"
    document.getElementById('submit-code').addEventListener('click', function() {
        var studentCode = document.getElementById('student-code').value;
        if (studentCode) {
            Swal.fire({
                title: 'สำเร็จ!',
                text: 'รหัสนักเรียน ' + studentCode + ' ได้รับการยืนยันแล้ว!',
                icon: 'success',
                confirmButtonText: 'ตกลง'
            });
        } else {
            Swal.fire({
                title: 'เกิดข้อผิดพลาด!',
                text: 'กรุณากรอกรหัสนักเรียน',
                icon: 'error',
                confirmButtonText: 'ตกลง'
            });
        }
    });
    
    // ฟังก์ชันสำหรับเริ่มการแสกนใบหน้า
    document.getElementById('start-scan').addEventListener('click', function() {
        // การใช้ Blazeface หรือฟังก์ชันตรวจจับใบหน้า
        alert("เริ่มการแสกนใบหน้า");
    });
</script>

<!-- ใช้ Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
