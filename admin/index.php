<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>หน้าหลัก</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="bg-gradient-to-br from-green-700 to-teal-500 text-white font-sans min-h-screen">
    <!-- Header -->
    <?php include "header.php" ?>

    <div class="p-4 text-center">
        <h1 class="text-2xl font-bold mb-2">จัดการข้อมูลสหกิจศึกษา</h1>
        <p class="mb-2">คณะวิทยาศาสตร์และนวัตกรรมดิจิทัล</p>

        <div class="flex justify-center space-x-4 mb-4">
            <!-- <a href="insert_student.php" class="bg-green-600 text-white px-6 py-3 rounded-lg flex items-center space-x-2">
                <i class="fas fa-user-plus"></i>
                <span>เพิ่มข้อมูลผู้ใช้</span>
            </a> -->
            <a href="insert_student_csv.php" class="bg-yellow-500 text-white px-6 py-3 rounded-lg flex items-center space-x-2">
                <i class="fas fa-file-import"></i>
                <span>นำเข้าข้อมูล</span>
            </a>
            <!-- <button class="bg-purple-500 text-white px-6 py-3 rounded-lg flex items-center space-x-2">
                <i class="fas fa-cogs"></i>
                <span>จัดการข้อมูลสถานประกอบการสหกิจศึกษา</span>
            </button> -->
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6 text-black">
            <h2 class="text-xl font-semibold mb-4">เพิ่มข้อมูลผู้ใช้</h2>
            <div class="space-y-4">
                <a href="insert_student.php" class="rounded-lg p-4 flex items-center hover:bg-green-200">
                    <i class="fas fa-user-plus text-green-600 mr-2"></i>
                    <span>นิสิตคณะวิทยาศาสตร์และนวัตกรรมดิจิทัล</span>
                </a>
                <a href="insert_teacher.php" class=" rounded-lg p-4 flex items-center hover:bg-green-200">
                    <i class="fas fa-chalkboard-teacher text-green-600 mr-2"></i>
                    <span>อาจารย์ประสานงานโครงการสหกิจศึกษา</span>
                </a>
                <a href="insert_officer.php" class="rounded-lg p-4 flex items-center hover:bg-green-200">
                    <i class="fas fa-user-shield text-green-600 mr-2"></i>
                    <span>ข้อมูลเจ้าหน้าที่ฝ่ายสหกิจศึกษา</span>
                </a>
            </div>
        </div>
    </div>
</body>

</html>

<!-- auto-check-login -->
<?php include("auto.php"); ?>