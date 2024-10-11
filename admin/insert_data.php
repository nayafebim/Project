<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TSU User Management</title>
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
            <a href="relateddata.php" class="text-yellow-300 hover:text-gray-300">จัดการข้อมูล</a>
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
        <h2 class="text-xl font-semibold mb-4">นำเข้าข้อมูล</h2>
        <form class="space-y-4">
            <div class="flex items-center space-x-4">
                <label class="block text-sm">นำเข้าข้อมูล</label>
                <input type="file" class="border border-gray-300 rounded p-2">
            </div>

            <div class="text-center mt-4">
                <button class="bg-green-600 text-white px-4 py-2 rounded-lg border border-gray-600">นำเข้าไฟล์</button>
            </div>
        </form>
    </div>
    </div>
</body>

</html>

<!-- auto-check-login -->
<?php include("auto.php"); ?>