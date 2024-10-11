<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ข้อมูลสถานประกอบการ</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="bg-gradient-to-br from-green-700 to-teal-500 text-white font-sans min-h-screen">
    <!-- Navbar -->
    <header class="bg-green-800 p-4 flex justify-between items-center">
        <div class="flex items-center space-x-4">
            <img src="assets/img/Sci.png" alt="TSU Logo" class="w-32 h-auto">
            <a href="#" class="text-yellow-300 hover:text-gray-300">หน้าหลัก</a>
            <a href="#" class="text-yellow-300 hover:text-gray-300">กรอกข้อมูลการเข้าร่วมโครงการสหกิจศึกษา</a>
            <a href="#" class="text-yellow-300 hover:text-gray-300">แบบฟอร์มเอกสาร</a>
            <a href="#" class="text-yellow-300 hover:text-gray-300">รีวิวสถานประกอบการ</a>
        </div>
        <div class="flex items-center space-x-2">
            <span>เนตรวารี</span>
            <i class="fa-solid fa-user"></i>
        </div>
    </header>

    <main class="p-4">
        <section class="bg-white rounded-lg shadow-lg p-6 text-black max-w-4xl mx-auto">
            <h1 class="text-2xl font-bold mb-6 text-center text-green-700">เพิ่มข้อมูลสถานประกอบการ</h1>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="form-group">
                    <label for="companyName" class="block mb-2">เพิ่มชื่อสถานประกอบการ</label>
                    <input type="text" id="companyName" class="w-full p-2 border border-slate-400 rounded">
                </div>
                <div class="form-group">
                    <label for="companyType" class="block mb-2">ประเภทบริษัท</label>
                    <select id="companyType" class="w-full p-2 border border-slate-400 rounded">
                        <option>เลือกประเภทบริษัท</option>
                        <option>ประเภท 1</option>
                        <option>ประเภท 2</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="village" class="block mb-2">หมู่ที่</label>
                    <input type="text" id="village" class="w-full p-2 border border-slate-400 rounded">
                </div>
                <div class="form-group">
                    <label for="alley" class="block mb-2">ซอย</label>
                    <input type="text" id="alley" class="w-full p-2 border border-slate-400 rounded">
                </div>
                <div class="form-group">
                    <label for="subdistrict" class="block mb-2">ตำบล</label>
                    <select id="subdistrict" class="w-full p-2 border border-slate-400 rounded">
                        <option>เลือกตำบล</option>
                        <option>ตำบล 1</option>
                        <option>ตำบล 2</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="province" class="block mb-2">จังหวัด</label>
                    <select id="province" class="w-full p-2 border border-slate-400 rounded">
                        <option>เลือกจังหวัด</option>
                        <option>จังหวัด 1</option>
                        <option>จังหวัด 2</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="phoneNumber" class="block mb-2">เบอร์โทรศัพท์</label>
                    <input type="text" id="phoneNumber" class="w-full p-2 border border-slate-400 rounded">
                </div>
                <div class="form-group">
                    <label for="website" class="block mb-2">Website บริษัท</label>
                    <input type="text" id="website" class="w-full p-2 border border-slate-400 rounded">
                </div>
                <div class="form-group">
                    <label for="position" class="block mb-2">กรุณาเลือกตำแหน่งที่ต้องการฝึกสหกิจ</label>
                    <select id="position" class="w-full p-2 border border-slate-400 rounded">
                        <option>เลือกตำแหน่ง</option>
                        <option>ตำแหน่ง 1</option>
                        <option>ตำแหน่ง 2</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="address" class="block mb-2">ที่อยู่เลขที่</label>
                    <input type="text" id="address" class="w-full p-2 border border-slate-400 rounded">
                </div>
                <div class="form-group">
                    <label for="building" class="block mb-2">ชั้น/อาคาร/ตึกที่</label>
                    <input type="text" id="building" class="w-full p-2 border border-slate-400 rounded">
                </div>
                <div class="form-group">
                    <label for="road" class="block mb-2">ถนน</label>
                    <select id="road" class="w-full p-2 border border-slate-400 rounded">
                        <option>เลือกถนน</option>
                        <option>ถนน 1</option>
                        <option>ถนน 2</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="district" class="block mb-2">อำเภอ</label>
                    <select id="district" class="w-full p-2 border border-slate-400 rounded">
                        <option>เลือกอำเภอ</option>
                        <option>อำเภอ 1</option>
                        <option>อำเภอ 2</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="postalCode" class="block mb-2">รหัสไปรษณีย์</label>
                    <select id="postalCode" class="w-full p-2 border border-slate-400 rounded">
                        <option>เลือกรหัสไปรษณีย์</option>
                        <option>รหัส 1</option>
                        <option>รหัส 2</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="email" class="block mb-2">e-mail</label>
                    <input type="email" id="email" class="w-full p-2 border border-slate-400 rounded">
                </div>
                <div class="form-group">
                    <label for="mapPosition" class="block mb-2">ตำแหน่ง MAP ที่อยู่บริษัท</label>
                    <input type="text" id="mapPosition" class="w-full p-2 border border-slate-400 rounded">
                </div>
            </div>

            <div class="text-center mt-6">
                <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg">บันทึกข้อมูล</button>
            </div>
        </section>
    </main>
</body>

</html>
