<?php
session_start(); // ต้องเริ่มต้น session ที่จุดเริ่มต้น
$User_ID = $_SESSION['UserID'];
$UserType = $_SESSION['u_type'];

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

include("../connection.php");

// ตรวจสอบการเชื่อมต่อฐานข้อมูล
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_data = null;
$prefix = "";
$avatar_image = "";
$user_name = "";
$index = "";

if ($UserType == "student") {
    $sql = "SELECT * FROM student WHERE student_id = ?";
    $avatar_image = "../assets/img/student/";
    $prefix = "student_prefix";
    $name_field_1 = "student_surname";
    $name_field_2 = "student_lastname";
    $index = "../student/index.php";
} else if ($UserType == "teacher") {
    $sql = "SELECT * FROM teacher WHERE teacher_id = ?";
    $avatar_image = "../assets/img/teacher/";
    $prefix = "teacher_prefix";
    $name_field_1 = "teacher_surname";
    $name_field_2 = "teacher_lastname";
    $index = "../teacher/index.php";
} else if ($UserType == "officer") {
    $sql = "SELECT * FROM officer WHERE officer_id = ?";
    $avatar_image = "../assets/img/officer/";
    $prefix = "officer_prefix";
    $name_field_1 = "officer_surname";
    $name_field_2 = "officer_lastname";
    $index = "../officer/index.php";
} else if ($UserType == "admin") {
    $sql = "SELECT * FROM admin WHERE admin_id = ?";
    $avatar_image = "../assets/img/admin/";
    $prefix = "admin_prefix";
    $name_field_1 = "admin_surname";
    $name_field_2 = "admin_lastname";
    $index = "../admin/index.php";
}

if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("s", $_SESSION['UserID']);
    $stmt->execute();
    $result = $stmt->get_result();
    $user_data = $result->fetch_assoc();
    $stmt->close();

    $user_name = htmlspecialchars($user_data[$prefix] . "" . $user_data[$name_field_1] . " " . $user_data[$name_field_2]);

    // ตรวจสอบว่าเป็น student และใช้ student_id แทนสำหรับรูปภาพ
    if ($UserType == "student") {
        $avatar_image .= htmlspecialchars($user_data['student_id']) . ".jpg";
    } else {
        $avatar_image .= htmlspecialchars($user_data[$name_field_1]) . ".jpg";
    }
} else {
    echo "Error preparing statement: " . $conn->error;
    exit();
}


?>

<header class="bg-green-800 p-4 flex justify-between items-center">
    <div class="flex items-center space-x-4">
        <img src="../assets/img/Sci.png" alt="TSU Logo" class="w-32 h-auto">
        <a href="<?= $index ?>" class="text-yellow-300 hover:text-gray-300">หน้าหลัก</a>
    </div>
    <div class="flex items-center space-x-2 relative">
        <span class="text-white"><?= $user_name ?></span>
        <img id="avatarButton" type="button" class="w-10 h-10 rounded-full object-cover cursor-pointer" src="<?= $avatar_image ?>" alt="User dropdown">

        <!-- Dropdown menu -->
        <div id="userDropdown" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 absolute right-0 mt-[150px]">
            <div class="px-4 py-3 text-sm text-gray-900">
                <div><?= $user_name ?></div>
            </div>
            <div class="py-1">
                <a href="../logout.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                    <p class="mr-1 text-red-600">ออกจากระบบ</p>
                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 16 16" class="text-red-600">
                        <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5.25 2.25h-3.5v12h3.5m5.5-9.5l3.5 3.5l-3.5 3.5m-5-3.5h8.5" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
</header>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Script to toggle the dropdown and close it on outside click -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var avatarButton = document.getElementById('avatarButton');
        var userDropdown = document.getElementById('userDropdown');

        // Toggle dropdown visibility
        avatarButton.addEventListener('click', function(event) {
            userDropdown.classList.toggle('hidden');
            event.stopPropagation(); // Prevent click event from closing immediately
        });

        // Close dropdown if clicking outside of it
        document.addEventListener('click', function(event) {
            if (!userDropdown.contains(event.target) && !avatarButton.contains(event.target)) {
                userDropdown.classList.add('hidden');
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        var logoutLink = document.querySelector('a[href="../logout.php"]');

        logoutLink.addEventListener('click', function(event) {
            event.preventDefault(); // หยุดการทำงานของลิงก์

            Swal.fire({
                title: 'คุณแน่ใจหรือไม่?',
                text: "คุณต้องการออกจากระบบใช่หรือไม่?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: "#eb4034",
                cancelButtonColor: "#525252",
                confirmButtonText: 'ใช่, ออกจากระบบ',
                cancelButtonText: 'ยกเลิก',
                reverseButtons: false

            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: "ออกจากระบบสำเร็จ",
                        text: "ระบบกำลังนำคุณไปยังหน้าแรก โปรดรอสักครู่",
                        icon: "success",
                        showConfirmButton: false,
                        timer: 3000,
                    })
                    window.location.href = '../logout.php'; // หากผู้ใช้ยืนยันการออกจากระบบ
                }
            });
        });
    });
</script>