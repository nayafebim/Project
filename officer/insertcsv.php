<?php
// เปิดการรายงานข้อผิดพลาด (สำหรับการดีบัก)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// การเชื่อมต่อกับฐานข้อมูล
include("../connection.php");

// ตรวจสอบการเชื่อมต่อกับฐานข้อมูล
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ตัวแปรสำหรับเก็บข้อความแจ้งเตือน
$alertMessage = "";

// ตรวจสอบว่ามีการอัปโหลดไฟล์หรือไม่
if (isset($_POST['submit'])) {
    // ตรวจสอบว่ามีไฟล์ที่อัปโหลด
    if ($_FILES['csv_file']['error'] === 0) {
        $csvFile = fopen($_FILES['csv_file']['tmp_name'], 'r');

        // ข้ามแถวแรก (header) ถ้ามี header
        fgetcsv($csvFile);

        // นำเข้าข้อมูลจากไฟล์ CSV ทีละแถว
        while (($row = fgetcsv($csvFile)) !== FALSE) {
            // ตรวจสอบว่ามีจำนวนคอลัมน์ครบ 8 คอลัมน์หรือไม่
            if (count($row) == 8) {
                // ป้องกันการ SQL injection
                $std_id = (int) $row[0];
                $std_prefix = mysqli_real_escape_string($conn, $row[1]);
                $std_surname = mysqli_real_escape_string($conn, $row[2]);
                $std_lastname = mysqli_real_escape_string($conn, $row[3]);
                $std_course = (int) $row[4];
                $std_year = (float) $row[5];
                $std_gpax = (float) $row[6];
                $std_tel = mysqli_real_escape_string($conn, $row[7]);

                // แทรกข้อมูลเข้าในตารางฐานข้อมูล
                $sql = "INSERT INTO student (student_id, student_prefix, student_surname, student_lastname, course, year, gpax, student_tel) 
                        VALUES ('$std_id', '$std_prefix', '$std_surname', '$std_lastname', '$std_course', '$std_year', '$std_gpax', '$std_tel')";

                // ตรวจสอบและรันคำสั่ง SQL
                if ($conn->query($sql) !== TRUE) {
                    $alertMessage = "Error: " . $sql . "<br>" . $conn->error;
                    break;
                }
            } else {
                // จำนวนคอลัมน์ไม่ครบ
                $alertMessage = "จำนวนคอลัมน์ในไฟล์ CSV ไม่ครบ! กรุณาตรวจสอบไฟล์ของคุณ";
                break;
            }
        }

        // ปิดไฟล์ CSV หลังจากอ่านเสร็จ
        fclose($csvFile);

        // แสดงข้อความเมื่อการนำเข้าข้อมูลเสร็จสมบูรณ์ (ถ้าไม่มีปัญหา)
        if (empty($alertMessage)) {
            $alertMessage = "CSV data imported successfully!";
        }
    } else {
        $alertMessage = "Error uploading the file.";
    }
}



// ดึงข้อมูลจากฐานข้อมูลเพื่อแสดงผลในรูปแบบตาราง
$sql_select = "SELECT * FROM student";
$result = $conn->query($sql_select);

?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload CSV and Display Data</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- <script>
        // แสดงข้อความแจ้งเตือนถ้ามี
        const alertMessage = "<?php echo $alertMessage; ?>";

        if (alertMessage !== "") {
            alert(alertMessage);
        }
    </script> -->
</head>

<body class="bg-gray-100 text-black">
    <?php include "header.php" ?>
    <div class="container mx-auto py-10">
        <h1 class="text-2xl font-bold mb-4">อัปโหลดไฟล์ CSV และแสดงข้อมูล</h1>

        <!-- Form for CSV upload -->
        <div class="bg-white p-6 rounded-lg shadow-lg mb-6">
            <h3 class="text-xl font-bold text-gray-700 mb-4">อัปโหลดไฟล์ CSV</h3>
            <form action="" method="POST" enctype="multipart/form-data" class="flex space-x-4 items-center">
                <input type="file" name="csv_file" class="border p-2 rounded-lg flex-grow" accept=".csv">
                <button type="submit" name="submit" class="bg-blue-500 text-white py-2 px-4 rounded-lg">อัปโหลดไฟล์</button>
            </form>
        </div>

        <!-- Display the uploaded data in a table -->
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h3 class="text-xl font-bold text-gray-700 mb-4">ข้อมูลจากไฟล์ CSV</h3>

            <table class="min-w-full bg-white border border-gray-300">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="py-2 px-4">รหัสนิสิต</th>
                        <th class="py-2 px-4">คำนำหน้า</th>
                        <th class="py-2 px-4">ชื่อ</th>
                        <th class="py-2 px-4">นามสกุล</th>
                        <th class="py-2 px-4">หลักสูตร</th>
                        <th class="py-2 px-4">ชั้นปี</th>
                        <th class="py-2 px-4">เกรดเฉลี่ย</th>
                        <th class="py-2 px-4">เบอร์โทรศัพท์</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // ตรวจสอบว่ามีการอัปโหลดไฟล์หรือไม่
                    if (isset($_POST['submit']) && isset($_FILES['csv_file'])) {
                        // ตรวจสอบว่ามีข้อผิดพลาดในไฟล์หรือไม่
                        if ($_FILES['csv_file']['error'] === 0) {
                            // เปิดไฟล์ CSV
                            $csvFile = fopen($_FILES['csv_file']['tmp_name'], 'r');
                            
                            // ข้ามแถวแรก (header) ถ้ามี header
                            fgetcsv($csvFile);

                            // อ่านข้อมูลจากไฟล์ CSV ทีละแถว
                            while (($row = fgetcsv($csvFile)) !== FALSE) {
                                echo "<tr>";
                                echo "<td class='border px-4 py-2'>" . htmlspecialchars($row[0]) . "</td>"; // รหัสนิสิต
                                echo "<td class='border px-4 py-2'>" . htmlspecialchars($row[1]) . "</td>"; // คำนำหน้า
                                echo "<td class='border px-4 py-2'>" . htmlspecialchars($row[2]) . "</td>"; // ชื่อ
                                echo "<td class='border px-4 py-2'>" . htmlspecialchars($row[3]) . "</td>"; // นามสกุล
                                echo "<td class='border px-4 py-2'>" . htmlspecialchars($row[4]) . "</td>"; // หลักสูตร
                                echo "<td class='border px-4 py-2'>" . htmlspecialchars($row[5]) . "</td>"; // ชั้นปี
                                echo "<td class='border px-4 py-2'>" . htmlspecialchars($row[6]) . "</td>"; // เกรดเฉลี่ย
                                echo "<td class='border px-4 py-2'>" . htmlspecialchars($row[7]) . "</td>"; // เบอร์โทรศัพท์
                                echo "</tr>";
                            }

                            // ปิดไฟล์หลังจากอ่านเสร็จ
                            fclose($csvFile);
                        } else {
                            echo "<tr><td colspan='8' class='border px-4 py-2 text-center'>Error: ไม่สามารถเปิดไฟล์ CSV ได้</td></tr>";
                        }
                    } else {
                        echo "<tr><td colspan='8' class='border px-4 py-2 text-center'>กรุณาอัปโหลดไฟล์ CSV</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>


</body>

</html>

<?php
// ปิดการเชื่อมต่อฐานข้อมูล
// $conn->close();
?>