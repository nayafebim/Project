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

$User_ID = $_SESSION['UserID'];

if (isset($_GET["student_id"])) {
    $STD_ID = $_GET["student_id"];
}

// Fetch uploaded files and status for the user
$sql = "
    SELECT f.file_name, f.file_path, i.status 
    FROM file_std f
    JOIN intern i ON f.User_ID = i.student_id
    WHERE f.User_ID = '$STD_ID'
";

$result = mysqli_query($conn, $sql);
$files = [];

while ($row = mysqli_fetch_assoc($result)) {
    $files[$row['file_name']] = [
        'file_path' => $row['file_path'],
        'status' => $row['status']
    ];
}
?>
<script>                                                                                                        
// ส่งข้อมูลไฟล์ที่ดึงมาจาก PHP ไปยัง JavaScript
let files = <?php echo json_encode($files); ?>;
</script>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ตรวจสอบและอนุมัติเอกสารการเข้าร่วมโครงการสหกิจศึกษา</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="anonymous" referrerpolicy="no-referrer" />
    <style>
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: white;
            padding: 20px;
            border-radius: 8px;
            max-width: 100%;
            max-height: 80%;
            overflow: auto;
        }

        .pdf-preview {
            width: 100%;
            height: 80vh;
            /* Adjust height as needed */
        }

        #map-container {
            display: none;
        }
    </style>
</head>

<body class="bg-gradient-to-br from-green-700 to-teal-500 text-white font-sans min-h-screen">
    <!-- Navbar -->
    <?php include "header.php" ?>

    <main class="p-6">
        <section class="bg-white rounded-lg shadow-lg p-8 text-black max-w-6xl mx-auto">
            <h1 class="text-2xl font-bold mb-6 text-center text-green-700">ตรวจสอบและอนุมัติเอกสารการเข้าร่วมโครงการสหกิจศึกษา</h1>
            <h2 class="text-xl font-semibold mb-8 text-center">คณะวิทยาศาสตร์และนวัตกรรมดิจิทัล</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Student Information -->
                <div class="bg-green-100 p-6 rounded-lg">
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
                        <h3 class="text-lg font-semibold mb-6">ข้อมูลนิสิต</h3>
                        <div class="flex items-center mb-6">
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

                        <h3 class="text-lg font-semibold mb-4">ข้อมูลสถานประกอบการของนิสิต</h3>
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

                <!-- Document Information -->
                <div class="bg-green-100 p-6 rounded-lg">
                    <h3 class="text-lg font-semibold mb-6">เอกสารการเข้าร่วมโครงการสหกิจศึกษา</h3>
                    <ul class="mb-6">
                        <li class="flex justify-between items-center mb-4">
                            <span>ใบสมัครเข้าร่วมโครงการสหกิจศึกษา</span>
                            <?php if (isset($files["{$STD_ID}_ใบสมัครเข้าร่วมโครงการสหกิจศึกษา.pdf"])): ?>
                                <button type="button" class="bg-green-500 text-white px-3 py-1 rounded-lg flex items-center gap-2" onclick="openModal('<?php echo $files["{$STD_ID}_ใบสมัครเข้าร่วมโครงการสหกิจศึกษา.pdf"]; ?>')">
                                    <i class="fas fa-eye"></i> ดู
                                </button>
                                <a href="<?php echo $files["{$STD_ID}_ใบสมัครเข้าร่วมโครงการสหกิจศึกษา.pdf"]; ?>" class="text-blue-500 flex items-center gap-2" download>
                                    <i class="fas fa-file-pdf"></i> ดาวน์โหลด
                                </a>
                            <?php else: ?>
                                <span class="text-red-500">ยังไม่ได้แนบไฟล์</span>
                            <?php endif; ?>
                        </li>
                        <li class="flex justify-between items-center mb-4">
                            <span>ใบรับรองผลการเรียน</span>
                            <?php if (isset($files["{$STD_ID}_ใบรับรองผลการเรียน.pdf"])): ?>
                                <button type="button" class="bg-green-500 text-white px-3 py-1 rounded-lg flex items-center gap-2" onclick="openModal('<?php echo $files["{$STD_ID}_ใบรับรองผลการเรียน.pdf"]; ?>')">
                                    <i class="fas fa-eye"></i> ดู
                                </button>
                                <a href="<?php echo $files["{$STD_ID}_ใบรับรองผลการเรียน.pdf"]; ?>" class="text-blue-500 flex items-center gap-2" download>
                                    <i class="fas fa-file-pdf"></i> ดาวน์โหลด
                                </a>
                            <?php else: ?>
                                <span class="text-red-500">ยังไม่ได้แนบไฟล์</span>
                            <?php endif; ?>
                        </li>
                        <li class="flex justify-between items-center mb-4">
                            <span>ข้อมูลประวัติส่วนตัว (Curriculum Vitae : CV)</span>
                            <?php if (isset($files["{$STD_ID}_CV.pdf"])): ?>
                                <button type="button" class="bg-green-500 text-white px-3 py-1 rounded-lg flex items-center gap-2" onclick="openModal('<?php echo $files["{$STD_ID}_CV.pdf"]; ?>')">
                                    <i class="fas fa-eye"></i> ดู
                                </button>
                                <a href="<?php echo $files["{$STD_ID}_CV.pdf"]; ?>" class="text-blue-500 flex items-center gap-2" download>
                                    <i class="fas fa-file-pdf"></i> ดาวน์โหลด
                                </a>
                            <?php else: ?>
                                <span class="text-red-500">ยังไม่ได้แนบไฟล์</span>
                            <?php endif; ?>
                        </li>
                        <li class="flex justify-between items-center">
                            <span>ใบอนุญาตไปปฏิบัติงานสหกิจศึกษา</span>
                            <?php if (isset($files["{$STD_ID}_ใบอนุญาตไปปฏิบัติงานสหกิจศึกษา.pdf"])): ?>
                                <button type="button" class="bg-green-500 text-white px-3 py-1 rounded-lg flex items-center gap-2" onclick="openModal('<?php echo $files["{$STD_ID}_ใบอนุญาตไปปฏิบัติงานสหกิจศึกษา.pdf"]; ?>')">
                                    <i class="fas fa-eye"></i> ดู
                                </button>
                                <a href="<?php echo $files["{$STD_ID}_ใบอนุญาตไปปฏิบัติงานสหกิจศึกษา.pdf"]; ?>" class="text-blue-500 flex items-center gap-2" download>
                                    <i class="fas fa-file-pdf"></i> ดาวน์โหลด
                                </a>
                            <?php else: ?>
                                <span class="text-red-500">ยังไม่ได้แนบไฟล์</span>
                            <?php endif; ?>
                        </li>
                    </ul>

                    <!-- <div class="mt-6">
                        <label for="reason" class="block mb-4">ระบุเหตุผลที่ไม่เห็นชอบการเข้าร่วมโครงการสหกิจศึกษา</label>
                        <textarea id="reason" class="w-full p-4 border rounded-lg h-32"></textarea>
                    </div> -->

                    <div class="mt-10">
                        <?php
                        include('../connection.php');

                        // Fetch Org
                        $sql_status = "
                                        SELECT status
                                        FROM intern
                                        WHERE student_id = '$STD_ID'
                                    ";

                        $result_status = mysqli_query($conn, $sql_status);

                        // Fetch and display student details
                        while ($row_status = mysqli_fetch_array($result_status)) {
                            switch ($row_status['status']) {
                                case 1:
                        ?>
                                    <div class="flex justify-center space-x-6 ">
                                        <button onclick="confirmAction('approved', <?php echo $STD_ID; ?>)" class="bg-green-600 text-white px-6 py-2 rounded-lg flex items-center space-x-2">
                                            <div class="flex justify-center items-center">
                                                <i class="fas fa-check w-5 h-5 me-1 pt-0.5"></i>
                                                <span>เห็นชอบ</span>
                                            </div>
                                        </button>
                                        <button onclick="confirmAction('unapproved', <?php echo $STD_ID; ?>)" class="bg-red-600 text-white px-6 py-2 rounded-lg flex items-center space-x-2">
                                            <div class="flex justify-center items-center">
                                                <i class="fas fa-times w-5 h-5 me-1 pt-0.5"></i>
                                                <span>ไม่เห็นชอบ</span>
                                            </div>
                                        </button>
                                    </div>
                                <?php
                                    break;

                                case 2:
                                ?>
                                    <div class="flex flex-col items-center space-y-4 mt-10">
                                        <span class="text-green-600">คุณได้ทำการอนุมัตินิสิตคนนี้แล้ว!!</span>
                                    </div>

                                <?php
                                    break;

                                case 3:
                                ?>
                                    <div class="flex flex-col items-center space-y-4 mt-10">
                                        <span class="text-red-600">คุณได้ทำการไม่อนุมัตินิสิตคนนี้แล้ว!!</span>
                                        <div class="flex justify-center space-x-6 pt-2">
                                            <button onclick="confirmAction('approved', <?php echo $STD_ID; ?>)" class="bg-green-600 text-white px-6 py-2 rounded-lg flex items-center space-x-2">
                                                <div class="flex justify-center items-center">
                                                    <i class="fas fa-check w-5 h-5 me-1 pt-0.5"></i>
                                                    <span>เห็นชอบ</span>
                                                </div>
                                            </button>
                                            <button onclick="confirmAction('unapproved', <?php echo $STD_ID; ?>)" class="bg-red-600 text-white px-6 py-2 rounded-lg flex items-center space-x-2">
                                                <div class="flex justify-center items-center">
                                                    <i class="fas fa-times w-5 h-5 me-1 pt-0.5"></i>
                                                    <span>ไม่เห็นชอบ</span>
                                                </div>
                                            </button>
                                        </div>
                                    </div>

                        <?php
                                    break;
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </section>
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
        function confirmAction(action, studentId) {
            let message = action === 'approved' ? 'คุณต้องการอนุมัติใช่หรือไม่?' : 'คุณต้องการไม่อนุมัติใช่หรือไม่?';
            let color = action === 'approved' ? '#008000' : '#D2042D';
            let textbutton = action === 'approved' ? 'อนุมัติ' : 'ไม่อนุมัติ';
            let inputOptions = action === 'unapproved' ? {
                input: 'textarea',
                inputPlaceholder: 'บอกเหตุผลที่ไม่อนุมัตินิสิตคนนี้...',
                inputAttributes: {
                    'aria-label': 'เหตุผล'
                }
            } : {};

            Swal.fire({
                title: 'ยืนยันการดำเนินการ',
                text: message,
                icon: 'warning',
                ...inputOptions,
                showCancelButton: true,
                confirmButtonText: textbutton,
                confirmButtonColor: color,
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    let text = result.value; // Get the value from the textarea if present
                    if (action === 'approved' || (action === 'unapproved' && text)) {
                        let form = document.createElement('form');
                        form.method = 'POST';
                        form.action = '';

                        let inputAction = document.createElement('input');
                        inputAction.type = 'hidden';
                        inputAction.name = action;
                        inputAction.value = '1';
                        form.appendChild(inputAction);

                        let inputStudentId = document.createElement('input');
                        inputStudentId.type = 'hidden';
                        inputStudentId.name = 'student_id';
                        inputStudentId.value = studentId;
                        form.appendChild(inputStudentId);

                        if (action === 'unapproved') {
                            let inputMessage = document.createElement('input');
                            inputMessage.type = 'hidden';
                            inputMessage.name = 'message';
                            inputMessage.value = text; // Add the textarea value
                            form.appendChild(inputMessage);
                        }

                        document.body.appendChild(form);
                        form.submit();
                    } else if (action === 'unapproved' && !text) {
                        Swal.fire({
                            icon: 'error',
                            title: 'กรุณากรอกเหตุผล!',
                            text: 'กรุณากรอกเหตุผลที่ไม่อนุมัตินิสิตคนนี้!!'
                        });
                    }
                }
            });
        }



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


        function confirmAction(action, studentId) {
    // ไฟล์ที่จำเป็นต้องมี
    let filesRequired = ['ใบสมัครเข้าร่วมโครงการสหกิจศึกษา', 'ใบรับรองผลการเรียน', 'CV', 'ใบอนุญาตไปปฏิบัติงานสหกิจศึกษา'];
    let missingFiles = [];

    // ตรวจสอบว่าไฟล์ที่จำเป็นมีอยู่หรือไม่
    filesRequired.forEach(function(file) {
        if (!files[`${studentId}_${file}.pdf`]) {
            missingFiles.push(file);
        }
    });

    // ถ้าขาดไฟล์ให้แสดงข้อความแจ้งเตือน
    if (missingFiles.length > 0) {
        Swal.fire({
            icon: 'error',
            title: 'ไม่สามารถดำเนินการได้',
            text: `เอกสารที่ขาด: ${missingFiles.join(', ')}`,
            timer: 3000,
            showConfirmButton: true
        });
        return; // หยุดการดำเนินการถ้าไม่มีไฟล์ครบ
    }

    // หากไฟล์ครบแล้วให้ดำเนินการต่อ
    let message = action === 'approved' ? 'คุณต้องการอนุมัติใช่หรือไม่?' : 'คุณต้องการไม่อนุมัติใช่หรือไม่?';
    let color = action === 'approved' ? '#008000' : '#D2042D';
    let textbutton = action === 'approved' ? 'อนุมัติ' : 'ไม่อนุมัติ';
    let inputOptions = action === 'unapproved' ? {
        input: 'textarea',
        inputPlaceholder: 'บอกเหตุผลที่ไม่อนุมัตินิสิตคนนี้...',
        inputAttributes: {
            'aria-label': 'เหตุผล'
        }
    } : {};

    Swal.fire({
        title: 'ยืนยันการดำเนินการ',
        text: message,
        icon: 'warning',
        ...inputOptions,
        showCancelButton: true,
        confirmButtonText: textbutton,
        confirmButtonColor: color,
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        if (result.isConfirmed) {
            let text = result.value; // Get the value from the textarea if present
            if (action === 'approved' || (action === 'unapproved' && text)) {
                let form = document.createElement('form');
                form.method = 'POST';
                form.action = '';

                let inputAction = document.createElement('input');
                inputAction.type = 'hidden';
                inputAction.name = action;
                inputAction.value = '1';
                form.appendChild(inputAction);

                let inputStudentId = document.createElement('input');
                inputStudentId.type = 'hidden';
                inputStudentId.name = 'student_id';
                inputStudentId.value = studentId;
                form.appendChild(inputStudentId);

                if (action === 'unapproved') {
                    let inputMessage = document.createElement('input');
                    inputMessage.type = 'hidden';
                    inputMessage.name = 'message';
                    inputMessage.value = text; // Add the textarea value
                    form.appendChild(inputMessage);
                }

                document.body.appendChild(form);
                form.submit();
            } else if (action === 'unapproved' && !text) {
                Swal.fire({
                    icon: 'error',
                    title: 'กรุณากรอกเหตุผล!',
                    text: 'กรุณากรอกเหตุผลที่ไม่อนุมัตินิสิตคนนี้!!'
                });
            }
        }
    });
}

    </script>
</body>

</html>

<?php
include('../connection.php');
if (isset($_POST['approved'])) {
    $STD = $_GET["student_id"]; // Ensure this is sanitized
    $sql = "UPDATE `intern` SET status = 2 WHERE student_id = $STD";
    $res = mysqli_query($conn, $sql) or die(mysqli_error($conn));
    if ($res) {
?>
        <script>
            Swal.fire({
                icon: 'success',
                title: "อนุมัติสำเร็จ!!",
                text: "โปรดรอสักครู่",
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                document.location.href = 'index.php';
            });
        </script>
    <?php
    }
    mysqli_close($conn);
}

if (isset($_POST['unapproved'])) {
    $STD = $_GET["student_id"]; // Ensure this is sanitized
    $message = $_POST['message']; // Fetch the message from POST

    // Update query
    $sql = "UPDATE `intern` SET status = 3, detail = ? WHERE student_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'si', $message, $STD);
    $res = mysqli_stmt_execute($stmt);

    if ($res) {
    ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: "ไม่อนุมัติสำเร็จ!!",
                text: "โปรดรอสักครู่",
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                document.location.href = 'index.php';
            });
        </script>

        
<?php
    }
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}


?>