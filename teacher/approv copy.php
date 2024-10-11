<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ตรวจสอบและอนุมัติเอกสารการเข้าร่วมโครงการสหกิจศึกษา</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="bg-gradient-to-br from-green-700 to-teal-500 text-white font-sans min-h-screen">
    <!-- Navbar -->
    <header class="bg-green-800 p-4 flex justify-between items-center">
        <div class="flex items-center space-x-4">
            <img src="assets/img/Sci.png" alt="TSU Logo" class="w-32 h-auto">
            <a href="#" class="text-yellow-300 hover:text-gray-300">หน้าหลัก</a>
            <a href="#" class="text-yellow-300 hover:text-gray-300">ข้อมูลอาจารย์ที่ปรึกษาโครงการ</a>
            <a href="#" class="text-yellow-300 hover:text-gray-300">ตรวจสอบและอนุมัติเอกสาร</a>
            <a href="#" class="text-yellow-300 hover:text-gray-300">ผลการเข้าร่วมสหกิจศึกษา</a>
        </div>
        <div class="flex items-center space-x-2">
            <span>Kanida</span>
            <i class="fa-solid fa-user"></i>
        </div>
    </header>

    <main class="p-6">
        <section class="bg-white rounded-lg shadow-lg p-8 text-black max-w-6xl mx-auto">
            <h1 class="text-2xl font-bold mb-6 text-center text-green-700">ตรวจสอบและอนุมัติเอกสารการเข้าร่วมโครงการสหกิจศึกษา</h1>
            <h2 class="text-xl font-semibold mb-8 text-center">คณะวิทยาศาสตร์และนวัตกรรมดิจิทัล</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Student Information -->
                <div class="bg-green-100 p-6 rounded-lg">
                    <h3 class="text-lg font-semibold mb-6">ข้อมูลนิสิต</h3>
                    <div class="flex items-center mb-6">
                        <img src="assets/img/student.jpg" alt="Student Photo" class="w-24 h-24 rounded-full mr-6">
                        <div>
                            <p><strong>ชื่อ - สกุล:</strong> นางสาวเนตรวารี ศรีน้อย</p>
                            <p><strong>รหัสนิสิต:</strong> 642021124</p>
                            <p><strong>หลักสูตร:</strong> วท.บ. เทคโนโลยีสารสนเทศ</p>
                            <p><strong>ชั้นปีที่:</strong> 4</p>
                            <p><strong>เกรดเฉลี่ย:</strong> 3.28</p>
                            <p><strong>วันเดือนปีเกิด:</strong> 19/06/2545</p>
                            <p><strong>Email:</strong> 642021124@tsu.ac.th</p>
                        </div>
                    </div>
                    <h3 class="text-lg font-semibold mb-4">ข้อมูลสถานประกอบการของนิสิต</h3>
                    <p><strong>ชื่อสถานประกอบการ:</strong> บริษัท A</p>
                    <p><strong>ประเภทสถานประกอบการ:</strong> บริษัทพัฒนาซอฟต์แวร์</p>
                    <p><strong>ตำแหน่งที่ต้องการฝึก:</strong> IT Support</p>
                    <p><strong>Email:</strong> AB@gmail.com</p>
                    <p><strong>เบอร์โทร:</strong> 089-123-4567</p>
                    <p><strong>Website บริษัท:</strong> www.AB.com</p>
                    <p><strong>ที่อยู่ของสถานประกอบการ:</strong> เลขที่ 199 หมู่ที่ - ชั้น/อาคาร ตึก B ซอย AB ถนน AB ตำบล AB อำเภอ เมืองชลบุรี จังหวัด ชลบุรี 16555</p>
                </div>

                <!-- Document Information -->
                <div class="bg-green-100 p-6 rounded-lg">
                    <h3 class="text-lg font-semibold mb-6">เอกสารการเข้าร่วมโครงการสหกิจศึกษา</h3>
                    <ul class="mb-6">
                        <li class="flex justify-between items-center mb-4">
                            <span>ใบสมัครเข้าร่วมโครงการสหกิจศึกษา</span>
                            <a href="documents/ใบสมัคร.pdf" class="text-blue-500" target="_blank">642021124_ใบสมัคร.pdf</a>
                        </li>
                        <li class="flex justify-between items-center mb-4">
                            <span>ใบรับรองผลการเรียน</span>
                            <a href="documents/ใบรับรอง.pdf" class="text-blue-500" target="_blank">642021124_ใบรับรอง.pdf</a>
                        </li>
                        <li class="flex justify-between items-center mb-4">
                            <span>ข้อมูลประวัติส่วนตัว (Curriculum Vitae : CV)</span>
                            <a href="documents/CV.pdf" class="text-blue-500" target="_blank">642021124_CV.pdf</a>
                        </li>
                        <li class="flex justify-between items-center">
                            <span>ใบอนุญาตไปปฏิบัติงานสหกิจศึกษา</span>
                            <a href="documents/ใบอนุญาต.pdf" class="text-blue-500" target="_blank">642021124_ใบอนุญาต.pdf</a>
                        </li>
                    </ul>

                    <div class="flex space-x-6">
                        <button class="bg-green-600 text-white px-6 py-2 rounded-lg">เห็นชอบ</button>
                        <button class="bg-red-600 text-white px-6 py-2 rounded-lg">ไม่เห็นชอบ</button>
                    </div>

                    <div class="mt-6">
                        <label for="reason" class="block mb-4">ระบุเหตุผลที่ไม่เห็นชอบการเข้าร่วมโครงการสหกิจศึกษา</label>
                        <textarea id="reason" class="w-full p-4 border rounded-lg h-32"></textarea>
                    </div>
                </div>
            </div>
        </section>
    </main>
</body>

</html>
