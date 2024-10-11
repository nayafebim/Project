<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>โครงการสหกิจศึกษา</title>
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
    <!-- Navbar -->
    <header class="bg-green-800 p-4 flex justify-between items-center">
        <div class="flex items-center space-x-4">
            <img src="../assets/img/Sci.png" alt="TSU Logo" class="w-32 h-auto">
            <a href="#" class="text-yellow-300 hover:text-gray-300">หน้าหลัก</a>
            <a href="#" class="text-yellow-300 hover:text-gray-300">กรอกข้อมูลการเข้าร่วมโครงการสหกิจศึกษา</a>
            <a href="#" class="text-yellow-300 hover:text-gray-300">แบบฟอร์มเอกสาร</a>
            <a href="#" class="text-yellow-300 hover:text-gray-300">รีวิวสถานประกอบการ</a>
            
        </div>
        <div class="flex items-center space-x-2">
            <span>นางสาวเนตรวารี ศรีน้อย</span>
            <i class="fa-solid fa-user"></i>
        </div>
    </header>

    <main class="p-4">
        <section class="bg-white rounded-lg shadow-lg p-6 text-black">
            <h1 class="text-2xl font-bold mb-2 text-center text-green-700">โครงการสหกิจศึกษา</h1>
            <h2 class="text-xl font-semibold mb-4 text-center">คณะวิทยาศาสตร์และนวัตกรรมดิจิทัล</h2>
            <h3 class="text-lg font-medium mb-6 text-center">มหาวิทยาลัยทักษิณ วิทยาเขตพัทลุง</h3>

            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-2">สถานะเอกสารในการเข้าร่วมโครงการสหกิจศึกษา</h3>
                <table class="w-full text-left bg-white rounded-lg shadow-lg">
                    <thead>
                        <tr>
                            <th class="border px-4 py-2">ขั้นตอนของเอกสาร</th>
                            <th class="border px-4 py-2">สถานะเอกสาร</th>
                            <th class="border px-4 py-2">เวลาการดำเนินการ</th>
                        </tr>
                    </thead>
                    <tbody id="documentStatus">
                        <tr>
                            <td class="border px-4 py-2">ยื่นเอกสารเข้าร่วมโครงการสหกิจศึกษา</td>
                            <td class="border px-4 py-2 text-green-500 flex items-center">
                                <i class="fa-solid fa-check-circle mr-2"></i>ยื่นเอกสารสำเร็จ
                            </td>
                            <td class="border px-4 py-2" id="timestamp0"></td>
                        </tr>
                        <tr>
                            <td class="border px-4 py-2">อาจารย์เห็นชอบการเข้าร่วมโครงการสหกิจศึกษา</td>
                            <td class="border px-4 py-2 text-yellow-500 flex items-center">
                                <i class="fa-solid fa-exclamation-circle mr-2"></i>รออาจารย์เห็นชอบ
                            </td>
                            <td class="border px-4 py-2" id="timestamp1"></td>
                        </tr>
                        <tr>
                            <td class="border px-4 py-2">ส่งเอกสารไปยังสถานประกอบการ</td>
                            <td class="border px-4 py-2 text-red-500 flex items-center">
                                <i class="fa-solid fa-times-circle mr-2"></i>ยังไม่ดำเนินการ
                            </td>
                            <td class="border px-4 py-2" id="timestamp2"></td>
                        </tr>
                    </tbody>
                </table>
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
            let timestamps = [];
            for (let i = 0; i < totalFiles; i++) {
                const fileName = document.getElementById(`fileName${i}`).textContent;
                if (fileName === "") {
                    allFilesAttached = false;
                    break;
                }
                timestamps.push(getCurrentTimestamp());
            }

            const modal = document.getElementById("myModal");
            const modalMessage = document.getElementById("modalMessage");

            if (allFilesAttached) {
                modalMessage.innerHTML = '<i class="fa-solid fa-check-circle text-green-500"></i> บันทึกข้อมูลสำเร็จ';
                modalMessage.className = "text-green-500";
                updateTimestamps(timestamps);
            } else {
                modalMessage.innerHTML = '<i class="fa-solid fa-exclamation-triangle text-red-500"></i> กรุณาแนบไฟล์ให้ครบถ้วน';
                modalMessage.className = "text-red-500";
            }

            modal.style.display = "flex";
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

        function updateTimestamps(timestamps) {
            for (let i = 0; i < timestamps.length; i++) {
                document.getElementById(`timestamp${i}`).textContent = timestamps[i];
            }
        }

        function closeModal() {
            const modal = document.getElementById("myModal");
            modal.style.display = "none";
        }
    </script>
</body>

</html>
