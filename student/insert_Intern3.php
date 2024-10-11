<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
include("../connection.php");

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


$sql = "SELECT * FROM `type_organization`";
$query = mysqli_query($conn, $sql);
while ($result = mysqli_fetch_assoc($query)) {
    $TypeOrg_ID = $result['type_id'];
}
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

        #map-container {
            display: none;
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
                </div>

                <div class="form-section">
                    <h3 class="text-lg font-semibold mb-2">ข้อมูลสถานประกอบการของนิสิต</h3>

                    <?php
                    include("../connection.php");

                    // Fetch the intern data
                    $sql_intern = "SELECT type_organization, organization_id, position FROM intern WHERE student_id = $User_ID";
                    $stmt_intern = $conn->prepare($sql_intern);
                    $stmt_intern->execute();
                    $result_intern = $stmt_intern->get_result();

                    $intern_data = $result_intern->fetch_assoc();
                    $selected_type_organization = $intern_data['type_organization'];
                    $selected_organization_id = $intern_data['organization_id'];
                    $selected_position = $intern_data['position'];
                    ?>

                    <div class="form-group">
                        <label>กรุณาเลือกประเภทสถานประกอบการ</label>
                        <select id="organization_type" name="organization_type" class="form-select">
                            <option value="">กรุณาเลือกประเภทสถานประกอบการ</option>
                            <?php
                            $sql = "SELECT * FROM `type_organization`";
                            $query = mysqli_query($conn, $sql);
                            while ($result = mysqli_fetch_assoc($query)) {
                                $Type_ID = $result['type_id'];
                                $selected = ($Type_ID == $selected_type_organization) ? 'selected' : '';
                            ?>
                                <option value="<?php echo $Type_ID; ?>" <?php echo $selected; ?>><?php echo $result['type_name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>กรุณาเลือกชื่อสถานประกอบการ</label>
                        <select id="organization" name="organization" class="form-select">
                            <?php
                            // ถ้ามี selected_organization_id ให้นำชื่อสถานประกอบการที่เลือกมาแสดงเลย
                            if ($selected_organization_id) {
                                $organization_query = mysqli_query($conn, "SELECT * FROM organization WHERE organization_id = '$selected_organization_id'");
                                $organization_data = mysqli_fetch_assoc($organization_query);
                                echo "<option value='{$organization_data['organization_id']}' selected>{$organization_data['organization_name']}</option>";
                            } else {
                                echo '<option value="">กรุณาเลือกสถานประกอบการ</option>';
                            }
                            ?>
                            <!-- Options will be populated by JavaScript if type is changed -->
                        </select>
                    </div>


                    <div class="form-group">
                        <label>กรุณาเลือกตำแหน่งที่ต้องการฝึกสหกิจ</label>
                        <select id="position" name="position" class="form-select">
                            <option value="">กรุณาเลือกตำแหน่ง</option>
                            <?php
                            $query = mysqli_query($conn, "SELECT * FROM position_type");
                            while ($result = mysqli_fetch_assoc($query)) {
                                $selected = ($result['position_id'] == $selected_position) ? 'selected' : '';
                            ?>
                                <option value="<?= $result['position_id'] ?>" <?= $selected ?>><?= $result['position_name'] ?></option>
                            <?php } ?>
                        </select>
                    </div>


                    <div class="form-group">
                        <label>ที่อยู่เลขที่</label>
                        <input type="text" id="address" name="address_number" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label>หมู่ที่</label>
                        <input type="text" id="moo" name="moo" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label>ซอย</label>
                        <input type="text" id="soi" name="soi" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label>ถนน</label>
                        <input type="text" id="road" name="road" class="form-control" readonly>
                    </div>

                    <div class="form-group">
                        <label>จังหวัด</label>
                        <input type="text" id="province_id" name="province_id" class="form-control" readonly>
                    </div>

                    <div class="form-group">
                        <label>อำเภอ</label>
                        <input type="text" id="amphure_id" name="amphure_id" class="form-control" readonly>
                    </div>

                    <div class="form-group">
                        <label>ตำบล</label>
                        <input type="text" id="district_id" name="district_id" class="form-control" readonly>
                    </div>

                    <div class="form-group">
                        <label>รหัสไปรษณีย์</label>
                        <input type="text" id="zip_code" name="zip_code" class="form-control" readonly>
                    </div>

                    <div class="form-group">
                        <label>เบอร์โทรศัพท์</label>
                        <input type="text" id="tel_phone" name="tel_phone" readonly>
                    </div>
                    <div class="form-group">
                        <label>Fax</label>
                        <input type="text" id="fax" name="fax" readonly>
                    </div>
                    <div class="form-group">
                        <label>e-mail</label>
                        <input type="email" id="email" name="email" readonly>
                    </div>
                    <div class="form-group">
                        <label>Website บริษัท</label>
                        <input type="text" id="webs" name="website" readonly>
                    </div>
                    <div class="form-group">
                        <label for="company-address">ตำแหน่ง MAP ที่อยู่บริษัท</label>
                        <input type="text" id="company-address" name="maps" placeholder="Enter Google Maps link" class="form-control" oninput="updateMap()" readonly>
                    </div>
                    <div id="map-container">
                        <iframe id="map" class="w-full h-[400px]" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    </div>

                    <?php
                    if ($selected_organization_id == "" && $selected_position == "") { ?>
                        <div class="flex space-x-4 pt-5">
                            <button type="button" href="insert_organization.php" class="bg-green-600 text-white px-4 py-2 rounded-lg">
                                <i class="fas fa-plus-circle"></i> เพิ่มข้อมูลสถานประกอบการ
                            </button>
                            <button type="submit" name="insert_intern" class="bg-blue-600 text-white px-4 py-2 rounded-lg">
                                <i class="fas fa-save"></i> บันทึกข้อมูล
                            </button>
                        </div>
                    <?php } else { ?>
                        <div class="flex justify-end space-x-4 pt-5">
                            <button type="button" id="edit_intern" name="edit_intern" class="bg-yellow-500 text-white px-4 py-2 rounded-lg">
                                <i class="fas fa-edit"></i> แก้ไขข้อมูล
                            </button>
                            <button type="submit" id="update_intern" name="update_intern" class="bg-blue-600 text-white px-4 py-2 rounded-lg">
                                <i class="fas fa-save"></i> บันทึกข้อมูล
                            </button>
                        </div>
                    <?php } ?>
                </div>
                </form>

            </div>

        </section>
    </main>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Disable gender radio buttons
            document.querySelectorAll('input[name="gender"]').forEach(radio => {
                radio.addEventListener('click', function(e) {
                    e.preventDefault();
                });
            });

            // Prevent course select change
            document.querySelector('select[name="course"]').addEventListener('change', function(e) {
                e.preventDefault();
                this.value = '<?php echo htmlspecialchars($studentData["course"]); ?>'; // Revert to the original value
            });
        });

        $(document).ready(function() {
            // เรียกข้อมูลสถานประกอบการที่ตรงกับประเภทที่เลือก
            $('#organization_type').change(function() {
                const typeId = $(this).val();
                fetchOrganizationsByType(typeId);
            });

            // ฟังก์ชันสำหรับดึงข้อมูลสถานประกอบการตามประเภท
            function fetchOrganizationsByType(typeId) {
                $.ajax({
                    type: 'POST',
                    url: 'ajax/fetch_organizations.php',
                    data: {
                        type_id: typeId
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.error) {
                            alert('Error fetching organizations: ' + response.error);
                        } else {
                            // เคลียร์และกรอกข้อมูลลงใน Dropdown สถานประกอบการ
                            $('#organization').empty();
                            if (response.organizations.length > 0) {
                                $.each(response.organizations, function(index, organization) {
                                    $('#organization').append('<option value="' + organization.organization_id + '">' + organization.organization_name + '</option>');
                                });
                                // ถ้าเลือกสถานประกอบการแรก ให้เรียก fetchOrganizationData อัตโนมัติ
                                fetchOrganizationData(response.organizations[0].organization_id); // เปลี่ยนเป็น ID ของสถานประกอบการแรก
                            } else {
                                $('#organization').append('<option value="">ไม่พบข้อมูลสถานประกอบการ</option>');
                                clearOrganizationFields();
                            }
                        }
                    },
                    error: function(error) {
                        alert('An error occurred while fetching organizations. Please try again.');
                    }
                });
            }

            // ดึงข้อมูลสถานประกอบการตามประเภทที่ถูกเลือกเมื่อโหลดหน้า
            const selectedTypeOrganization = '<?= $selected_type_organization ?>';
            if (selectedTypeOrganization) {
                fetchOrganizationsByType(selectedTypeOrganization);
            }

            // ดึงค่า organization_id ที่ถูกเลือกไว้จากฐานข้อมูล
            const selectedOrganizationId = '<?= $selected_organization_id ?>';
            if (selectedOrganizationId) {
                fetchOrganizationData(selectedOrganizationId);
                $('#organization_type, #organization, #position').prop('disabled', true);
                $('#update_intern').prop('hidden', true);
            }

            $('#organization').change(function() {
                const organizationId = $(this).val();
                fetchOrganizationData(organizationId);
            });

            // ฟังก์ชันสำหรับดึงข้อมูลสถานประกอบการและกรอกข้อมูลลงฟอร์ม
            function fetchOrganizationData(organizationId) {
                if (organizationId) {
                    $.ajax({
                        type: 'POST',
                        url: 'ajax/get_organization.php',
                        data: {
                            organization_id: organizationId
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.error) {
                                alert('Error fetching organization details: ' + response.error);
                            } else {
                                // กรอกข้อมูลที่ได้รับลงในฟอร์ม
                                $('#company-type').val(response.type_name || '');
                                $('#address').val(response.address_number || '');
                                $('#moo').val(response.moo || '');
                                $('#soi').val(response.soy || '');
                                $('#road').val(response.road || '');
                                $('#province_id').val(response.province_name || '').change();
                                $('#amphure_id').val(response.amphure_name || '').change();
                                $('#district_id').val(response.tambon_name || '').change();
                                $('#zip_code').val(response.zip_code || '');
                                $('#tel_phone').val(response.tel_phone || '');
                                $('#fax').val(response.fax || '');
                                $('#email').val(response.email || '');
                                $('#webs').val(response.website || '');
                                $('#company-address').val(response.maps || '');
                                updateMap(); // อัพเดทแผนที่
                            }
                        },
                        error: function(error) {
                            alert('An error occurred while fetching organization details. Please try again.');
                        }
                    });
                } else {
                    // เคลียร์ข้อมูลหากไม่ได้เลือกสถานประกอบการ
                    clearOrganizationFields();
                }
            }

            // ฟังก์ชันสำหรับเคลียร์ข้อมูลฟอร์ม
            function clearOrganizationFields() {
                $('#company-type').val('');
                $('#address').val('');
                $('#moo').val('');
                $('#soi').val('');
                $('#road').val('');
                $('#province_id').val('').change();
                $('#amphure_id').val('').change();
                $('#district_id').val('').change();
                $('#zip_code').val('');
                $('#tel_phone').val('');
                $('#fax').val('');
                $('#email').val('');
                $('#webs').val('');
                $('#company-address').val('');
                // เคลียร์แผนที่
                const mapIframe = document.getElementById('map-container');
                const map = document.getElementById('map');
                map.src = ''; // เคลียร์ iframe แผนที่
                mapIframe.style.display = 'none'; // ซ่อนแผนที่
            }

            $('#edit_intern').on('click', function() {
                $('#organization_type, #organization, #position').prop('disabled', false);
                $('#edit_intern').prop('hidden', true);
                $('#update_intern').prop('hidden', false);
                // เลื่อนหน้าจอขึ้นไปที่ด้านบนสุด
                $('html, body').animate({
                    scrollTop: 0
                }, 'smooth');
            });
        });


        function updateMap() {
            const url = document.getElementById('company-address').value;
            const mapIframe = document.getElementById('map-container');
            const map = document.getElementById('map');

            // Regular expression to capture the essential part of the URL, including non-Latin characters
            const regex = /(?:https?:\/\/)?(?:www\.|maps\.)?google\.(?:com|co\.\w+)\/maps\/(?:place\/|search\/|view\/)?([^\/@]+)?(?:\/)?(?:@([\d\.\-]+),([\d\.\-]+))?/;
            const match = url.match(regex);

            if (match) {
                // Decode the place name from URL encoding to readable text
                const decodedPlaceName = match[1] ? decodeURIComponent(match[1]) : '';
                const locationQuery = decodedPlaceName || `${match[2]},${match[3]}`;

                const apikey = "AIzaSyCNqYqtAs0oRG2xyLEabqe7_Hihq7nZJwU"; // Replace with your actual API key

                // Construct the iframe source URL with the decoded place name
                map.src = `https://www.google.com/maps/embed/v1/place?q=${encodeURIComponent(locationQuery)}&key=${apikey}`;

                // Show the map container
                mapIframe.style.display = 'block';
            } else {
                // Hide the map container
                mapIframe.style.display = 'none';
                map.src = ''; // Clear the map if the URL is not valid
            }
        }
    </script>


</body>

</html>

<?php
include('../connection.php');
session_start(); // Ensure session is started

$response = array();

if (isset($_POST['insert_intern'])) {
    $UserID = $_SESSION['UserID'];
    $organization_type = mysqli_real_escape_string($conn, $_POST['organization_type']);
    $organization = mysqli_real_escape_string($conn, $_POST['organization']);
    $position = mysqli_real_escape_string($conn, $_POST['position']);
    // ตั้งค่าเขตเวลาเป็นเวลาประเทศไทย
    date_default_timezone_set('Asia/Bangkok');
    $date = date("Y-m-d H:i:s");

    // Prepare the SQL query
    $sql = "INSERT INTO `intern` (`student_id`, `type_organization `, `organization_id`, `position`, `datetime`) 
            VALUES ('$UserID', '$organization_type', '$organization', '$position', '$date')";

    // Execute the query
    if (mysqli_query($conn, $sql)) {
        echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกสำเร็จ!',
                    text: 'บันทึกข้อมูลเข้าร่วมโครงการสหกิจศึกษาสำเร็จ.',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    window.location.href = 'index.php';
                });
              </script>";
    } else {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด!',
                    text: 'ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่ : " . mysqli_error($conn) . "',
                    confirmButtonText: 'ตกลง'
                });
              </script>";
    }

    // Close the connection
    mysqli_close($conn);

    // Return the response in JSON format
    echo json_encode($response);
}

if (isset($_POST['update_intern'])) {
    $UserID = $_SESSION['UserID'];
    $organization_type = mysqli_real_escape_string($conn, $_POST['organization_type']);
    $organization = mysqli_real_escape_string($conn, $_POST['organization']);
    $position = mysqli_real_escape_string($conn, $_POST['position']);
    // ตั้งค่าเขตเวลาเป็นเวลาประเทศไทย
    date_default_timezone_set('Asia/Bangkok');
    $date = date("Y-m-d H:i:s");
    // Prepare the SQL query
    $sql = "UPDATE `intern` SET `type_organization`='$organization_type',`organization_id`='$organization',`position`='$position',`datetime`='$date' WHERE student_id = $UserID";
    // Execute the query
    if (mysqli_query($conn, $sql)) {
        echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกสำเร็จ!',
                    text: 'อัพเดทข้อมูลเข้าร่วมโครงการสหกิจศึกษาสำเร็จ.',
                    confirmButtonText: 'ตกลง',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    window.location.href = 'insert_Intern.php';
                });
              </script>";
    } else {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด!',
                    text: 'ไม่สามารถอัพเดทข้อมูลได้ กรุณาลองใหม่ : " . mysqli_error($conn) . "',
                    confirmButtonText: 'ตกลง'
                });
              </script>";
    }

    // Close the connection
    mysqli_close($conn);

    // Return the response in JSON format
    echo json_encode($response);
}
?>