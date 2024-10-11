<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ตรวจสอบเอกสารโครงการสหกิจศึกษา</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="bg-gradient-to-br from-green-700 to-teal-500 text-white min-h-screen">

    <!-- Header -->
    <?php include "header.php" ?>
    <!-- Main Content -->
    <main class="p-6">
        <div class="bg-white text-black rounded-lg shadow-lg p-6">
            <h2 class="text-center text-2xl font-bold mb-6 text-green-700">ตรวจสอบเอกสารและจัดการข้อมูลนิสิตโครงการสหกิจศึกษา</h2>

            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-green-700">
                    <thead class="bg-green-500 text-white">
                        <tr>
                            <th class="py-2 px-4 border">ชื่อ - สกุล</th>
                            <th class="py-2 px-4 border">หลักสูตร</th>
                            <th class="py-2 px-4 border">เอกสารและผลตอบกลับ</th>
                            <th class="py-2 px-4 border">หนังสือส่งตัว</th>
                            <th class="py-2 px-4 border">ผลการตอบกลับ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include("../connection.php");

                        $sql = "SELECT intern.*, organization.*, student.*, course.* FROM intern 
                                JOIN organization ON intern.organization_id = organization.organization_id
                                JOIN student ON intern.student_id = student.student_id
                                JOIN course ON student.course = course.course_id
                                WHERE intern.status = 2
                                ORDER BY student.student_id";
                        $query = mysqli_query($conn, $sql);
                        while ($result = mysqli_fetch_array($query)) {
                        ?>
                            <tr class="border-t">
                                <td class="py-2 px-4 border"><?= $result["student_prefix"] . " " . $result["student_surname"] . " " . $result["student_lastname"] ?></td>
                                <td class="py-2 px-4 border"><?= $result["course_name"]; ?></td>
                                <td class="py-2 px-4 border"> 
                                    <a href="fillstd.php?student_id=<?= $result['student_id'] ?>" class="bg-orange-400 text-white px-4 py-2 rounded">ตรวจสอบเอกสาร</a>
                                </td>
                                <td class="py-2 px-4 border">
                                    <button class="bg-blue-500 text-white px-4 py-2 rounded flex items-center">
                                        <i class="fa-solid fa-download mr-2"></i>
                                        <a href="pdf.php">
                                        ดาวน์โหลดหนังสือ
                                    </button></a>
                                </td>
                                <td class="py-2 px-4 border">
                                    <button class="bg-green-500 text-white px-4 py-2 rounded">อัปโหลดการตอบกลับ</button>
                                </td>
                            </tr>

                            
                        <?php } ?>

                        <!-- Add more rows as needed -->
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <!-- <div class="mt-4 flex justify-between items-center">
                <span>แสดง 1 - 8 จาก 50 รายการ</span>
                <div class="flex space-x-2">
                    <button class="bg-gray-300 text-gray-700 px-4 py-2 rounded">1</button>
                    <button class="bg-gray-300 text-gray-700 px-4 py-2 rounded">2</button>
                    <button class="bg-gray-300 text-gray-700 px-4 py-2 rounded">></button>
                </div>
            </div> -->
        </div>
    </main>

</body>

</html>