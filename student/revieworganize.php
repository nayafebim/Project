<?php
// เชื่อมต่อกับฐานข้อมูล
include('../connection.php');
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['UserID'])) {
?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "กรุณาล็อกอินก่อนใช้งาน!!",
            timer: 3000,
            showConfirmButton: false
        }).then(() => {
            document.location.href = '../index.php'; // เปลี่ยนไปที่หน้าเข้าสู่ระบบ
        });
    </script>
    <?php
    exit();
}

$User_ID = $_SESSION['UserID'];

$STD_ID = null;
if (isset($_GET["student_id"])) {
    $STD_ID = $_GET["student_id"];
}

?>


<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>โครงการสหกิจศึกษา - รีวิวสถานประกอบการ</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-gradient-to-br from-green-700 to-teal-500 min-h-screen text-white">

    <?php include "header.php" ?>

    <!-- Main Content -->
    <main class="p-4">
        <section class="bg-white rounded-lg shadow-lg p-6 text-green-900">
            <h1 class="text-2xl font-bold mb-2 text-center text-green-700">โครงการสหกิจศึกษา</h1>
            <h2 class="text-xl font-semibold mb-4 text-center">คณะวิทยาศาสตร์และนวัตกรรมดิจิทัล</h2>
            <h3 class="text-center text-green-700">มหาวิทยาลัยทักษิณ วิทยาเขตพัทลุง</h3>

            <!-- <div class="grid grid-cols-1 md:grid-cols-3 gap-4 my-6">
                <button class="bg-yellow-400 text-white py-3 px-4 rounded-lg text-center">ผลการเลือกสถานที่ฝึกงานในประเทศ</button>
                <button class="bg-orange-400 text-white py-3 px-4 rounded-lg text-center">ผลการเลือกสถานที่ประกอบการ</button>
                <button class="bg-green-400 text-white py-3 px-4 rounded-lg text-center">จังหวัดที่นิสิตนิยมเลือกฝึกสหกิจศึกษา</button>
            </div> -->

            <div class="bg-green-100 rounded-lg p-4 shadow-inner mb-6">
                <h3 class="text-lg font-semibold mb-4">รีวิวสถานประกอบการ</h3>

                <?php
                include('../connection.php');

                // Fetch Org
                $sql_org = "
                                    SELECT i.*, o.*, t.type_name, p.position_name, tam.name_th AS district_name, am.name_th AS amphure_name, pro.name_th AS province_name
                                    FROM intern i
                                    JOIN organization o ON i.organization_id = o.organization_id
                                    JOIN type_organization t ON o.type_organization = t.type_id
                                    JOIN position_type p ON i.position = p.position_id
                                    JOIN thai_tambons tam ON o.district = tam.id
                                    JOIN thai_amphures am ON o.amphure = am.id
                                    JOIN thai_provinces pro ON o.province = pro.id
                                    WHERE i.student_id = '$STD_ID'
                                ";

                $result_org = mysqli_query($conn, $sql_org);

                // Fetch and display student details
                while ($row_org = mysqli_fetch_array($result_org)) {
                    $organization = $row_org['organization_id'];
                ?>
                    <p><strong>ชื่อสถานประกอบการ:</strong> <?php echo htmlspecialchars($row_org['organization_name']); ?></p>
                    <p><strong>ประเภทสถานประกอบการ:</strong> <?php echo htmlspecialchars($row_org['type_name']); ?></p>
                    <p><strong>ตำแหน่งที่ต้องการฝึก:</strong> <?php echo htmlspecialchars($row_org['position_name']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($row_org['email']); ?></p>
                    <p><strong>เบอร์โทร:</strong> <?php echo htmlspecialchars($row_org['tel_phone']); ?></p>
                    <p><strong>Fax:</strong> <?php echo htmlspecialchars($row_org['fax']); ?></p>
                    <p><strong>Website บริษัท:</strong> <?php echo htmlspecialchars($row_org['website']); ?></p>
                    <p><strong>ที่อยู่ของสถานประกอบการ:</strong> เลขที่ <?php echo htmlspecialchars($row_org['address_number']); ?> หมู่ที่ <?php echo htmlspecialchars($row_org['moo']); ?> ชั้น/อาคาร <?php echo htmlspecialchars($row_org['floor']); ?> ซอย <?php echo htmlspecialchars($row_org['soy']); ?> ถนน <?php echo htmlspecialchars($row_org['road']); ?> ตำบล <?php echo htmlspecialchars($row_org['district_name']); ?> อำเภอ <?php echo htmlspecialchars($row_org['amphure_name']); ?> จังหวัด <?php echo htmlspecialchars($row_org['province_name']); ?> รหัสไปรษณีย์ <?php echo htmlspecialchars($row_org['zip_code']); ?></p>
                <?php
                }
                ?>

            </div>

            <form method="POST">
                <div class="mb-4">
                    <label for="comment" class="block text-green-800 font-semibold mb-2">แสดงความเห็น</label>
                    <textarea id="comment" name="review_text" class="w-full p-3 rounded-lg border border-gray-300 focus:outline-none" rows="4" placeholder="สถานประกอบการนี้ดีมาก ๆ ใส่ความคิดเห็นของคุณที่นี่"></textarea>
                </div>
                <div class="text-center">
                    <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg">บันทึก</button>
                </div>

            </form>


        </section>
    </main>

    <footer class="bg-green-900 text-white text-center py-4 mt-8">
        <p>© 2024 มหาวิทยาลัยทักษิณ วิทยาเขตพัทลุง</p>
    </footer>

</body>

</html>


<?php
// เชื่อมต่อกับฐานข้อมูล

// Query ฐานข้อมูลเพื่อหาข้อมูลนักศึกษา
$sql = "SELECT * FROM intern WHERE student_id = '$STD_ID'";
$result = $conn->query($sql);

// ตรวจสอบว่ามีข้อมูลในตารางหรือไม่
if ($result->num_rows == 0) {
?>
    <script>
        Swal.fire({
            icon: 'warning',
            title: 'ไม่พบข้อมูล',
            text: 'กรุณาเพิ่มสถานประกอบการก่อนทำการรีวิวค่ะ',
            timer: 3000,
            showConfirmButton: false
        }).then(() => {
            document.location.href = 'index.php'; // เด้งไปหน้าแรก
        });
    </script>
<?php
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // รับค่าจากฟอร์ม
    $review_text = mysqli_real_escape_string($conn, $_POST['review_text']);
    $organization_id = $organization; // ตัวอย่าง: รหัสสถานประกอบการ (ควรเปลี่ยนให้เหมาะสม)
    $student_id = $_SESSION['UserID'];  // ตัวอย่าง: รหัสนิสิต (ควรเปลี่ยนให้เหมาะสม)
    // ตั้งค่าเขตเวลาเป็นเวลาประเทศไทย
    date_default_timezone_set('Asia/Bangkok');
    $date = date("Y-m-d H:i:s");

    // var_dump($_POST, $organization_id, $student_id);

    // ตรวจสอบว่ามีการกรอก review_text หรือไม่
    if (!empty($review_text)) {
        // SQL สำหรับเพิ่มข้อมูลลงในตาราง review
        $sql = "INSERT INTO review (review_text, organization_id, student_id, review_date) 
                VALUES ('$review_text', '$organization_id', '$student_id', '$date')";

        // ตรวจสอบการบันทึกข้อมูล
        if (mysqli_query($conn, $sql)) {
            // เมื่อบันทึกสำเร็จ ให้ redirect ไปที่หน้า indexreview.php
            header("Location: indexreview.php");
            exit();
        } else {
            echo "เกิดข้อผิดพลาด: " . mysqli_error($conn);
        }
    } else {
        echo "กรุณากรอกข้อมูลรีวิว";
    }

    // ปิดการเชื่อมต่อฐานข้อมูล
    mysqli_close($conn);
}
?>