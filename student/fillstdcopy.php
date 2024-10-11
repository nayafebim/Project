<?php
session_start(); // ต้องเริ่มต้น session ที่จุดเริ่มต้น

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

$User_ID = $_SESSION['UserID'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ข้อมูลนิสิตสำหรับเข้าร่วมโครงการสหกิจศึกษา</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="anonymous" referrerpolicy="no-referrer" />
    <style>
        .form-section {
            background-color: #F5F5F5;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .form-section h3 {
            margin-bottom: 10px;
        }

        .form-section .form-group {
            margin-bottom: 15px;
        }

        .form-section label {
            display: block;
            margin-bottom: 5px;
        }

        .form-section input[type="text"],
        .form-section input[type="email"],
        .form-section input[type="password"],
        .form-section input[type="date"],
        .form-section select,
        .form-section input[type="file"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
    </style>
</head>

<body class="bg-gradient-to-br from-green-700 to-teal-500 text-white font-sans min-h-screen">
    <!-- Navbar -->
    <?php include "header.php" ?>

    <main class="p-4">
        <section class="bg-white rounded-lg shadow-lg p-6 text-black">
            <h1 class="text-2xl font-bold mb-2 text-center text-green-700">ข้อมูลนิสิตสำหรับเข้าร่วมโครงการสหกิจศึกษา</h1>
            <h2 class="text-xl font-semibold mb-4 text-center">คณะวิทยาศาสตร์และนวัตกรรมดิจิทัล</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="form-section">
                    <h3 class="text-lg font-semibold mb-2">ข้อมูลนิสิต</h3>
                    <form method="POST" enctype="multipart/form-data" class="space-y-4" onsubmit="return validateForm()">
                        <?php
                        include("../connection.php");
                        mysqli_set_charset($conn, "utf8");

                        // ตรวจสอบการเชื่อมต่อฐานข้อมูล
                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        }

                        // ใช้ prepared statements เพื่อความปลอดภัย
                        $sql = "SELECT * FROM student WHERE student_id = ?";
                        if ($stmt = $conn->prepare($sql)) {
                            $stmt->bind_param("s", $User_ID);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $studentData = $result->fetch_assoc();
                            $stmt->close();
                        } else {
                            echo "Error preparing statement: " . $conn->error;
                            exit();
                        }

                        // Fetch course options
                        $sql = "SELECT * FROM course";
                        if ($result = $conn->query($sql)) {
                            $courses = [];
                            while ($row = $result->fetch_assoc()) {
                                $courses[] = $row;
                            }
                            $result->free();
                        } else {
                            die("Error fetching courses: " . $conn->error);
                        }

                        // Fetch email from login table
                        $email = "";
                        $sql = "SELECT email FROM login WHERE user_id = ?";
                        if ($stmt = $conn->prepare($sql)) {
                            $stmt->bind_param("s", $User_ID); // Assuming user_id is the foreign key
                            $stmt->execute();
                            $result = $stmt->get_result();
                            if ($loginData = $result->fetch_assoc()) {
                                $email = $loginData['email'];
                            }
                            $stmt->close();
                        } else {
                            die("Error preparing statement: " . $conn->error);
                        }


                        $conn->close();
                        ?>
                        <img id="studentImage" src="../assets/img/student/<?php echo $studentData['student_id'] ?>.jpg" width="120" height="auto" class="rounded-lg shadow-2xl">

                        <div class="form-group">
                            <label>เพศ</label>
                            <div class="flex space-x-4">
                                <label>
                                    <input type="radio" name="gender" value="male" <?php echo $studentData['student_prefix'] == 'นาย' ? 'checked' : ''; ?> class="mr-2">นาย
                                </label>
                                <label>
                                    <input type="radio" name="gender" value="female" <?php echo $studentData['student_prefix'] == 'นางสาว' ? 'checked' : ''; ?> class="mr-2">นางสาว
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>ชื่อ</label>
                            <input type="text" name="first_name" value="<?php echo htmlspecialchars($studentData['student_surname']); ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label>นามสกุล</label>
                            <input type="text" name="last_name" value="<?php echo htmlspecialchars($studentData['student_lastname']); ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label>รหัสนิสิต</label>
                            <input type="text" name="student_id" value="<?php echo htmlspecialchars($studentData['student_id']); ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label>หลักสูตร</label>
                            <select name="course">
                                <option value=""> <-- โปรดเลือกหลักสูตร --> </option>
                                <?php
                                if (!empty($courses)) {
                                    foreach ($courses as $course) {
                                        $selected = ($studentData['course'] == $course['course_id']) ? 'selected' : '';
                                        echo "<option value=\"" . htmlspecialchars($course['course_id']) . "\" $selected>" . htmlspecialchars($course['course_name']) . "</option>";
                                    }
                                } else {
                                    echo "<option value=\"\">No courses available</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>ชั้นปี</label>
                            <input type="text" name="year" value="<?php echo htmlspecialchars($studentData['year']); ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label>เกรดเฉลี่ย</label>
                            <input type="text" name="gpax" value="<?php echo htmlspecialchars($studentData['gpax']); ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label>เบอร์โทรศัพท์</label>
                            <input type="text" name="phone" value="<?php echo htmlspecialchars($studentData['student_tel']); ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label>e-mail</label>
                            <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" readonly>
                        </div>
                    </form>
                </div>

                <div class="form-section">
                    <h3 class="text-lg font-semibold mb-2">ข้อมูลสถานประกอบการของนิสิต</h3>
                    <form>
                        <div class="form-group">
                            <label>กรุณาเลือกชื่อสถานประกอบการ</label>
                            <select name="company_id">
                                <?php
                                // ดึงข้อมูลชื่อบริษัททั้งหมด
                                $query = "SELECT id, organization_name FROM organization";
                                $result = mysqli_query($conn, $query);
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $selected = ($company_id == $row['id']) ? 'selected' : '';
                                    echo "<option value='{$row['id']}' $selected>{$row['organization_name']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>กรุณาเลือกตำแหน่งที่ต้องการฝึกสหกิจ</label>
                            <select>
                                <option>ตำแหน่ง 1</option>
                                <option>ตำแหน่ง 2</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>ประเภทบริษัท</label>
                            <input type="text" name="company_type" value="<?php echo isset($company['type_organization']) ? $company['type_organization'] : ''; ?>">

                        </div>
                        <div class="form-group">
                            <label>ที่อยู่เลขที่</label>
                            <input type="text" name="address_number" value="<?php echo isset($company['address_number']) ? $company['address_number'] : ''; ?>">

                        </div>
                        <div class="form-group">
                            <label>หมู่ที่</label>
                            <input type="text">
                        </div>
                        <div class="form-group">
                            <label>ซอย</label>
                            <input type="text">
                        </div>
                        <div class="form-group">
                            <label>ถนน</label>
                            <input type="text">
                        </div>



                        <div class="form-group">
                            <label>จังหวัด</label>
                            <select name="province_id" id="province" class="form-select" required>
                                <option value="">กรุณาเลือกจังหวัดที่ต้องการ</option>
                                <?php
                                include('../connection.php');
                                $query = mysqli_query($conn, "SELECT * FROM thai_provinces");
                                while ($result = mysqli_fetch_assoc($query)) : ?>
                                    <option value="<?= $result['id'] ?>"><?= $result['name_th'] ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>อำเภอ</label>
                            <select name="amphure_id" id="amphure" class="form-select" required>
                                <option value="">กรุณาเลือกอำเภอ</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>ตำบล</label>
                            <select name="district_id" id="district" class="form-select" required>
                                <option value="">กรุณาเลือกตำบล</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>รหัสไปรษณีย์</label>
                            <input type="text" id="zipcode" name="zipcode" class="form-control" readonly>
                        </div>

                        <div class="form-group">
                            <label>เบอร์โทรศัพท์</label>
                            <input type="text">
                        </div>
                        <div class="form-group">
                            <label>e-mail</label>
                            <input type="email">
                        </div>
                        <div class="form-group">
                            <label>Website บริษัท</label>
                            <input type="text">
                        </div>
                        <div class="form-group">
                            <label for="company-address">ตำแหน่ง MAP ที่อยู่บริษัท</label>
                            <input type="text" id="company-address" name="company-address" placeholder="Enter Google Maps link" class="form-control" oninput="updateMap()">
                        </div>
                        <div id="map-container">
                            <iframe id="map" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                        </div>

                        <div class="flex space-x-4">
                            <button type="button" class="bg-green-600 text-white px-4 py-2 rounded-lg">เพิ่มข้อมูลสถานประกอบการ</button>
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg">บันทึกข้อมูล</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </main>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const radios = document.querySelectorAll('input[name="gender"]');
            radios.forEach(radio => {
                radio.addEventListener('click', function(e) {
                    e.preventDefault();
                });
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
            const select = document.querySelector('select[name="course"]');
            select.addEventListener('change', function(e) {
                e.preventDefault();
                this.value = '<?php echo htmlspecialchars($studentData['course']); ?>'; // กลับไปที่ค่าเดิม
            });
        });
        $(document).ready(function() {
            // When province is selected
            $('#province').change(function() {
                var province_id = $(this).val();
                $('#amphure').html('<option value="">กรุณาเลือกอำเภอ</option>');
                $('#district').html('<option value="">กรุณาเลือกตำบล</option>');
                $('#zipcode').val('');

                if (province_id) {
                    $.ajax({
                        type: 'POST',
                        url: 'province/get_amphures.php',
                        data: {
                            province_id: province_id
                        },
                        success: function(response) {
                            $('#amphure').html(response);
                        }
                    });
                }
            });

            // When amphure is selected
            $('#amphure').change(function() {
                var amphure_id = $(this).val();
                $('#district').html('<option value="">กรุณาเลือกตำบล</option>');
                $('#zipcode').val('');

                if (amphure_id) {
                    $.ajax({
                        type: 'POST',
                        url: 'province/get_districts.php',
                        data: {
                            amphure_id: amphure_id
                        },
                        success: function(response) {
                            $('#district').html(response);
                        }
                    });
                }
            });

            // When district is selected
            $('#district').change(function() {
                var district_id = $(this).val();

                if (district_id) {
                    $.ajax({
                        type: 'POST',
                        url: 'province/get_zipcode.php',
                        data: {
                            district_id: district_id
                        },
                        success: function(response) {
                            $('#zipcode').val(response);
                        }
                    });
                } else {
                    $('#zipcode').val('');
                }
            });
        });

        function updateMap() {
            const url = document.getElementById('company-address').value;
            const mapIframe = document.getElementById('map');

            // Regular expression to capture the essential part of the URL, including non-Latin characters
            const regex = /(?:https?:\/\/)?(?:www\.|maps\.)?google\.(?:com|co\.\w+)\/maps\/(?:place\/|search\/|view\/)?([^\/@]+)?(?:\/)?(?:@([\d\.\-]+),([\d\.\-]+))?/;
            const match = url.match(regex);

            if (match) {
                // Decode the place name from URL encoding to readable text
                const decodedPlaceName = match[1] ? decodeURIComponent(match[1]) : '';
                const locationQuery = decodedPlaceName || `${match[2]},${match[3]}`;

                const apikey = "AIzaSyCNqYqtAs0oRG2xyLEabqe7_Hihq7nZJwU"; // Replace with your actual API key

                // Construct the iframe source URL with the decoded place name
                mapIframe.src = `https://www.google.com/maps/embed/v1/place?q=${encodeURIComponent(locationQuery)}&key=${apikey}`;

                // For display or further processing
                console.log(`Decoded URL: https://www.google.com/maps/place/${decodedPlaceName}/@${match[2]},${match[3]}`);
            } else {
                mapIframe.src = ''; // Clear the map if the URL is not valid
            }
        }
    </script>
</body>

</html>