<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขข้อมูลอาจารย์</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="bg-gradient-to-br from-green-700 to-teal-500 text-white font-sans min-h-screen">
    <!-- Navbar -->
    <nav class="bg-green-800 p-4 flex justify-between items-center border-b border-gray-600">
        <div class="flex items-center space-x-4">
            <img src="assets/img/Sci.png" alt="TSU Logo" class="w-32 h-auto">
            <a href="#" class="text-yellow-300 hover:text-gray-300">หน้าหลัก</a>
            <a href="#" class="text-yellow-300 hover:text-gray-300">ข้อมูลผู้ใช้และระบบสหกิจศึกษา</a>
            <a href="#" class="text-yellow-300 hover:text-gray-300">จัดการข้อมูล</a>
            <a href="#" class="text-yellow-300 hover:text-gray-300">ผลการเข้าร่วมสหกิจศึกษา</a>
        </div>
        <div class="flex items-center space-x-2">
            <span>admin</span>
            <i class="fa-solid fa-user"></i>
        </div>
    </nav>

    <div class="p-4">
        <div class="text-center mb-4 border-b border-gray-600">
            <h1 class="text-2xl font-bold mb-2">จัดการข้อมูลสหกิจศึกษา</h1>
            <p class="mb-2">คณะวิทยาศาสตร์และนวัตกรรมดิจิทัล</p>
        </div>

        <div class="flex justify-center space-x-4 mb-4">
            <button class="bg-green-600 text-white px-6 py-3 rounded-lg flex items-center space-x-2">
                <i class="fas fa-user-plus"></i>
                <span>เพิ่มข้อมูลผู้ใช้</span>
            </button>
            <button class="bg-yellow-500 text-white px-6 py-3 rounded-lg flex items-center space-x-2">
                <i class="fas fa-file-import"></i>
                <span>นำเข้าข้อมูล</span>
            </button>
            <button class="bg-purple-500 text-white px-6 py-3 rounded-lg flex items-center space-x-2">
                <i class="fas fa-cogs"></i>
                <span>จัดการข้อมูลที่เกี่ยวข้อง</span>
            </button>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6 text-black border border-gray-600">
            <h2 class="text-xl font-semibold mb-4">เพิ่มข้อมูลผู้ใช้ : อาจารย์ที่ปรึกษาโครงการสหกิจศึกษา</h2>
            <form class="space-y-4">
                <div class="flex items-center space-x-4">
                    <label class="flex items-center space-x-2">
                        <input type="radio" name="gender" value="male" class="form-radio text-green-500">
                        <span>ชาย</span>
                    </label>
                    <label class="flex items-center space-x-2">
                        <input type="radio" name="gender" value="female" class="form-radio text-green-500">
                        <span>นาย</span>    
                    </label>
                    <label class="flex items-center space-x-2">
                        <input type="radio" name="gender" value="other" class="form-radio text-green-500">
                        <span>นางสาว</span>
                    </label>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class=" p-2 rounded">
                        <label class="block text-sm">ชื่อ</label>
                        <input type="text" class="w-full p-2 border border-gray-600 rounded" value="เนตรนารี">
                    </div>
                    <div class=" p-2 rounded">
                        <label class="block text-sm">นามสกุล</label>
                        <input type="text" class="w-full p-2 border border-gray-600 rounded" value="ศรีน้อย">
                    </div>
                    <div class=" p-2 rounded">
                        <label class="block text-sm">หลักสูตร</label>
                        <input type="text" class="w-full p-2 border border-gray-600 rounded" value="วท.บ. เทคโนโลยีสารสนเทศ">
                    </div>
                    <div class=" p-2 rounded">
                        <label class="block text-sm">ตำแหน่ง</label>
                        <input type="text" class="w-full p-2 border border-gray-600 rounded" value="ผู้ช่วยศาสตราจารย์">
                    </div>
                    <div class=" p-2 rounded">
                        <label class="block text-sm">เบอร์โทร</label>
                        <input type="text" class="w-full p-2 border border-gray-600 rounded" value="0653619084">
                    </div>
                    <div class=" p-2 rounded">
                        <label class="block text-sm">E-mail</label>
                        <input type="email" class="w-full p-2 border border-gray-600 rounded" value="642021124@tsu.ac.th">
                    </div>
                    <div class=" p-2 rounded">
                        <label class="block text-sm">รหัสผ่าน</label>
                        <input type="password" class="w-full p-2 border border-gray-600 rounded" value="*********">
                    </div>
                    <div class=" p-2 rounded">
                        <label class="block text-sm">โฟล์เดอร์รูปภาพ</label>
                        <input type="text" class="w-full p-2 border border-gray-600 rounded" value="124.jpg">
                    </div>
                    <div class=" p-2 rounded flex items-center space-x-2">
                        <label class="block text-sm">แทรกรูป</label>
                        <input type="file" class="w-full p-2 border border-gray-600 rounded">
                    </div>
                </div>
                
                <div class="text-center mt-4">
                    <button class="bg-green-600 text-white px-4 py-2 rounded-lg ">บันทึก</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>

<!-- auto-check-login -->
<?php include("auto.php"); ?>
