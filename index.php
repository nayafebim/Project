<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TSU Login Page</title>
    <!-- <script src="https://cdn.tailwindcss.com"></script> -->
    <link rel="stylesheet" href="style.css">
    <style>
        .gradient-custom {
            background: linear-gradient(to bottom right, #225348, #8DB575);
        }

        .gradient-login {
            background: linear-gradient(to bottom right, #D9D9D9, #9AC282);
        }
    </style>
</head>

<body class="gradient-custom font-sans text-white">
    <div class="flex min-h-screen flex-col lg:flex-row">
        <!-- Left side content -->
        <div class="w-full lg:w-2/3">
            <header class="flex justify-between items-center px-4 bg-transparent -mt-12">
                <img src="assets/img/Sci.png" alt="TSU Logo" class="w-96 h-auto">
                <nav class="flex">
                    <a href="index.php" class="text-yellow-300 px-2 lg:px-4 hover:text-gray-300">หน้าหลัก</a>
                    <a href="#" class="text-yellow-300 px-2 lg:px-4 hover:text-gray-300">ผลการเข้าร่วมสหกิจ</a>
                    <a href="#" class="text-yellow-300 px-2 lg:px-4 hover:text-gray-300">คู่มือการเข้าร่วมสหกิจ</a>
                    <a href="#" class="text-yellow-300 px-2 lg:px-4 hover:text-gray-300">ช่องทางการติดต่อ</a>
                </nav>
            </header>
            <div class="mt-8 lg:mt-20 lg:mx-24 animate-jump-in animate-duration-[800ms] animate-ease-out">
                <p class="text-3xl mb-4 mb-6 font-DB_Med">โครงการสหกิจศึกษา</p>
                <p class="text-xl lg:text-3xl mb-2 font-DB_Med">คณะวิทยาศาสตร์และนวัตกรรมดิจิทัล</p>
                <p class="text-lg lg:text-xl font-DB_Med">มหาวิทยาลัยทักษิณ วิทยาเขตพัทลุง</p>
            </div>
        </div>
        <!-- Right side login form -->
        <div class="w-full lg:w-1/3 flex items-end justify-center lg:justify-end">
            <div class="p-4 pt-[6rem] w-[42rem] h-[42rem] rounded-tl-[128px] shadow-2xl gradient-login text-gray-800 relative">
                <h2 class="text-2xl lg:text-3xl font-bold mb-4 text-center">เข้าสู่ระบบ</h2>
                <p class="text-sm lg:text-sm font-bold mb-4 text-center">โครงการสหกิจศึกษา คณะวิทยาศาสตร์และนวัตกรรมดิจิทัลและนวัตกรรมดิจิทัล</p>
                <form id="login-form" method="POST">
                    <?php if (isset($_SESSION['error'])) : ?>
                        <div class="error">
                            <h3>
                                <?php
                                echo $_SESSION['error'];
                                unset($_SESSION['error']);
                                ?>
                            </h3>
                        </div>
                    <?php endif ?>
                    <div class="mx-[6rem]">
                        <div class="mb-4 w-[22rem]">
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" id="email" name="email" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div class="mb-4 lg:mb-6 w-[22rem]">
                            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                            <input type="password" id="password" name="password" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <button type="submit" name="login" class="mx-12 w-[16rem] px-4 py-2 text-lg font-medium text-white bg-green-800 rounded-md hover:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">เข้าสู่ระบบ</button>
                    </div>

                </form>
                <div class="absolute bottom-0 left-0 right-0 flex justify-center pt-1">
                    <img src="assets/img/students.png" alt="Students" class="h-16 lg:h-[14rem] w-auto">
                </div>
            </div>
        </div>
    </div>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.20/dist/sweetalert2.all.min.js"></script>
</body>

</html>

<?php
include('connection.php');
session_start(); // Make sure to call session_start() at the beginning of your PHP script

$errors = array();

if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    if (empty($email) || empty($password)) {
        array_push($errors, "กรุณากรอก email และ password ให้ครบ!!");
    } else {
        $query = "SELECT * FROM login WHERE email = '$email' AND password = '$password'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_array($result);
            $_SESSION['email'] = $email;
            $_SESSION['user_name'] = $row['email'];
            $_SESSION['u_type'] = $row['type'];
            $_SESSION['UserID'] = $row['user_id']; // Make sure the semicolon is present here.

            // Logging the session values for debugging
            echo "<pre>";
            echo "Session Email: " . $_SESSION['email'] . "\n";
            echo "Session User Name: " . $_SESSION['user_name'] . "\n";
            echo "Session User Type: " . $_SESSION['u_type'] . "\n";
            echo "Session User ID: " . $_SESSION['UserID'] . "\n";
            echo "</pre>";

            echo '<pre>Session: ';
            var_dump($_SESSION);
            echo '</pre>';

            $redirect_url = '';
            if ($row['type'] == 'admin') {
                $redirect_url = 'admin/index.php';
            } elseif ($row['type'] == 'student') {
                $redirect_url = 'student/index.php';
            } elseif ($row['type'] == 'officer') {
                $redirect_url = 'officer/index.php';
            } elseif ($row['type'] == 'teacher') {
                $redirect_url = 'teacher/index.php';
            }
?>
            <script>
                Swal.fire({
                    icon: 'success',
                    title: "เข้าสู่ระบบสำเร็จ",
                    text: "โปรดรอสักครู่",
                    timer: 2000,
                    showConfirmButton: false
                }).then(function() {
                    window.location = '<?php echo $redirect_url; ?>';
                });
            </script>
        <?php
        } else {
            array_push($errors, "ไม่สามารถเข้าสู่ระบบได้ โปรดลองใหม่อีกครั้งนึง");
        }
    }

    if (count($errors) > 0) {
        ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '<?php echo implode("<br>", $errors); ?>',
            });
        </script>
<?php
    }
}
?>