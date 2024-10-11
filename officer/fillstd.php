<?php
// เชื่อมต่อกับฐานข้อมูล
include('../connection.php');
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['UserID'])) {
?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

$STD_ID = null;
if (isset($_GET["student_id"])) {
    $STD_ID = $_GET["student_id"];
}


// Fetch uploaded files for the user
$sql = "SELECT file_name, file_path FROM file_std WHERE User_ID = '$STD_ID'";
$result = mysqli_query($conn, $sql);
$files = [];
while ($row = mysqli_fetch_assoc($result)) {
    $files[$row['file_name']] = $row['file_path'];
}

?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ตรวจสอบและอนุมัติเอกสารการเข้าร่วมโครงการสหกิจศึกษา</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="anonymous" referrerpolicy="no-referrer" />
    <style>
        #map-container {
            display: none;
        }
    </style>

</head>

<body class="bg-gradient-to-br from-green-700 to-teal-500 text-white min-h-screen">

    <!-- Header -->
    <?php include "header.php" ?>

    <!-- Main Content -->
    <main class="p-6">
        <div class="bg-white text-black rounded-lg shadow-lg p-6 grid grid-cols-3 gap-6">
            <!-- Student Information -->
            <div class="col-span-1">
                <h2 class="text-xl font-bold mb-4 text-green-700">ข้อมูลนิสิต</h2>
                <?php
                include('../connection.php');

                // Fetch student
                $sql_std = "
                                    SELECT s.*, c.course_name 
                                    FROM student s
                                    JOIN course c ON s.course = c.course_id
                                    WHERE s.student_id = '$STD_ID'
                                ";
                $result_std = mysqli_query($conn, $sql_std);

                // Fetch and display student details
                while ($row_std = mysqli_fetch_array($result_std)) {
                ?>
                    <div class="flex items-center mb-4">
                        <img src="../assets/img/student/<?= $STD_ID ?>.jpg" alt="<?= $STD_ID ?>" width="120" height="auto" class="rounded-lg shadow-2xl me-4">
                        <div>
                            <p><strong>ชื่อ - สกุล:</strong> <?php echo htmlspecialchars($row_std['student_prefix'] . "" . $row_std['student_surname'] . " " . $row_std['student_lastname']) ?></p>
                            <p><strong>รหัสนิสิต:</strong> <?php echo htmlspecialchars($row_std['student_id']); ?></p>
                            <p><strong>หลักสูตร:</strong> <?php echo htmlspecialchars($row_std['course_name']); ?></p>
                            <p><strong>ชั้นปีที่:</strong> <?php echo htmlspecialchars($row_std['year']); ?></p>
                            <p><strong>เกรดเฉลี่ย:</strong> <?php echo htmlspecialchars($row_std['gpax']); ?></p>
                            <p><strong>เบอร์โทร:</strong> <?php echo htmlspecialchars($row_std['student_tel']); ?></p>
                            <p><strong>Email:</strong> <?php echo htmlspecialchars($row_std['student_id']); ?>@tsu.ac.th</p>
                        </div>
                    </div>
                <?php
                }
                ?>
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
                ?>
                    <h2 class="text-xl font-bold mb-4 text-green-700">ข้อมูลสถานประกอบการของนิสิต</h2>
                    <p><strong>ชื่อสถานประกอบการ:</strong> <?php echo htmlspecialchars($row_org['organization_name']); ?></p>
                    <p><strong>ประเภทสถานประกอบการ:</strong> <?php echo htmlspecialchars($row_org['type_name']); ?></p>
                    <p><strong>ตำแหน่งที่ต้องการฝึก:</strong> <?php echo htmlspecialchars($row_org['position_name']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($row_org['email']); ?></p>
                    <p><strong>เบอร์โทร:</strong> <?php echo htmlspecialchars($row_org['tel_phone']); ?></p>
                    <p><strong>Fax:</strong> <?php echo htmlspecialchars($row_org['fax']); ?></p>
                    <p><strong>Website บริษัท:</strong> <?php echo htmlspecialchars($row_org['website']); ?></p>
                    <p><strong>ที่อยู่ของสถานประกอบการ:</strong> เลขที่ <?php echo htmlspecialchars($row_org['address_number']); ?> หมู่ที่ <?php echo htmlspecialchars($row_org['moo']); ?> ชั้น/อาคาร <?php echo htmlspecialchars($row_org['floor']); ?> ซอย <?php echo htmlspecialchars($row_org['soy']); ?> ถนน <?php echo htmlspecialchars($row_org['road']); ?> ตำบล <?php echo htmlspecialchars($row_org['district_name']); ?> อำเภอ <?php echo htmlspecialchars($row_org['amphure_name']); ?> จังหวัด <?php echo htmlspecialchars($row_org['province_name']); ?> รหัสไปรษณีย์ <?php echo htmlspecialchars($row_org['zip_code']); ?></p>
                    <input type="hidden" id="company-address" name="company-address" placeholder="กรอกลิงค์ Google Maps" class="w-full border rounded-lg px-3 py-2" value="<?php echo htmlspecialchars($row_org['maps']); ?>" oninput="updateMap()">
                <?php
                }
                ?>
                <div id="map-container" class="flex items-center mt-5">
                    <iframe id="map" class="w-full h-[400px]" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                </div>
            </div>

            <!-- Documents -->
            <div class="col-span-2">
                <h2 class="text-xl font-bold mb-4 text-green-700">เอกสารการเข้าร่วมโครงการสหกิจศึกษา</h2>
                <table class="min-w-full bg-white text-black border border-green-700 rounded-lg shadow-md">
                    <thead class="bg-green-500 text-white">
                        <tr>
                            <th class="py-2 px-4 border">รายการเอกสาร</th>
                            <th class="py-2 px-4 border">ไฟล์</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-t">
                            <td class="py-2 px-4 border">ใบสมัครเข้าร่วมโครงการสหกิจศึกษา</td>
                            <td class="py-2 px-4 border flex items-center ">
                                <?php if (isset($files["{$STD_ID}_ใบสมัครเข้าร่วมโครงการสหกิจศึกษา.pdf"])): ?>
                                    <button type="button" class="bg-green-500 text-white px-3 py-1 rounded-lg flex items-center gap-2 mr-5" onclick="openModal('<?php echo $files["{$STD_ID}_ใบสมัครเข้าร่วมโครงการสหกิจศึกษา.pdf"]; ?>')">
                                        <i class="fa-solid fa-search ml-2"></i> ดู
                                    </button>
                                    <a href="<?php echo $files["{$STD_ID}_ใบสมัครเข้าร่วมโครงการสหกิจศึกษา.pdf"]; ?>" class="text-blue-500 flex items-center gap-2" download>
                                        <i class="fas fa-file-pdf"></i> ดาวน์โหลด
                                    </a>
                                <?php else: ?>
                                    <span class="text-red-500">ยังไม่ได้แนบไฟล์</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr class="border-t">
                            <td class="py-2 px-4 border">ใบรับรองผลการเรียน</td>
                            <td class="py-2 px-4 border flex items-center">
                                <?php if (isset($files["{$STD_ID}_ใบรับรองผลการเรียน.pdf"])): ?>
                                    <button type="button" class="bg-green-500 text-white px-3 py-1 rounded-lg flex items-center gap-2 mr-5" onclick="openModal('<?php echo $files["{$STD_ID}_ใบรับรองผลการเรียน.pdf"]; ?>')">
                                        <i class="fa-solid fa-search ml-2"></i> ดู
                                    </button>
                                    <a href="<?php echo $files["{$STD_ID}_ใบรับรองผลการเรียน.pdf"]; ?>" class="text-blue-500 flex items-center gap-2" download>
                                        <i class="fas fa-file-pdf"></i> ดาวน์โหลด
                                    </a>
                                <?php else: ?>
                                    <span class="text-red-500">ยังไม่ได้แนบไฟล์</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr class="border-t">
                            <td class="py-2 px-4 border">ข้อมูลประวัติส่วนตัว (Curriculum Vitae : CV)</td>
                            <td class="py-2 px-4 border flex items-center">
                                <?php if (isset($files["{$STD_ID}_CV.pdf"])): ?>
                                    <button type="button" class="bg-green-500 text-white px-3 py-1 rounded-lg flex items-center gap-2 mr-5" onclick="openModal('<?php echo $files["{$STD_ID}_CV.pdf"]; ?>')">
                                        <i class="fa-solid fa-search ml-2"></i> ดู
                                    </button>
                                    <a href="<?php echo $files["{$STD_ID}_CV.pdf"]; ?>" class="text-blue-500 flex items-center gap-2" download>
                                        <i class="fas fa-file-pdf"></i> ดาวน์โหลด
                                    </a>
                                <?php else: ?>
                                    <span class="text-red-500">ยังไม่ได้แนบไฟล์</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr class="border-t">
                            <td class="py-2 px-4 border">ใบอนุญาตไปปฏิบัติงานสหกิจศึกษา</td>
                            <td class="py-2 px-4 border flex items-center">
                                <?php if (isset($files["{$STD_ID}_ใบอนุญาตไปปฏิบัติงานสหกิจศึกษา.pdf"])): ?>
                                    <button type="button" class="bg-green-500 text-white px-3 py-1 rounded-lg flex items-center gap-2 mr-5" onclick="openModal('<?php echo $files["{$STD_ID}_ใบอนุญาตไปปฏิบัติงานสหกิจศึกษา.pdf"]; ?>')">
                                        <i class="fa-solid fa-search ml-2"></i> ดู
                                    </button>
                                    <a href="<?php echo $files["{$STD_ID}_ใบอนุญาตไปปฏิบัติงานสหกิจศึกษา.pdf"]; ?>" class="text-blue-500 flex items-center gap-2" download>
                                        <i class="fas fa-file-pdf"></i> ดาวน์โหลด
                                    </a>
                                <?php else: ?>
                                    <span class="text-red-500">ยังไม่ได้แนบไฟล์</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div class="flex justify-end mt-4 space-x-2">
                    <button class="bg-black text-white px-4 py-2 rounded-lg">แก้ไขข้อมูลนิสิต</button>
                    <button class="bg-purple-600 text-white px-4 py-2 rounded-lg"> 
                    <a id="pdfLink" href="docorganize.php" target="_blank">ทำหนังสือส่งตัว</a></button>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal for PDF Preview -->
    <div id="pdfModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" style="display: none;" onclick="closeModal(event)">
        <div class="bg-white rounded-xl overflow-hidden w-11/12 md:w-3/4 lg:w-1/2" onclick="event.stopPropagation();">
            <div class="relative" style="padding-top: 80%;">
                <object id="pdfPreview" data="" type="application/pdf" width="100%" height="100%" style="position: absolute; top: 0; left: 0;">
                    <p><a id="pdfLink" href="" target="_blank">Open!</a></p>
                </object>
            </div>
        </div>
    </div>



    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function openModal(pdfUrl) {
            const pdfPreview = document.getElementById('pdfPreview');
            const pdfLink = document.getElementById('pdfLink');
            const pdfModal = document.getElementById('pdfModal');

            pdfPreview.setAttribute('data', pdfUrl);
            pdfLink.setAttribute('href', pdfUrl);

            pdfModal.style.display = 'flex'; // Show modal
        }

        function closeModal(event) {
            if (event.target === document.getElementById('pdfModal')) {
                const pdfModal = document.getElementById('pdfModal');
                const pdfPreview = document.getElementById('pdfPreview');
                const pdfLink = document.getElementById('pdfLink');

                // Clear pdfPreview and pdfLink attributes
                pdfPreview.removeAttribute('data');
                pdfLink.removeAttribute('href');
                
                pdfModal.style.display = 'none'; // Hide modal
            }
        }



        document.addEventListener('DOMContentLoaded', function() {
            const companyAddressInput = document.getElementById('company-address');
            if (companyAddressInput.value) {
                updateMap();
            }
        });

        function updateMap() {
            const url = document.getElementById('company-address').value;
            const mapIframe = document.getElementById('map-container');
            const map = document.getElementById('map');

            // Regular expression to capture the essential part of the URL, including non-Latin characters
            const regex = /(?:https?:\/\/)?(?:www\.|maps\.)?google\.(?:com|co\.\w+)\/maps\/(?:place\/|search\/|view\/)?([^\/@]+)?(?:\/)?(?:@([\d\.\-]+),([\d\.\-]+))?/;
            const match = url.match(regex);

            if (match) {
                // Decode the place name from URL encoding to readable text
                const decodedPlaceName = match[1] ? decodeURIComponent(match[1]) : '';
                const locationQuery = decodedPlaceName || `${match[2]},${match[3]}`;

                const apikey = "AIzaSyCNqYqtAs0oRG2xyLEabqe7_Hihq7nZJwU"; // Replace with your actual API key

                // Construct the iframe source URL with the decoded place name
                map.src = `https://www.google.com/maps/embed/v1/place?q=${encodeURIComponent(locationQuery)}&key=${apikey}`;

                // Show the map container
                mapIframe.style.display = 'block';
            } else {
                // Hide the map container
                mapIframe.style.display = 'none';
                map.src = ''; // Clear the map if the URL is not valid
            }
        }
    </script>

</body>

</html>