<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>หน้าตารางข้อมูลนิสิต</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="bg-gradient-to-br from-green-700 to-teal-500 text-white font-sans min-h-screen">
    <!-- Navbar -->
    <?php include "header.php"; ?>

    <div class="p-4">
        <div class="text-center mb-4 border-b border-gray-600">
            <h1 class="text-2xl font-bold mb-2">จัดการข้อมูลสหกิจศึกษา</h1>
            <p class="mb-2">คณะวิทยาศาสตร์และนวัตกรรมดิจิทัล</p>
        </div>

        <div class="flex justify-center space-x-4 mb-4">
            <!-- <a href="insert_student.php" class="bg-green-600 text-white px-6 py-3 rounded-lg flex items-center space-x-2">
                <i class="fas fa-user-plus"></i>
                <span>เพิ่มข้อมูลผู้ใช้</span>
            </a> -->
            <a href="insert_student_csv.php" class="bg-yellow-500 text-white px-6 py-3 rounded-lg flex items-center space-x-2">
                <i class="fas fa-file-import"></i>
                <span>นำเข้าข้อมูล</span>
            </a>
            <button class="bg-purple-500 text-white px-6 py-3 rounded-lg flex items-center space-x-2">
                <i class="fas fa-cogs"></i>
                <span>จัดการข้อมูลสถานประกอบการสหกิจศึกษา</span>
            </button>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6 text-black border border-gray-600">
            <h2 class="text-xl font-semibold mb-4">จัดการข้อมูลที่เกี่ยวข้อง: ข้อมูลผู้ใช้</h2>
            <div class="mb-4 flex justify-between">
                <div class="flex space-x-2">
                    <select class="p-2 border rounded">
                        <option>นิสิต</option>
                        <option>อาจารย์ที่ปรึกษา</option>
                        <option>เจ้าหน้าที่สหกิจ</option>
                    </select>
                    <input type="text" placeholder="ค้นหารายชื่อ" class="p-2 border rounded">
                    <select class="p-2 border rounded">
                        <option>ประวัติการศึกษา</option>
                    </select>
                    <select class="p-2 border rounded">
                        <option>หลักสูตรทั้งหมด</option>
                    </select>
                </div>
            </div>

            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border p-2 text-left"></th>
                        <th class="border p-2 text-left">รหัสนิสิต</th>
                        <th class="border p-2 text-left">คำนำหน้า</th>
                        <th class="border p-2 text-left">ชื่อ</th>
                        <th class="border p-2 text-left">นามสกุล</th>
                        <th class="border p-2 text-left">หลักสูตร</th>
                        <th class="border p-2 text-center">แก้ไข</th>
                        <th class="border p-2 text-center">ลบ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include "../connection.php";
                    $sql = "SELECT * FROM `student`,`course` WHERE student.course = course.course_id ORDER BY student.student_id;";
                    $query = mysqli_query($conn, $sql);
                    while ($result = mysqli_fetch_array($query)) { ?>
                        <tr>
                            <td><img src='../assets/img/student/<?= $result["student_id"]; ?>.jpg' width="100" height="auto"></td>
                            <td class="text-lg"><?= $result["student_id"]; ?></td>
                            <td class="text-lg"><?= $result["student_prefix"]; ?></td>
                            <td class="text-lg"><?= $result["student_surname"]; ?></td>
                            <td class="text-lg"><?= $result["student_lastname"]; ?></td>
                            <td class="text-lg"><?= $result["course_name"]; ?></td>
                            <td>
                                <a href="relateddatauser_edit.php?student_id=<?= $result["student_id"]; ?>" class="text-green-500 ps-[8rem]"><i class="fas fa-pencil-alt"></i></a>
                            </td>
                            <td>
                                <a data-id="<?= $result["student_id"]; ?>" href="?delete=<?= $result["student_id"]; ?>" class="text-red-600 ps-[5rem]"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>

<!-- auto-check-login -->
<?php include("auto.php"); ?>