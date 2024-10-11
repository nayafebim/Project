<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>หน้าหลัก</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>

<body class="bg-gradient-to-br from-green-700 to-teal-500 text-white font-sans min-h-screen">
    <!-- Navbar -->
    <nav class="bg-green-800 p-4 flex justify-between items-center">
        <div class="flex justify-between items-center px-4 bg-transparent ">
            <img src="assets/img/Sci.png" alt="TSU Logo" class="w-32 h-auto">
            <a href="index.php" class="text-yellow-300 px-2 lg:px-4 hover:text-gray-300">หน้าหลัก</a>
            <a href="#" class="text-yellow-300 px-2 lg:px-4 hover:text-gray-300">ข้อมูลผู้ใช้และระบบโครงการสหกิจศึกษา</a>
            <a href="manage_organization.php" class="text-yellow-300 px-2 lg:px-4 hover:text-gray-300">จัดการข้อมูล</a>
            <a href="#" class="text-yellow-300 px-2 lg:px-4 hover:text-gray-300">สถานะเอกสารของนิสิต</a>
        </div>
        <div class="flex items-center space-x-2">
            <span>Coke</span>
            <button class="text-white">☰</button>
        </div>
    </nav>
    <div class="flex-grow p-4 pt-2">

        <div class="text-center mb-4">
            <h1 class="text-2xl font-bold mb-2">จัดการข้อมูลผู้ใช้ในโครงการสหกิจศึกษา</h1>
            <p class="mb-2">คณะวิทยาศาสตร์และนวัตกรรมดิจิทัล</p>
        </div>
        <!-- Content -->
        <div class="p-4">
            <div class="bg-white rounded-lg shadow-lg p-4 text-black">
                <div class="mt-4 flex justify-end">
                    <button class="bg-green-600 text-white px-4 py-2 rounded-lg">นำเข้าข้อมูลผู้ใช้</button>
                </div>
                <table class="w-full border-collapse mt-5">
                    <thead>
                        <tr>
                            <th class="border-b p-2 text-left">ชื่อ - สกุล</th>
                            <th class="border-b p-2 text-left">ประเภทผู้ใช้</th>
                            <th class="border-b p-2 text-center">แก้ไข</th>
                            <th class="border-b p-2 text-center">ลบ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Example Data Row -->
                        <tr>
                            <td class="border-b p-2">นางสาวจอมเงิน นักทรัพย์</td>
                            <td class="border-b p-2">อาจารย์ที่ปรึกษา</td>
                            <td class="border-b p-2 text-center"><button class="text-orange-500"><i class="fas fa-pen"></i></button></td>
                            <td class="border-b p-2 text-center"><button class="text-red-600"><i class="fas fa-trash"></i></button></td>
                        </tr>
                    </tbody>
                </table>

            </div>
            <div class="flex justify-center space-x-4 p-4 w-full fixed bottom-0 left-0">
                <button class="bg-yellow-500 text-white px-2 py-2 rounded-lg  ">จัดการข้อมูลสถาบันประกอบการ</button>
                <a href="insert_student.php" class="bg-orange-500 text-white px-12 py-3 rounded-lg ">เพิ่มข้อมูลนิสิต</a>
                <a href="insert_teacher.php" class="bg-blue-500 text-white px-12 py-3 rounded-lg ">เพิ่มข้อมูลอาจารย์ที่ปรึกษา</a>
                <a href="#" class="bg-blue-500 text-white px-12 py-3 rounded-lg ">เพิ่มข้อมูลเจ้าหน้าที่</a>
                <a href="insert_admin.php" class="bg-blue-500 text-white px-12 py-3 rounded-lg ">เพิ่มข้อมูลผู้ดูแลระบบ</a>

            </div>
        </div>
</body>

</html>