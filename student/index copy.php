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

        .modal {
            display: none;
            position: fixed;
            z-index: 50;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            border-radius: 10px;
            text-align: center;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>

<body class="bg-gradient-to-br from-green-700 to-teal-500 text-white font-sans min-h-screen">
    <!-- header -->
    <?php include "header.php"?>


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
                                <input type="file" id="fileInput0" class="hidden" onchange="handleFileUpload(event, 0)">
                                <span id="fileName0" class="flex-grow"></span>
                                <label for="fileInput0" class="bg-blue-500 text-white px-3 py-1 rounded-lg cursor-pointer">แนบไฟล์</label>
                                <button class="bg-red-500 text-white px-3 py-1 rounded-lg" onclick="cancelUpload(0)">ยกเลิก</button>
                            </td>
                        </tr>
                        <tr>
                            <td class="border px-4 py-2">ใบรับรองผลการเรียน</td>
                            <td class="border px-4 py-2 file-upload-cell">
                                <input type="file" id="fileInput1" class="hidden" onchange="handleFileUpload(event, 1)">
                                <span id="fileName1" class="flex-grow"></span>
                                <label for="fileInput1" class="bg-blue-500 text-white px-3 py-1 rounded-lg cursor-pointer">แนบไฟล์</label>
                                <button class="bg-red-500 text-white px-3 py-1 rounded-lg" onclick="cancelUpload(1)">ยกเลิก</button>
                            </td>
                        </tr>
                        <tr>
                            <td class="border px-4 py-2">ข้อมูลประวัติส่วนตัว (Curriculum Vitae : CV)</td>
                            <td class="border px-4 py-2 file-upload-cell">
                                <input type="file" id="fileInput2" class="hidden" onchange="handleFileUpload(event, 2)">
                                <span id="fileName2" class="flex-grow"></span>
                                <label for="fileInput2" class="bg-blue-500 text-white px-3 py-1 rounded-lg cursor-pointer">แนบไฟล์</label>
                                <button class="bg-red-500 text-white px-3 py-1 rounded-lg" onclick="cancelUpload(2)">ยกเลิก</button>
                            </td>
                        </tr>
                        <tr>
                            <td class="border px-4 py-2">ใบอนุญาตไปปฏิบัติงานสหกิจศึกษา</td>
                            <td class="border px-4 py-2 file-upload-cell">
                                <input type="file" id="fileInput3" class="hidden" onchange="handleFileUpload(event, 3)">
                                <span id="fileName3" class="flex-grow"></span>
                                <label for="fileInput3" class="bg-blue-500 text-white px-3 py-1 rounded-lg cursor-pointer">แนบไฟล์</label>
                                <button class="bg-red-500 text-white px-3 py-1 rounded-lg" onclick="cancelUpload(3)">ยกเลิก</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="flex justify-center mt-4">
                    <button class="bg-green-600 text-white px-6 py-2 rounded-lg" onclick="submitFiles()">บันทึก</button>
                </div>
            </div>
        </section>
    </main>

    <!-- Modal -->
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <p id="modalMessage"></p>
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

            const modal = document.getElementById("myModal");
            const modalMessage = document.getElementById("modalMessage");

            if (allFilesAttached) {
                modalMessage.innerHTML = '<i class="fa-solid fa-check-circle text-green-500"></i> บันทึกข้อมูลสำเร็จ';
                modalMessage.className = "text-green-500";
                updateDocumentStatus();
                setTimeout(() => {
                    window.location.href = "pendingpage.php";
                }, 2000); // Wait for 2 seconds before redirecting
            } else {
                modalMessage.innerHTML = '<i class="fa-solid fa-exclamation-triangle text-red-500"></i> กรุณาแนบไฟล์ให้ครบถ้วน';
                modalMessage.className = "text-red-500";
            }

            modal.style.display = "flex";
        }

        function updateDocumentStatus() {
            const documentStatus = document.getElementById("documentStatus");
            documentStatus.innerHTML = `
                <tr>
                    <td class="border px-4 py-2">ยื่นเอกสารเข้าร่วมโครงการสหกิจศึกษา</td>
                    <td class="border px-4 py-2 text-green-500 flex items-center">
                        <i class="fa-solid fa-check-circle mr-2"></i>ยื่นเอกสารสำเร็จ
                    </td>
                    <td class="border px-4 py-2">${getCurrentTimestamp()}</td>
                </tr>
                <tr>
                    <td class="border px-4 py-2">อาจารย์เห็นชอบการเข้าร่วมโครงการสหกิจศึกษา</td>
                    <td class="border px-4 py-2 text-yellow-500 flex items-center">
                        <i class="fa-solid fa-exclamation-circle mr-2"></i>รออาจารย์เห็นชอบ
                    </td>
                    <td class="border px-4 py-2">--/--/----</td>
                </tr>
                <tr>
                    <td class="border px-4 py-2">ส่งเอกสารไปยังสถานประกอบการ</td>
                    <td class="border px-4 py-2 text-red-500 flex items-center">
                        <i class="fa-solid fa-times-circle mr-2"></i>ยังไม่ดำเนินการ
                    </td>
                    <td class="border px-4 py-2">--/--/----</td>
                </tr>`;
        }

        function getCurrentTimestamp() {
            const now = new Date();
            return now.toLocaleDateString('th-TH', {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
        }

        function closeModal() {
            const modal = document.getElementById("myModal");
            modal.style.display = "none";
        }
    </script>
</body>

</html>