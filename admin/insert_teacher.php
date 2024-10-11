<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่มข้อมูลอาจารย์ที่ปรึกษา</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="bg-gradient-to-br from-green-700 to-teal-500 text-white font-sans min-h-screen">
    <!-- Navbar -->
    <nav class="bg-green-800 p-4 flex justify-between items-center border-b border-gray-600">
        <div class="flex items-center space-x-4">
            <img src="assets/img/Sci.png" alt="TSU Logo" class="w-32 h-auto">
            <a href="index.php" class="text-yellow-300 hover:text-gray-300">หน้าหลัก</a>
            <a href="#" class="text-yellow-300 hover:text-gray-300">ข้อมูลผู้ใช้และระบบสหกิจศึกษา</a>
            <a href="manage_organization.php" class="text-yellow-300 hover:text-gray-300">จัดการข้อมูล</a>
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
            
            <a href="insert_student_csv.php" class="bg-yellow-500 text-white px-6 py-3 rounded-lg flex items-center space-x-2">
                <i class="fas fa-file-import"></i>
                <span>นำเข้าข้อมูล</span>
            </a>
            
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6 text-black border border-gray-600">
            <h2 class="text-xl font-semibold mb-4">เพิ่มข้อมูลผู้ใช้: อาจารย์ที่ปรึกษาโครงการสหกิจศึกษา</h2>
            <form method="POST" enctype="multipart/form-data" class="space-y-4">
                <div class="flex items-start space-x-4">
                    <div>
                        <label for="upload_picture" class="block text-sm font-medium mb-2">อัปโหลดรูป <span>
                                <font color="red">*อัพโหลดได้เฉพาะไฟล์สกุล.jpg</font>
                            </span>
                        </label>
                        <input type="file" name="uploaded_file" class="w-[16rem] p-2 border border-slate-400 rounded-lg" accept="image/jpeg" onchange="loadFile(event)" required>
                    </div>

                    <div class="mt-5 absolute left-[25rem]">
                        <!-- Display student image dynamically based on Student_ID -->
                        <img id="previewImage" src="" width="100" height="auto" class="rounded-lg shadow-2xl">
                    </div>
                </div>


                <div class="flex items-start space-x-4">
                    <div>
                        <label for="hs-select-label" class="block text-sm font-medium mb-2">คำนำหน้าชื่อ</label>
                        <select id="Teacher_Prefix" name="Teacher_Prefix" class="py-3 px-4 block w-[16rem] border border-slate-400 rounded-lg text-sm" required>
                            <option value=""> <- กรุณาเลือกคำนำหน้าชื่อ -> </option>
                            <option value="นาย">นาย</option>
                            <option value="นางสาว">นางสาว</option>
                            <option value="นาง">นาง</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="student_surname" class="block text-sm">ชื่อ</label>
                        <input type="text" name="Teacher_Surname" id="Teacher_Surname" class="w-full p-2 border border-slate-400 rounded-lg" required>
                    </div>
                    <div>
                        <label for="student_lastname" class="block text-sm">นามสกุล</label>
                        <input type="text" name="Teacher_Lastname" id="Teacher_Lastname" class="w-full p-2 border border-slate-400 rounded-lg" required>
                    </div>
                    <div>
                        <label for="course" class="block text-sm">หลักสูตร</label>
                        <select class="form-select w-full p-2 border border-slate-400 rounded-lg" name="Teacher_Course" required>
                            <option value=""> <-- โปรดเลือกหลักสูตร --> </option>
                            <?php
                            // Fetching majors from the database
                            include_once "../connection.php";
                            mysqli_set_charset($conn, "utf8");
                            $sql = "SELECT * FROM course";
                            $query = mysqli_query($conn, $sql);
                            while ($result = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
                            ?>
                                <option value="<?php echo $result["course_id"]; ?>"><?php echo $result["course_name"] ?>
                                </option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div>
                        <label for="course" class="block text-sm">ตำแหน่ง</label>
                        <select class="form-select w-full p-2 border border-slate-400 rounded-lg" name="Teacher_Rank" required>
                            <option value=""> <-- โปรดเลือกตำแหน่ง --> </option>
                            <?php
                            // Fetching majors from the database
                            include_once "../connection.php";
                            mysqli_set_charset($conn, "utf8");
                            $sql = "SELECT * FROM rank";
                            $query = mysqli_query($conn, $sql);
                            while ($result = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
                            ?>
                                <option value="<?php echo $result["rank_id"]; ?>"><?php echo $result["rank_name"] ?>
                                </option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div>
                        <label for="student_tel" class="block text-sm">เบอร์โทร</label>
                        <input type="text" name="Teacher_Tel" id="Teacher_Tel" class="w-full p-2 border border-slate-400 rounded-lg" required>
                    </div>
                    <br>
                    <div>
                        <label for="gpax" class="block text-sm">Email</label>
                        <input type="email" name="Teacher_Email" id="email" placeholder="Enter email" class="w-full p-2 border border-slate-400 rounded-lg" required>
                    </div>
                    <!-- Form Group -->
                    <div class="max-w-full">
                        <label class="block text-sm">Password</label>
                        <div class="relative">
                            <input id="password" type="password" name="Teacher_Password" class="w-full p-2 border border-slate-400 rounded-lg" placeholder="Enter password" required>
                            <button id="togglePassword" type="button" class="absolute top-0 end-0 p-3.5 rounded-e-md">
                                <svg class="flex-shrink-0 size-3.5 text-gray-400 dark:text-neutral-600" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path class="hs-password-active:hidden" d="M9.88 9.88a3 3 0 1 0 4.24 4.24"></path>
                                    <path class="hs-password-active:hidden" d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"></path>
                                    <path class="hs-password-active:hidden" d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"></path>
                                    <line class="hs-password-active:hidden" x1="2" x2="22" y1="2" y2="22"></line>
                                    <path class="hidden hs-password-active:block" d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>
                                    <circle class="hidden hs-password-active:block" cx="12" cy="12" r="3"></circle>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- End Form Group -->
                </div>
                <div class="text-center pt-5">
                    <button type="submit" name="insert_teacher" class="bg-green-600 text-white px-4 py-2 rounded-lg">บันทึก</button>
                </div>
            </form>
            <script>
                var loadFile = function(event) {
                    var preivew = document.getElementById('previewImage');
                    preivew.src = URL.createObjectURL(event.target.files[0]);
                    preivew.onload = function() {
                        URL.revokeObjectURL(preivew.src) // free memory
                    }
                };

                var togglePassword = document.getElementById('togglePassword');
                var passwordInput = document.getElementById('password');

                togglePassword.addEventListener('click', function() {
                    var type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);

                    // Change icon based on type
                    var icon = togglePassword.querySelector('svg');
                    if (type === 'password') {
                        icon.classList.remove('hidden');
                        icon.classList.add('hs-password-active:hidden');
                    } else {
                        icon.classList.remove('hs-password-active:hidden');
                        icon.classList.add('hidden');
                    }
                });
            </script>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.20/dist/sweetalert2.all.min.js"></script>

</body>

</html>

<!-- auto-check-login -->
<?php include("auto.php"); ?>

<?php
include "../connection.php";

// Function to get the next teacher_id
function getNextTeacherId($conn)
{
    $query = "SELECT MAX(CAST(SUBSTRING(teacher_id, 8) AS UNSIGNED)) AS max_id FROM teacher";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $max_id = $row['max_id'];
    $next_id = 'Teacher' . sprintf('%05d', $max_id + 1);
    return $next_id;
}

if (!empty($_FILES['uploaded_file'])) {
    //ดึงตัวแปรชื่อมาตั้งชื่อไฟล์//
    $tch_surname = $_POST['Teacher_Surname'];

    $name =  $_FILES['uploaded_file']['name'];
    $tmp_name =  $_FILES['uploaded_file']['tmp_name'];
    $locate_img = "../assets/img/teacher/";

    if (strlen($name)) {
        //ลบชื่อไฟล์ ให้เหลือแค่สกุลไฟล์//
        list($txt, $ext) = explode(".", $name);
        //ตั้งชื่อไฟล์ใหม่ ด้วยชื่อที่ดึงมาจากตัวแปร//
        $new_file_name = $tch_surname . "." . $ext;
        //Save ไฟล์ลงแฟ้มข้อมูล//
        move_uploaded_file($tmp_name, $locate_img . $new_file_name);
    }
}

if (isset($_POST['insert_teacher'])) {
    $tch_id = getNextTeacherId($conn);

    $tch_prefix = $_POST['Teacher_Prefix'];
    $tch_surname = $_POST['Teacher_Surname'];
    $tch_lastname = $_POST['Teacher_Lastname'];
    $tch_course = $_POST['Teacher_Course'];
    $tch_rank = $_POST['Teacher_Rank'];
    $tch_tel = $_POST['Teacher_Tel'];
    $tch_email = $_POST['Teacher_Email'];
    $tch_password = $_POST['Teacher_Password'];

    $tch_type = 'teacher';

    // Back-end validation for student ID
    if ($tch_id == "" || $tch_prefix == "" || $tch_surname == "" || $tch_lastname == "" || $tch_course == "" || $tch_rank == "" || $tch_tel == "" || $tch_email == "" || $tch_password == "") {
?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'กรุณากรอกข้อมูลให้ครบทุกช่องค่ะ',
            });
        </script>
        <?php
    } else {
        // Proceed with database insertion
        $Sql = "INSERT INTO `teacher`(`teacher_id`, `teacher_prefix`, `teacher_surname`, `teacher_lastname`, `course`, `rank`, `teacher_tel`) 
                VALUES ('$tch_id','$tch_prefix','$tch_surname','$tch_lastname','$tch_course','$tch_rank','$tch_tel')";
        $sql_login = "INSERT INTO `login`(`email`,`password`,`type`,`user_id`) VALUES ('$tch_email','$tch_password','$tch_type','$tch_id')";

        $res = mysqli_query($conn, $Sql);

        var_dump($std_id);

        $res_login = mysqli_query($conn, $sql_login);
        if ($res || $res_login) {
        ?>
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'เพิ่มอาจารย์ข้อมูลสำเร็จ!!',
                    text: 'โปรดรอสักครู่',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    document.location.href = 'relateddatauser.php';
                });
            </script>";
        <?php
        } else {
        ?>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'ไม่สามารถเพิ่มข้อมูลได้ โปรดลองใหม่อีกครั้งนึง',
                });
            </script>
<?php
            // Logging the error for debugging
            error_log("SQL Error: " . mysqli_error($conn));
        }
        mysqli_close($conn);
    }
}
?>