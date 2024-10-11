<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ตรวจสอบเอกสารโครงการสหกิจศึกษา</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="anonymous" referrerpolicy="no-referrer" />
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
        }

        #map-container {
            display: none;
        }
    </style>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-gradient-to-br from-green-700 to-teal-500 text-white min-h-screen">

    <!-- Header -->
    <?php include "header.php"; ?>
    <!-- Main Content -->
    <main class="p-6">
        <div class="bg-white text-black rounded-lg shadow-lg p-6">
            <h2 class="text-center text-2xl font-bold mb-6 text-green-700">ตรวจสอบเอกสารและจัดการข้อมูลนิสิตโครงการสหกิจศึกษา</h2>

            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-green-700">
                    <thead class="bg-green-500 text-white">
                        <tr>
                            <th class="py-2 px-4 border">ชื่อสถานประกอบการ</th>
                            <th class="py-2 px-4 border">เอกสารและผลตอบกลับ</th>
                            <th class="py-2 px-4 border">หนังสือส่งตัว</th>
                            <th class="py-2 px-4 border">ผลการตอบกลับ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include("../connection.php");

                        $sql = "SELECT intern.organization_id, organization.organization_name
                                FROM intern
                                JOIN organization ON intern.organization_id = organization.organization_id
                                WHERE intern.status = 2
                                GROUP BY intern.organization_id";
                        $query = mysqli_query($conn, $sql);

                        while ($result = mysqli_fetch_array($query)) {
                            $organizationId = $result['organization_id'];

                            // Fetch file details from the document table
                            $sqlFile = "SELECT file_name, file_path FROM document WHERE organization_id = '$organizationId'";
                            $fileQuery = mysqli_query($conn, $sqlFile);
                            $fileData = mysqli_fetch_assoc($fileQuery);

                            // Check if the file exists in the specified folder
                            $fileExists = false;
                            if ($fileData && file_exists($fileData['file_path'])) {
                                $fileExists = true;
                            }
                        ?>
                            <tr class="border-t">
                                <td class="py-2 px-4 border text-center w-1/4"><?= $result["organization_name"] ?></td>
                                <td class="py-2 px-4 border text-center w-1/4">
                                    <a href="organization_std.php?organization_id=<?= $result['organization_id'] ?>" class="bg-orange-400 text-white px-4 py-2 rounded">ตรวจสอบรายชื่อ</a>
                                </td>
                                <td class="py-2 px-4 border text-center w-1/4">
                                    <a href="downloaddoc.php?organization_id=<?= $result['organization_id'] ?>" class="bg-blue-500 text-white px-4 py-2 rounded flex items-center justify-center">
                                        <i class="fa-solid fa-download mr-2"></i>ดาวน์โหลดหนังสือ
                                    </a>
                                </td>


                                <td class="py-2 px-4 border">
                                    <?php if ($fileExists): ?>
                                        <!-- Display a link to view/download the file -->
                                        <a href="<?= $fileData['file_path'] ?>" target="_blank" class="bg-blue-500 text-white px-4 py-2 rounded">
                                            <i class="fa-solid fa-file-pdf mr-2"></i>ดูไฟล์
                                        </a>
                                    <?php else: ?>
                                        <!-- If no file exists, show the upload form -->
                                        <form method="POST" enctype="multipart/form-data">
                                            <input type="hidden" name="organizationId" value="<?= $organizationId ?>">
                                            <input type="file" name="fileInput" id="fileInput_<?= $organizationId ?>" class="hidden" accept="application/pdf" onchange="document.getElementById('uploadBtn_<?= $organizationId ?>').click();">
                                            <button type="button" class="bg-green-500 text-white px-4 py-2 rounded" onclick="document.getElementById('fileInput_<?= $organizationId ?>').click();">อัปโหลดการตอบกลับ</button>
                                            <button id="uploadBtn_<?= $organizationId ?>" type="submit" class="hidden">อัปโหลด</button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

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
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function openModal(pdfUrl) {
            const pdfPreview = document.getElementById('pdfPreview');
            const pdfLink = document.getElementById('pdfLink');
            const pdfModal = document.getElementById('pdfModal');

            pdfPreview.setAttribute('data', pdfUrl);
            pdfLink.setAttribute('href', pdfUrl);

            pdfModal.style.display = 'flex';
        }

        function closeModal(event) {
            if (event.target === document.getElementById('pdfModal')) {
                const pdfModal = document.getElementById('pdfModal');
                const pdfPreview = document.getElementById('pdfPreview');
                const pdfLink = document.getElementById('pdfLink');

                pdfPreview.removeAttribute('data');
                pdfLink.removeAttribute('href');

                pdfModal.style.display = 'none';
            }
        }
    </script>

</body>

</html>

<?php
include("../connection.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['organizationId'])) {
    $organizationId = $_POST['organizationId'];
    $fileInput = $_FILES['fileInput'];

    // Fetch the organization name based on organizationId
    $sqlOrg = "SELECT organization_name FROM organization WHERE organization_id='$organizationId'";
    $resultOrg = mysqli_query($conn, $sqlOrg);
    $orgData = mysqli_fetch_assoc($resultOrg);
    $organizationName = $orgData['organization_name'];

    // Define upload directory
    $uploadDir = "../assets/uploads/Intern/";

    // Clean the organization name to be used as the file name
    $cleanOrganizationName = preg_replace('/[^A-Za-z0-9\-]/', '_', $organizationName); // Replace non-alphanumeric characters with underscores

    // Extract file extension
    $fileExtension = pathinfo($fileInput['name'], PATHINFO_EXTENSION);

    // Define new file name with organization name
    $fileName = $cleanOrganizationName . "." . $fileExtension;

    // Set file path
    $filePath = $uploadDir . $fileName;

    // Upload the file to the server
    if (move_uploaded_file($fileInput['tmp_name'], $filePath)) {
        // File uploaded successfully, now update the database
        $fileUrl = $filePath;

        $sqlUpdate = "UPDATE `document` SET `file_name`='$fileName', `file_path`='$fileUrl' WHERE `organization_id`='$organizationId'";
        if (mysqli_query($conn, $sqlUpdate)) {
            // Show SweetAlert for success
            echo "<script>
                    Swal.fire({
                        title: 'Upload Success',
                        text: 'อัปไฟล์สำเร็จ',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    });
                </script>";
        } else {
            // Show SweetAlert for database error
            echo "<script>
                    Swal.fire({
                        title: 'Database Error',
                        text: 'อัปไฟล์ไม่สำเร็จ',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                </script>";
        }
    } else {
        // Show SweetAlert for file upload error
        echo "<script>
                Swal.fire({
                    title: 'Upload Failed',
                    text: 'There was an error uploading the file.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            </script>";
    }
}
?>