<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการข้อมูลผู้ใช้</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-gradient-to-br from-green-700 to-teal-500 text-white font-sans min-h-screen">
    <!-- Navbar -->
    <nav class="bg-green-800 p-4 flex justify-between items-center">
        <div class="flex items-center space-x-4">
            <img src="assets/img/Sci.png" alt="TSU Logo" class="w-32 h-auto">
            <a href="index.php" class="text-yellow-300 hover:text-gray-300">หน้าหลัก</a>
            <a href="#" class="text-yellow-300 hover:text-gray-300">ข้อมูลผู้ใช้และระบบสหกิจศึกษา</a>
            <a href="relateddata.php" class="text-yellow-300 hover:text-gray-300">จัดการข้อมูล</a>
            <a href="#" class="text-yellow-300 hover:text-gray-300">ผลการเข้าร่วมสหกิจศึกษา</a>
        </div>
        <div class="flex items-center space-x-2">
            <span>
                <?php
                session_start();
                echo isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest';
                ?>
            </span>
            <i class="fa-solid fa-user"></i>
        </div>
    </nav>

    <div class="p-4">
        <div class="text-center mb-4">
            <h1 class="text-2xl font-bold mb-2">จัดการข้อมูลสหกิจศึกษา</h1>
            <p class="mb-2">คณะวิทยาศาสตร์และนวัตกรรมดิจิทัล</p>
        </div>

        <div class="flex justify-center space-x-4 mb-4">
            
            <a href="insert_student_csv.php" class="bg-yellow-500 text-white px-6 py-3 rounded-lg flex items-center space-x-2">
                <i class="fas fa-file-import"></i>
                <span>นำเข้าข้อมูล</span>
            </a>
            
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6 text-black">
            <h2 class="text-xl font-semibold mb-4">จัดการข้อมูลที่เกี่ยวข้อง : ข้อมูลผู้ใช้</h2>
            <table class="min-w-full bg-white">
            <thead>
                    <tr class="bg-gray-200">
                        <th class="border p-2 text-left"></th>
                        <th class="border p-2 text-left">รหัสเจ้าหน้าที่</th>
                        <th class="border p-2 text-left">คำนำหน้า</th>
                        <th class="border p-2 text-left">ชื่อ</th>
                        <th class="border p-2 text-left">นามสกุล</th>
                        <th class="border p-2 text-left">แก้ไข</th>
                        <th class="border p-2 text-left">ลบ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include_once "../connection.php";
                    $sql = "SELECT * FROM `officer` ORDER BY officer.officer_id;";
                    $query = mysqli_query($conn, $sql);
                    while ($result = mysqli_fetch_array($query)) { ?>
                        <tr>
                            <td><img src='../assets/img/officer/<?= $result["officer_surname"]; ?>.jpg' width="100" height="auto"></td>
                            <td class="text-lg"><?= $result["officer_id"]; ?></td>
                            <td class="text-lg"><?= $result["officer_prefix"]; ?></td>
                            <td class="text-lg"><?= $result["officer_surname"]; ?></td>
                            <td class="text-lg"><?= $result["officer_lastname"]; ?></td>
                            <td>
                                <a href="edit_science.php?officer_id=<?= $result["officer_id"]; ?>" class="text-green-500 ps-[1rem]"><i class="fas fa-pencil-alt"></i></a>
                            </td>
                            <td>
                                <a data-id="<?= $result["officer_id"]; ?>" href="?delete=<?= $result["officer_id"]; ?>" class="text-red-600 ps-[1rem]"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function editStudent(id) {
            window.location.href = 'edit_student.php?id=' + id;
        }

        function deleteStudent(id) {
            Swal.fire({
                title: 'คุณแน่ใจหรือไม่?',
                text: "คุณจะไม่สามารถกู้คืนข้อมูลนี้ได้!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ใช่, ลบมัน!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'delete_student.php?id=' + id;
                }
            })
        }
    </script>
</body>

</html>

<!-- auto-check-login -->
<?php include("auto.php"); ?>
