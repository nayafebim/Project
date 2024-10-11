<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่มข้อมูลนิสิต</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="bg-gradient-to-br from-green-700 to-teal-500 text-white font-sans min-h-screen">
    <!-- Navbar -->
    <nav class="bg-green-800 p-4 flex justify-between items-center">
        <div class="flex items-center space-x-4">
            <img src="assets/img/Sci.png" alt="TSU Logo" class="w-32 h-auto">
            <a href="index.php" class="text-yellow-300 hover:text-gray-300">หน้าหลัก</a>
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
        <div class="text-center mb-4">
            <h1 class="text-2xl font-bold mb-2">จัดการข้อมูลสหกิจศึกษา</h1>
            <p class="mb-2">คณะวิทยาศาสตร์และนวัตกรรมดิจิทัล</p>
        </div>

        <div class="flex justify-center space-x-4 mb-4">
            <a href="insert_student_csv.php" class="bg-yellow-500 text-white px-6 py-3 rounded-lg flex items-center space-x-2">
                <i class="fas fa-file-import"></i>
                <span>นำเข้าข้อมูล</span>
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6 text-black">
            <h2 class="text-xl font-semibold mb-4">เพิ่มข้อมูลผู้ใช้: นิสิตคณะวิทยาศาสตร์และนวัตกรรมดิจิทัล</h2>
            <form method="POST" enctype="multipart/form-data" class="space-y-4" onsubmit="return validateForm()">

                <div class="flex items-start space-x-4">
                    <div>
                        <label for="student_id" class="block text-sm font-medium mb-2">รหัสนิสิต</label>
                        <input type="text" class="w-[16rem] p-2 border border-slate-400 rounded-lg" name="Student_ID" id="Student_ID" placeholder="xxxxxxxxx" aria-label="xxxxxxxxx" aria-describedby="studentID" required pattern="([0-9]{9}|67[0-9]{8})" title="กรุณากรอกรหัสนิสิตให้ถูกต้อง" onchange="updateStudentImage()" />
                    </div>

                    <div class="mt-5 absolute left-[25rem]">
                        <!-- Display student image dynamically based on Student_ID -->
                        <img id="studentImage" src="" width="120" height="auto" class="rounded-lg shadow-2xl">
                    </div>
                </div>


                <div class="flex items-start space-x-4">
                    <div>
                        <label for="hs-select-label" class="block text-sm font-medium mb-2">คำนำหน้าชื่อ</label>
                        <select id="Student_Prefix" name="Student_Prefix" class="py-3 px-4 block w-[16rem] border border-slate-400 rounded-lg text-sm">
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
                        <input type="text" name="Student_Surname" id="Student_Surname" class="w-full p-2 border border-slate-400 rounded-lg" required>
                    </div>
                    <div>
                        <label for="student_lastname" class="block text-sm">นามสกุล</label>
                        <input type="text" name="Student_Lastname" id="Student_Lastname" class="w-full p-2 border border-slate-400 rounded-lg" required>
                    </div>
                    <div>
                        <label for="course" class="block text-sm">หลักสูตร</label>
                        <select class="form-select w-full p-2 border border-slate-400 rounded-lg" name="Student_Course" required>
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
                        <label for="year" class="block text-sm">ชั้นปี</label>
                        <input type="text" name="Student_Year" id="Student_Year" class="w-full p-2 border border-slate-400 rounded-lg" required>
                    </div>
                    <div>
                        <label for="gpax" class="block text-sm">เกรดเฉลี่ย</label>
                        <input type="number" step="0.01" name="Student_Gpax" id="Student_Gpax" class="w-full p-2 border border-slate-400 rounded-lg" required>
                    </div>
                    <div>
                        <label for="student_tel" class="block text-sm">เบอร์โทร</label>
                        <input type="text" name="Student_Tel" id="Student_Tel" class="w-full p-2 border border-slate-400 rounded-lg" required>
                    </div>
                </div>

                <div class="text-center pt-5">
                    <button type="submit" name="insert_student" class="bg-green-600 text-white px-4 py-2 rounded-lg">บันทึก</button>
                </div>
            </form>
            <script>
                // Function to update student image based on Student_ID input
                function updateStudentImage() {
                    var studentID = document.getElementById('Student_ID').value;
                    var imgElement = document.getElementById('studentImage');
                    imgElement.src = '../assets/img/student/' + studentID + '.jpg';
                }

                // Function to validate form data before submission
                function validateForm() {
                    const studentID = document.querySelector('[name="Student_ID"]').value;
                    if (studentID.startsWith('67') && studentID.length !== 10) {
                        // Show error message using SweetAlert2 if student ID format is incorrect
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'รหัสนิสิตที่ขึ้นต้นด้วย "67" ต้องมีความยาว 10 ตัวอักษร',
                        });
                        return false;
                    }
                    return true; // Return true to allow form submission
                }
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

if (isset($_POST['insert_student'])) {
    $std_id = $_POST['Student_ID'];
    $std_prefix = $_POST['Student_Prefix'];
    $std_surname = $_POST['Student_Surname'];
    $std_lastname = $_POST['Student_Lastname'];
    $std_course = $_POST['Student_Course'];
    $std_year = $_POST['Student_Year'];
    $std_gpax = $_POST['Student_Gpax'];
    $std_tel = $_POST['Student_Tel'];

    $std_email = $std_id . '@tsu.ac.th';
    $std_password = $std_id;
    $std_type = 'student';

    // Back-end validation for student ID
    if (substr($std_id, 0, 2) === '67' && strlen($std_id) !== 10) {
?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'รหัสนิสิตที่ขึ้นต้นด้วย \"67\" ต้องมีความยาว 10 ตัวอักษร',
            });
        </script>
        <?php
    } else {
        // Proceed with database insertion
        $Sql = "INSERT INTO `student`(`student_id`, `student_prefix`, `student_surname`, `student_lastname`, `course`, `gpax`, `year`, `student_tel`) 
                VALUES ('$std_id','$std_prefix','$std_surname','$std_lastname','$std_course','$std_year','$std_gpax','$std_tel')";
        $sql_login = "INSERT INTO `login`(`email`,`password`,`type`,`user_id`) VALUES ('$std_email','$std_password','$std_type','$std_id')";

        $res = mysqli_query($conn, $Sql);

        var_dump($std_id);

        $res_login = mysqli_query($conn, $sql_login);
        if ($res || $res_login) {
        ?>
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'เพิ่มนิสิตข้อมูลสำเร็จ!!',
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