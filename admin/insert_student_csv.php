<?php
// เปิดการรายงานข้อผิดพลาด (สำหรับการดีบัก)
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

// การเชื่อมต่อกับฐานข้อมูล
include("../connection.php");
session_start(); // Start the session

// ตัวแปรสำหรับเก็บข้อความแจ้งเตือน
$alertMessage = "";

// ฟังก์ชันสำหรับแสดงข้อมูลจากไฟล์ CSV
function readCsv($file)
{
    $data = [];
    $csvFile = fopen($file, 'r');
    fgetcsv($csvFile); // ข้ามแถวแรก (header)

    while (($row = fgetcsv($csvFile)) !== FALSE) {
        $data[] = $row; // เก็บข้อมูลใน array
    }

    fclose($csvFile);
    return $data;
}

// ตรวจสอบว่ามีการอัปโหลดไฟล์ CSV หรือไม่
if (isset($_FILES['csv_file']) && $_FILES['csv_file']['error'] == UPLOAD_ERR_OK) {
    $targetDirectory = "assets/uploads/"; // โฟลเดอร์สำหรับเก็บไฟล์
    $filePath = $targetDirectory . basename($_FILES['csv_file']['name']);

    // ย้ายไฟล์ไปยังโฟลเดอร์เป้าหมาย
    if (move_uploaded_file($_FILES['csv_file']['tmp_name'], $filePath)) {
        $_SESSION['uploaded_file_name'] = $filePath; // เก็บที่อยู่ไฟล์ใน session
        $csvData = readCsv($filePath); // อ่านข้อมูลจากไฟล์ CSV
        $_SESSION['csvData'] = $csvData;
    } else {
        $alertMessage = "Error: Could not move the uploaded file.";
    }
} else {
    $alertMessage = "Error: No file uploaded or there was an upload error.";
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload CSV and Display Data</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 text-black">
    <?php include "header.php"; ?>

    <div class="container mx-auto py-10">
        <h1 class="text-2xl font-bold mb-4">อัปโหลดไฟล์ CSV และแสดงข้อมูล</h1>

        <!-- Form for CSV upload -->
        <div class="bg-white p-6 rounded-lg shadow-lg mb-6">
            <h3 class="text-xl font-bold text-gray-700 mb-4">อัปโหลดไฟล์ CSV</h3>
            <form id="csvForm" action="" method="POST" enctype="multipart/form-data" class="flex space-x-4 items-center">
                <input type="file" name="csv_file" id="csv_file" class="border p-2 rounded-lg flex-grow" accept=".csv" value="">
                <!-- Button for uploading to database -->
                <?php if (isset($csvData)) : ?>
                    <button type="submit" name="upload_to_db" class="bg-green-500 text-white py-2 px-4 rounded-lg">
                        อัปโหลดไปยังฐานข้อมูล
                    </button>
                <?php endif; ?>
            </form>
        </div>

        <!-- Display the uploaded data in a table -->
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h3 class="text-xl font-bold text-gray-700 mb-4">ข้อมูลจากไฟล์ CSV</h3>

            <table class="min-w-full bg-white border border-gray-300" id="csvTable">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="py-2 px-4"></th>
                        <th class="py-2 px-4">รหัสนิสิต</th>
                        <th class="py-2 px-4">คำนำหน้า</th>
                        <th class="py-2 px-4">ชื่อ</th>
                        <th class="py-2 px-4">นามสกุล</th>
                        <th class="py-2 px-4">หลักสูตร</th>
                        <th class="py-2 px-4">ชั้นปี</th>
                        <th class="py-2 px-4">เกรดเฉลี่ย</th>
                        <th class="py-2 px-4">เบอร์โทรศัพท์</th>
                        <th class="py-2 px-4">Email</th>
                        <th class="py-2 px-4">Password</th>
                    </tr>
                </thead>
                <tbody id="csvData">
                    <?php if (isset($csvData)) : ?>
                        <?php foreach ($csvData as $row) : ?>
                            <tr>
                                <?php
                                // Extract student data from the row
                                $std_id = htmlspecialchars($row[0]);
                                $std_prefix = htmlspecialchars($row[1]);
                                $std_surname = htmlspecialchars($row[2]);
                                $std_lastname = htmlspecialchars($row[3]);
                                $std_course = htmlspecialchars($row[4]);

                                // ดึงรายชื่อหลักสูตรทั้งหมดจากฐานข้อมูล
                                include "../connection.php";
                                $sql_Course = "SELECT course_id, course_name FROM course";
                                $query = mysqli_query($conn, $sql_Course);

                                $bestMatch = null;
                                $highestSimilarity = 0;

                                // วนลูปเพื่อเปรียบเทียบ course_name จาก CSV กับทุก course_name ในฐานข้อมูล
                                while ($course_row = mysqli_fetch_assoc($query)) {
                                    $dbCourseName = $course_row['course_name'];

                                    // คำนวณความคล้ายคลึงระหว่างชื่อหลักสูตรจาก CSV และชื่อหลักสูตรในฐานข้อมูล
                                    similar_text($std_course, $dbCourseName, $similarityPercentage);

                                    // ถ้าความคล้ายคลึงสูงกว่า $highestSimilarity เดิม ให้บันทึกข้อมูลหลักสูตรที่คล้ายที่สุด
                                    if ($similarityPercentage > $highestSimilarity) {
                                        $highestSimilarity = $similarityPercentage;
                                        $bestMatch = $course_row;
                                    }
                                }

                                // ตรวจสอบว่าพบหลักสูตรที่คล้ายกันหรือไม่
                                if ($bestMatch && $highestSimilarity > 50) { // กำหนดให้ความคล้ายต้องมากกว่า 50%
                                    // ถ้าพบหลักสูตรที่คล้ายกันมากที่สุด ให้ใช้ course_name และ course_id นั้น
                                    $std_course = $bestMatch['course_name'];
                                } else {
                                    // ถ้าไม่พบหลักสูตรที่คล้ายพอ แจ้งเตือน
                                    $alertMessage = "Error: No similar course found for '$std_course'. Please verify the course name.";
                                    break;
                                }


                                $std_year = htmlspecialchars($row[5]);
                                $std_gpax = htmlspecialchars($row[6]);
                                $std_tel = htmlspecialchars($row[7]);

                                // Generate email and password
                                $email = $std_id . "@tsu.ac.th";
                                $password = $std_id;
                                ?>

                                <td class='border px-4 py-2'><img src='../assets/img/student/<?= $std_id; ?>.jpg' width="50" height="auto"></td>
                                <td class='border px-4 py-2'><?= $std_id; ?></td>
                                <td class='border px-4 py-2'><?= $std_prefix; ?></td>
                                <td class='border px-4 py-2'><?= $std_surname; ?></td>
                                <td class='border px-4 py-2'><?= $std_lastname; ?></td>
                                <td class='border px-4 py-2'><?= $std_course; ?></td>
                                <td class='border px-4 py-2'><?= $std_year; ?></td>
                                <td class='border px-4 py-2'><?= $std_gpax; ?></td>
                                <td class='border px-4 py-2'><?= $std_tel; ?></td>
                                <td class='border px-4 py-2'><?= $email; ?></td>
                                <td class='border px-4 py-2'><?= $password; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan='11' class='border px-4 py-2 text-center'>กรุณาอัปโหลดไฟล์ CSV</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>



    </div>
    <!-- Alert Modal -->
    <div id="customAlert" class="fixed inset-0 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 id="alertTitle" class="text-lg font-bold mb-4"></h2>
            <p id="alertMessage" class="mb-4"></p>
            <button id="alertButton" class="bg-blue-500 text-white py-2 px-4 rounded-lg">ตกลง</button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.getElementById('csv_file').addEventListener('change', function() {
            document.getElementById('csvForm').submit();
        });
    </script>

</body>

</html>

<?php
include("../connection.php");

// ตัวแปรสำหรับเก็บข้อความแจ้งเตือน
$alertMessage = "";

// ตรวจสอบการอัปโหลดข้อมูลไปยังฐานข้อมูล
if (isset($_POST['upload_to_db'])) {
    if (isset($_SESSION['csvData'])) {
        $csvData = $_SESSION['csvData'];
        $alertMessage = '';
        $errors = []; // เก็บข้อผิดพลาด

        foreach ($csvData as $row) {
            if (count($row) == 8) { // Expecting 8 columns for other data, email & password are auto-generated
                // ป้องกันการ SQL injection
                $std_id = (int) $row[0];
                $std_prefix = mysqli_real_escape_string($conn, $row[1]);
                $std_surname = mysqli_real_escape_string($conn, $row[2]);
                $std_lastname = mysqli_real_escape_string($conn, $row[3]);
                $std_course = mysqli_real_escape_string($conn, $row[4]);
                $std_year = (int) $row[5];
                $std_gpax = (float) $row[6];
                $std_tel = mysqli_real_escape_string($conn, $row[7]);

                // สร้าง email โดยใช้รหัสนิสิต
                $email = $std_id . "@tsu.ac.th";

                $std_type = "student";

                // ตรวจสอบว่ามี student_id หรือ email อยู่ในฐานข้อมูลแล้วหรือไม่
                $checkStudentQuery = "SELECT student_id FROM student WHERE student_id = '$std_id' OR EXISTS (SELECT email FROM login WHERE email = '$email')";
                $checkResult = mysqli_query($conn, $checkStudentQuery);

                if (mysqli_num_rows($checkResult) == 0) {
                    // ดึงรายชื่อหลักสูตรทั้งหมดจากฐานข้อมูล
                    $sql_Course = "SELECT course_id, course_name FROM course";
                    $query = mysqli_query($conn, $sql_Course);

                    $bestMatch = null;
                    $highestSimilarity = 0;

                    // วนลูปเพื่อเปรียบเทียบ course_name จาก CSV กับทุก course_name ในฐานข้อมูล
                    while ($course_row = mysqli_fetch_assoc($query)) {
                        $dbCourseName = $course_row['course_name'];
                        similar_text($std_course, $dbCourseName, $similarityPercentage);

                        if ($similarityPercentage > $highestSimilarity) {
                            $highestSimilarity = $similarityPercentage;
                            $bestMatch = $course_row;
                        }
                    }

                    if ($bestMatch && $highestSimilarity > 50) {
                        $course_id = $bestMatch['course_id'];

                        // แทรกข้อมูลเข้าในตารางฐานข้อมูล student
                        $stmt = $conn->prepare("INSERT INTO student (student_id, student_prefix, student_surname, student_lastname, course, year, gpax, student_tel) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                        $stmt->bind_param("ssssiids", $std_id, $std_prefix, $std_surname, $std_lastname, $course_id, $std_year, $std_gpax, $std_tel);

                        // แทรกข้อมูลเข้าสู่ตาราง login
                        $stmt_login = $conn->prepare("INSERT INTO login (email, password, type, user_id) VALUES (?, ?, ?, ?)");
                        $stmt_login->bind_param("ssss", $email, $std_id, $std_type, $std_id);

                        if ($stmt->execute() && $stmt_login->execute()) {
                            // If both queries are successful, redirect to the specified page
                            echo '<script type="text/javascript">';
                            echo 'document.location.href = "relateddatauser.php";';
                            echo '</script>';
                        } else {
                            // Handle errors if the queries fail
                            echo 'Error: Could not execute the queries.';
                        }
                    }
                }
            }
        }

        if (!empty($errors)) {
            $alertMessage = "The following errors occurred: " . implode(", ", $errors);
        }

        // ลบไฟล์ CSV หลังจากการอัพโหลดสำเร็จ
        if (empty($errors) && isset($_SESSION['uploaded_file_name'])) {
            if (file_exists($_SESSION['uploaded_file_name'])) {
                unlink($_SESSION['uploaded_file_name']); // ลบไฟล์
                unset($_SESSION['uploaded_file_name']); // ลบ session ของไฟล์
                unset($_SESSION['csvData']); // ลบ session ของไฟล์
            }
        }
    }
}
?>