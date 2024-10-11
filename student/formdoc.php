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

// Fetch uploaded files for the user
$sql = "SELECT file_name, file_path FROM file_std WHERE User_ID = '$User_ID'";
$result = mysqli_query($conn, $sql);
$files = [];
while ($row = mysqli_fetch_assoc($result)) {
    $files[$row['file_name']] = $row['file_path'];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เอกสารนิสิตสำหรับเข้าร่วมโครงการสหกิจศึกษา</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="anonymous" referrerpolicy="no-referrer" />
    <style>
        .file-upload-cell {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }

        .file-upload-cell i {
            font-size: 1.2rem;
        }

        .file-upload-cell a,
        .file-upload-cell button,
        .file-upload-cell label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

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
    </style>
</head>

<body class="bg-gradient-to-br from-green-700 to-teal-500 text-white font-sans min-h-screen">
    <!-- header -->
    <?php include "header.php" ?>

    <main class="p-4">
        <section class="bg-white rounded-lg shadow-lg p-6 text-black">
            <h1 class="text-2xl font-bold mb-2 text-center text-green-700">เอกสารนิสิตสำหรับเข้าร่วมโครงการสหกิจศึกษา</h1>
            <h2 class="text-xl font-semibold mb-4 text-center">คณะวิทยาศาสตร์และนวัตกรรมดิจิทัล</h2>

            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-2">รายละเอียดและเอกสารแบบสำหรับนิสิตสำหรับเข้าร่วมโครงการสหกิจศึกษา</h3>
                <ul class="list-decimal list-inside">
                    <li>นิสิตสามารถดาวน์โหลดใบสมัครเข้าร่วมโครงการสหกิจศึกษาได้ที่เว็บไซต์ <a href="http://coopmis.tsu.ac.th/" class="text-blue-500">http://coopmis.tsu.ac.th/</a></li>
                    <li>นิสิตสามารถดาวน์โหลดใบรับรองผลการเรียนได้ที่ <a href="https://enroll.tsu.ac.th/" class="text-blue-500">https://enroll.tsu.ac.th/</a> และสามารถขอรับได้จากฝ่ายทะเบียน</li>
                    <li>ข้อมูลประวัติส่วนตัว (Curriculum Vitae : CV)</li>
                    <li>แบบฟอร์มขออนุญาตไปปฏิบัติงานสหกิจศึกษา</li>
                </ul>
            </div>

            <form method="POST" enctype="multipart/form-data">
                <div class="bg-green-100 rounded-lg p-4 shadow-inner">
                    <h3 class="text-lg font-semibold mb-2">เอกสารแบบในการเข้าร่วมโครงการสหกิจศึกษา</h3>
                    <table class="w-full text-left bg-white rounded-lg shadow-lg">
                        <thead>
                            <tr>
                                <th class="border px-4 py-2">รายการเอกสาร</th>
                                <th class="border px-4 py-2">การแนบเอกสาร</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="border px-4 py-2">ใบสมัครเข้าร่วมโครงการสหกิจศึกษา</td>
                                <td class="border px-4 py-2 file-upload-cell">
                                    <?php if (isset($files["{$User_ID}_ใบสมัครเข้าร่วมโครงการสหกิจศึกษา.pdf"])): ?>
                                        <button type="button" class="bg-green-500 text-white px-3 py-1 rounded-lg flex items-center gap-2" onclick="openModal('<?php echo $files["{$User_ID}_ใบสมัครเข้าร่วมโครงการสหกิจศึกษา.pdf"]; ?>')">
                                            <i class="fas fa-eye"></i> ดู
                                        </button>
                                        <a href="<?php echo $files["{$User_ID}_ใบสมัครเข้าร่วมโครงการสหกิจศึกษา.pdf"]; ?>" class="text-blue-500 flex items-center gap-2" download>
                                            <i class="fas fa-file-pdf"></i> ดาวน์โหลด
                                        </a>
                                    <?php else: ?>
                                        <input type="file" name="fileInput0" id="fileInput0" class="hidden" accept="application/pdf" onchange="handleFileUpload(event, 0)">
                                        <span id="fileName0" class="flex-grow"></span>
                                        <label for="fileInput0" class="bg-blue-500 text-white px-3 py-1 rounded-lg cursor-pointer flex items-center gap-2">
                                            <i class="fas fa-upload"></i> แนบไฟล์
                                        </label>
                                        <button type="button" class="bg-red-500 text-white px-3 py-1 rounded-lg flex items-center gap-2" onclick="cancelUpload(0)">
                                            <i class="fas fa-times"></i> ยกเลิก
                                        </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="border px-4 py-2">ใบรับรองผลการเรียน</td>
                                <td class="border px-4 py-2 file-upload-cell">
                                    <?php if (isset($files["{$User_ID}_ใบรับรองผลการเรียน.pdf"])): ?>
                                        <button type="button" class="bg-green-500 text-white px-3 py-1 rounded-lg flex items-center gap-2" onclick="openModal('<?php echo $files["{$User_ID}_ใบรับรองผลการเรียน.pdf"]; ?>')">
                                            <i class="fas fa-eye"></i> ดู
                                        </button>
                                        <a href="<?php echo $files["{$User_ID}_ใบรับรองผลการเรียน.pdf"]; ?>" class="text-blue-500 flex items-center gap-2" download>
                                            <i class="fas fa-file-pdf"></i> ดาวน์โหลด
                                        </a>
                                    <?php else: ?>
                                        <input type="file" name="fileInput1" id="fileInput1" class="hidden" accept="application/pdf" onchange="handleFileUpload(event, 1)">
                                        <span id="fileName1" class="flex-grow"></span>
                                        <label for="fileInput1" class="bg-blue-500 text-white px-3 py-1 rounded-lg cursor-pointer flex items-center gap-2">
                                            <i class="fas fa-upload"></i> แนบไฟล์
                                        </label>
                                        <button type="button" class="bg-red-500 text-white px-3 py-1 rounded-lg flex items-center gap-2" onclick="cancelUpload(1)">
                                            <i class="fas fa-times"></i> ยกเลิก
                                        </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="border px-4 py-2">ข้อมูลประวัติส่วนตัว (Curriculum Vitae : CV)</td>
                                <td class="border px-4 py-2 file-upload-cell">
                                    <?php if (isset($files["{$User_ID}_CV.pdf"])): ?>
                                        <button type="button" class="bg-green-500 text-white px-3 py-1 rounded-lg flex items-center gap-2" onclick="openModal('<?php echo $files["{$User_ID}_CV.pdf"]; ?>')">
                                            <i class="fas fa-eye"></i> ดู
                                        </button>
                                        <a href="<?php echo $files["{$User_ID}_CV.pdf"]; ?>" class="text-blue-500 flex items-center gap-2" download>
                                            <i class="fas fa-file-pdf"></i> ดาวน์โหลด
                                        </a>
                                    <?php else: ?>
                                        <input type="file" name="fileInput2" id="fileInput2" class="hidden" accept="application/pdf" onchange="handleFileUpload(event, 2)">
                                        <span id="fileName2" class="flex-grow"></span>
                                        <label for="fileInput2" class="bg-blue-500 text-white px-3 py-1 rounded-lg cursor-pointer flex items-center gap-2">
                                            <i class="fas fa-upload"></i> แนบไฟล์
                                        </label>
                                        <button type="button" class="bg-red-500 text-white px-3 py-1 rounded-lg flex items-center gap-2" onclick="cancelUpload(2)">
                                            <i class="fas fa-times"></i> ยกเลิก
                                        </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="border px-4 py-2">ใบอนุญาตไปปฏิบัติงานสหกิจศึกษา</td>
                                <td class="border px-4 py-2 file-upload-cell">
                                    <?php if (isset($files["{$User_ID}_ใบอนุญาตไปปฏิบัติงานสหกิจศึกษา.pdf"])): ?>
                                        <button type="button" class="bg-green-500 text-white px-3 py-1 rounded-lg flex items-center gap-2" onclick="openModal('<?php echo $files["{$User_ID}_ใบอนุญาตไปปฏิบัติงานสหกิจศึกษา.pdf"]; ?>')">
                                            <i class="fas fa-eye"></i> ดู
                                        </button>
                                        <a href="<?php echo $files["{$User_ID}_ใบอนุญาตไปปฏิบัติงานสหกิจศึกษา.pdf"]; ?>" class="text-blue-500 flex items-center gap-2" download>
                                            <i class="fas fa-file-pdf"></i> ดาวน์โหลด
                                        </a>
                                    <?php else: ?>
                                        <input type="file" name="fileInput3" id="fileInput3" class="hidden" accept="application/pdf" onchange="handleFileUpload(event, 3)">
                                        <span id="fileName3" class="flex-grow"></span>
                                        <label for="fileInput3" class="bg-blue-500 text-white px-3 py-1 rounded-lg cursor-pointer flex items-center gap-2">
                                            <i class="fas fa-upload"></i> แนบไฟล์
                                        </label>
                                        <button type="button" class="bg-red-500 text-white px-3 py-1 rounded-lg flex items-center gap-2" onclick="cancelUpload(3)">
                                            <i class="fas fa-times"></i> ยกเลิก
                                        </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <?php
                    // Fetch uploaded files for the user
                    include('../connection.php');
                    $sqlCount = "SELECT COUNT(*) AS total_files FROM file_std WHERE User_ID = '$User_ID'";
                    $resultCount = mysqli_query($conn, $sqlCount);
                    $rowCount = mysqli_fetch_assoc($resultCount);
                    $totalFiles = $rowCount['total_files'];
                    if ($totalFiles != 4) { ?>
                        <div class="flex justify-center mt-4">
                            <button type="button" class="bg-green-600 text-white px-6 py-2 rounded-lg" onclick="submitFiles()">บันทึก</button>
                        </div>
                    <?php } ?>
                </div>
            </form>
        </section>
    </main>

    <!-- Modal -->
    <div id="pdfModal" class="modal" onclick="closeModal(event)">
        <div class="modal-content" onclick="event.stopPropagation()">
            <span class="close" onclick="closeModal()">&times;</span>
            <iframe id="pdfPreview" class="pdf-preview" frameborder="0"></iframe>
        </div>
    </div>

    <script>
        function handleFileUpload(event, index) {
            const file = event.target.files[0];
            if (file) {
                document.getElementById(`fileName${index}`).textContent = file.name;
            }
        }

        function cancelUpload(index) {
            document.getElementById(`fileInput${index}`).value = "";
            document.getElementById(`fileName${index}`).textContent = "";
        }

        function submitFiles() {
            const totalFiles = 4;
            let allFilesAttached = true;
            for (let i = 0; i < totalFiles; i++) {
                const fileName = document.getElementById(`fileName${i}`).textContent;
                if (fileName === "") {
                    allFilesAttached = false;
                    break;
                }
            }

            if (allFilesAttached) {
                // ส่งฟอร์มไปยังเซิร์ฟเวอร์
                document.forms[0].submit(); // <-- เพิ่มบรรทัดนี้เพื่อส่งฟอร์ม
            }
        }

        function openModal(fileUrl) {
            document.getElementById('pdfPreview').src = fileUrl;
            document.getElementById('pdfModal').style.display = 'flex';
        }

        function closeModal(event) {
            if (event.target === document.getElementById('pdfModal')) {
                document.getElementById('pdfModal').style.display = 'none';
            }
        }
    </script>
</body>

</html>

<?php
include('../connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $uploadDir = '../assets/uploads/';
    $uploadStatus = 1;
    $uploadedFiles = [];
    $fileNames = [
        "ใบสมัครเข้าร่วมโครงการสหกิจศึกษา",
        "ใบรับรองผลการเรียน",
        "CV",
        "ใบอนุญาตไปปฏิบัติงานสหกิจศึกษา"
    ];

    // Create uploads directory if not exists
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    for ($i = 0; $i < 4; $i++) {
        $fileInput = "fileInput{$i}";
        if (!empty($_FILES[$fileInput]['name'])) {
            // Get the file extension
            $fileType = strtolower(pathinfo($_FILES[$fileInput]['name'], PATHINFO_EXTENSION));

            // Generate new file name based on the user ID and document type
            $newFileName = $User_ID . "_" . $fileNames[$i] . "." . $fileType;
            $uploadFilePath = $uploadDir . $newFileName;

            // ตรวจสอบว่ามีไฟล์ซ้ำในฐานข้อมูลหรือไม่
            $sqlCheck = "SELECT * FROM file_std WHERE file_name = '" . mysqli_real_escape_string($conn, $newFileName) . "' AND User_ID = '" . mysqli_real_escape_string($conn, $User_ID) . "'";
            $resultCheck = mysqli_query($conn, $sqlCheck);

            if (mysqli_num_rows($resultCheck) > 0) {
                echo "<script>
                    Swal.fire({
                        icon: 'warning',
                        title: 'ขออภัย',
                        text: 'ไฟล์นี้มีอยู่ในระบบแล้ว!',
                        timer: 3000,
                        showConfirmButton: false
                    });
                </script>";
                $uploadStatus = 0;
                continue; // ข้ามการอัปโหลดไฟล์นี้
            }

            // ตรวจสอบขนาดไฟล์ (จำกัดที่ 5MB)
            if ($_FILES[$fileInput]['size'] > 5000000) {
                echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'ขออภัย',
                        text: 'ไฟล์ของคุณมีขนาดใหญ่เกินไป!',
                        timer: 3000,
                        showConfirmButton: false
                    });
                </script>";
                $uploadStatus = 0;
            }

            // ตรวจสอบประเภทไฟล์
            if ($fileType != "pdf") {
                echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'ขออภัย',
                        text: 'เฉพาะไฟล์ PDF เท่านั้นที่อนุญาต!',
                        timer: 3000,
                        showConfirmButton: false
                    });
                </script>";
                $uploadStatus = 0;
            }

            // ตรวจสอบสถานะการอัปโหลด
            if ($uploadStatus == 0) {
                echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'ขออภัย',
                        text: 'ไฟล์ของคุณไม่ได้รับการอัปโหลด!',
                        timer: 3000,
                        showConfirmButton: false
                    });
                </script>";
            } else {
                if (move_uploaded_file($_FILES[$fileInput]['tmp_name'], $uploadFilePath)) {
                    // บันทึกข้อมูลไฟล์ลงในฐานข้อมูล
                    $sql = "INSERT INTO file_std (file_name, file_path, User_ID) 
                            VALUES ('" . mysqli_real_escape_string($conn, $newFileName) . "', 
                                    '" . mysqli_real_escape_string($conn, $uploadFilePath) . "', 
                                    '" . mysqli_real_escape_string($conn, $User_ID) . "')";
                    if (mysqli_query($conn, $sql)) {
                        $uploadedFiles[] = $uploadFilePath;
                    } else {
                        echo "<script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'เกิดข้อผิดพลาด: " . mysqli_error($conn) . "',
                            });
                        </script>";
                    }
                } else {
                    echo "<script>
                        Swal.fire({
                            icon: 'error',
                            title: 'ขออภัย',
                            text: 'มีข้อผิดพลาดในการอัปโหลดไฟล์ของคุณ',
                        });
                    </script>";
                }
            }
        }
    }

    if (!empty($uploadedFiles)) {
        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'สำเร็จ!',
                text: 'ไฟล์ของคุณถูกอัปโหลดสำเร็จ!',
                timer: 3000,
                showConfirmButton: false
            }).then(() => {
                window.location.href = 'formdoc.php';
            });
        </script>";
    }
}
?>