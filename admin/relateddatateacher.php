<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ตารางข้อมูลอาจารย์</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="bg-gradient-to-br from-green-700 to-teal-500 text-white font-sans min-h-screen">
    <!-- Navbar -->
    <nav class="bg-green-800 p-4 flex justify-between items-center border-b border-gray-600">
        <div class="flex items-center space-x-4">
            <img src="assets/img/Sci.png" alt="TSU Logo" class="w-32 h-auto">
            <a href="index.php" class="text-yellow-300 hover:text-gray-300">หน้าหลัก</a>
            <a href="#" class="text-yellow-300 hover:text-gray-300">ข้อมูลผู้ใช้และระบบสหกิจศึกษา</a>
            <a href="#" class="text-yellow-300 hover:text-gray-300">จัดการข้อมูล</a>
            <a href="#" class="text-yellow-300 hover:text-gray-300">ผลการเข้าร่วมสหกิจศึกษา</a>
        </div>
        <div class="flex items-center space-x-2">
            <span>admin</span>
            <i class="fa-solid fa-user"></i>
        </div>
    </nav>

    <div class="p-4">
        <div class="text-center mb-4 border-b border-gray-600">
            <h1 class="text-2xl font-bold mb-2">จัดการข้อมูลสหกิจศึกษา</h1>
            <p class="mb-2">คณะวิทยาศาสตร์และนวัตกรรมดิจิทัล</p>
        </div>

        <div class="flex justify-center space-x-4 mb-4">
            <!-- <button class="bg-green-600 text-white px-6 py-3 rounded-lg flex items-center space-x-2">
                <i class="fas fa-user-plus"></i>
                <span>เพิ่มข้อมูลผู้ใช้</span>
            </button> -->
            <a href="insert_student_csv.php" class="bg-yellow-500 text-white px-6 py-3 rounded-lg flex items-center space-x-2">
                <i class="fas fa-file-import"></i>
                <span>นำเข้าข้อมูล</span>
            </a>
            <!-- <button class="bg-purple-500 text-white px-6 py-3 rounded-lg flex items-center space-x-2">
                <i class="fas fa-cogs"></i>
                <span>จัดการข้อมูลที่เกี่ยวข้อง</span>
            </button> -->
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6 text-black border border-gray-600">
            <h2 class="text-xl font-semibold mb-4">จัดการข้อมูลที่เกี่ยวข้อง: ข้อมูลผู้ใช้ : นิสิตคณะวิทยาศาสตร์และนวัตกรรมดิจิทัล</h2>
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
                        <th class="border p-2 text-left">รหัสอาจารย์</th>
                        <th class="border p-2 text-left">คำนำหน้า</th>
                        <th class="border p-2 text-left">ชื่อ</th>
                        <th class="border p-2 text-left">นามสกุล</th>
                        <th class="border p-2 text-left">ตำแหน่ง</th>
                        <th class="border p-2 text-left">หลักสูตร</th>
                        <th class="border p-2 text-center">แก้ไข</th>
                        <th class="border p-2 text-center">ลบ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include_once "../connection.php";
                    $sql = "SELECT * FROM `teacher`,`course`,`rank` WHERE teacher.course = course.course_id AND teacher.rank = rank.rank_id
                            ORDER BY teacher.teacher_id;";
                    $query = mysqli_query($conn, $sql);
                    while ($result = mysqli_fetch_array($query)) { ?>
                        <tr>
                            <td><img src='../assets/img/teacher/<?= $result["teacher_surname"]; ?>.jpg' width="100" height="auto"></td>
                            <td class="text-lg"><?= $result["teacher_id"]; ?></td>
                            <td class="text-lg"><?= $result["teacher_prefix"]; ?></td>
                            <td class="text-lg"><?= $result["teacher_surname"]; ?></td>
                            <td class="text-lg"><?= $result["teacher_lastname"]; ?></td>
                            <td class="text-lg"><?= $result["rank_name"]; ?></td>
                            <td class="text-lg"><?= $result["course_name"]; ?></td>
                            <td>
                                <a href="edit_science.php?teacher_id=<?= $result["teacher_id"]; ?>" class="text-green-500 ps-[5rem]"><i class="fas fa-pencil-alt"></i></a>
                            </td>
                            <td>
                                <a data-id="<?= $result["teacher_id"]; ?>" href="?delete=<?= $result["teacher_id"]; ?>" class="text-red-600 ps-[5rem]"><i class="fas fa-trash"></i></a>
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