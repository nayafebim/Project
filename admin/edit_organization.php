<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการข้อมูลสหกิจศึกษา</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        #map-container {
            display: none;
        }
    </style>

</head>

<body class="bg-gradient-to-br from-green-700 to-teal-500 text-white min-h-screen">

    <!-- Header -->
    <?php include "header.php" ?>

    <!-- Main Content -->
    <main class="p-6">
        <div class="text-center mb-4">
            <h1 class="text-2xl font-bold">คณะวิทยาศาสตร์และนวัตกรรมดิจิทัล</h1>
            <h2 class="text-2xl font-bold text-white mb-4">จัดการข้อมูลสหกิจศึกษา</h2>
            <div class="flex justify-center space-x-4">
                <button class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-lg flex items-center">
                    <i class="fa fa-plus mr-2"></i> เพิ่มข้อมูลผู้ใช้
                </button>
                <button class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-3 rounded-lg flex items-center">
                    <i class="fa fa-file-excel mr-2"></i> นำเข้าข้อมูล
                </button>
                <button class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg flex items-center">
                    <i class="fa fa-database mr-2"></i> จัดการข้อมูลที่เกี่ยวข้อง
                </button>
            </div>
        </div>

        <div class="bg-white text-black rounded-lg shadow-lg p-6">
            <h3 class="text-xl font-bold text-green-700 mb-4">จัดการข้อมูลที่เกี่ยวข้อง : แก้ไขข้อมูลสถานประกอบการ</h3>
            <form class="space-y-4" method="POST">
                <!-- Form Fields -->
                <?php
                $Org_ID = null;
                if (isset($_GET["organization_id"])) {
                    $Org_ID = $_GET["organization_id"];
                }

                include "../connection.php";

                if ($Org_ID) {
                    // Fetch ข้อมูลบริษัทที่มีอยู่
                    $query = mysqli_query($conn, "SELECT * FROM organization WHERE organization_id = '$Org_ID'");
                    $organization = mysqli_fetch_assoc($query);
                }
                ?>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block mb-2 font-semibold" for="company-name">ชื่อสถานประกอบการ</label>
                        <input id="org_name" name="org_name" type="text" class="w-full border rounded-lg px-3 py-2" value="<?= $organization['organization_name'] ?>">
                    </div>
                    <div>
                        <label class="block mb-2 font-semibold" for="company-type">ประเภทบริษัท</label>
                        <select id="company_type" name="company_type" class="w-full border rounded-lg px-3 py-2">
                            <option value="">กรุณาเลือกประเภทบริษัท</option>
                            <?php
                            $query = mysqli_query($conn, "SELECT * FROM type_organization");
                            while ($result = mysqli_fetch_assoc($query)) : ?>
                                <option value="<?= $result['type_id'] ?>" <?= $organization['type_organization'] == $result['type_id'] ? 'selected' : '' ?>><?= $result['type_name'] ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <!-- Add similar input fields for other attributes -->
                    <div>
                        <label class="block mb-2 font-semibold" for="address">ที่อยู่เลขที่</label>
                        <input id="address" name="address" type="text" class="w-full border rounded-lg px-3 py-2" value="<?= $organization['address_number'] ?>">
                    </div>
                    <div>
                        <label class="block mb-2 font-semibold" for="village">หมู่ที่</label>
                        <input id="village" name="village" type="text" class="w-full border rounded-lg px-3 py-2" value="<?= $organization['moo'] ?>">
                    </div>
                    <div>
                        <label class="block mb-2 font-semibold" for="building">ชั้น/อาคาร/ตึกที่</label>
                        <input id="building" name="building" type="text" class="w-full border rounded-lg px-3 py-2" value="<?= $organization['floor'] ?>">
                    </div>
                    <div>
                        <label class="block mb-2 font-semibold" for="soy">ซอย</label>
                        <input id="soy" name="soy" type="text" class="w-full border rounded-lg px-3 py-2" value="<?= $organization['soy'] ?>">
                    </div>
                    <div>
                        <label class="block mb-2 font-semibold" for="road">ถนน</label>
                        <input id="road" name="road" type="text" class="w-full border rounded-lg px-3 py-2" value="<?= $organization['road'] ?>">
                    </div>
                    <div>
                        <label class="block mb-2 font-semibold" for="province">จังหวัด</label>
                        <select name="province" id="province" class="w-full border rounded-lg px-3 py-2">
                            <option value="">กรุณาเลือกจังหวัด</option>
                            <?php
                            $query = mysqli_query($conn, "SELECT * FROM thai_provinces");
                            while ($result = mysqli_fetch_assoc($query)) : ?>
                                <option value="<?= $result['id'] ?>" <?= $organization['province'] == $result['id'] ? 'selected' : '' ?>><?= $result['name_th'] ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block mb-2 font-semibold" for="district">อำเภอ</label>
                        <select name="amphure" id="amphure" class="w-full border rounded-lg px-3 py-2">
                            <option value="">กรุณาเลือกอำเภอ</option>
                            <!-- Fetch and populate amphure data -->
                            <?php
                            $query = mysqli_query($conn, "SELECT * FROM thai_amphures");
                            while ($result = mysqli_fetch_assoc($query)) : ?>
                                <option value="<?= $result['id'] ?>" <?= $organization['amphure'] == $result['id'] ? 'selected' : '' ?>><?= $result['name_th'] ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block mb-2 font-semibold" for="subdistrict">ตำบล</label>
                        <select name="district" id="district" class="w-full border rounded-lg px-3 py-2">
                            <option value="">กรุณาเลือกตำบล</option>
                            <!-- Fetch and populate district data -->
                            <?php
                            $query = mysqli_query($conn, "SELECT * FROM thai_tambons");
                            while ($result = mysqli_fetch_assoc($query)) : ?>
                                <option value="<?= $result['id'] ?>" <?= $organization['district'] == $result['id'] ? 'selected' : '' ?>><?= $result['name_th'] ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block mb-2 font-semibold" for="postcode">รหัสไปรษณีย์</label>
                        <input type="text" id="zipcode" name="zipcode" class="w-full border rounded-lg px-3 py-2" value="<?= $organization['zip_code'] ?>" readonly>
                    </div>
                    <div>
                        <label class="block mb-2 font-semibold" for="phone">เบอร์โทรศัพท์</label>
                        <input id="phone" name="phone" type="text" class="w-full border rounded-lg px-3 py-2" value="<?= $organization['tel_phone'] ?>">
                    </div>
                    <div>
                        <label class="block mb-2 font-semibold" for="phone">โทรสาร/แฟกซ์ (Fax) </label>
                        <input id="fax" name="fax" type="text" class="w-full border rounded-lg px-3 py-2" value="<?= $organization['fax'] ?>">
                    </div>
                    <div>
                        <label class="block mb-2 font-semibold" for="email">อีเมล</label>
                        <input id="email" name="email" type="email" class="w-full border rounded-lg px-3 py-2" value="<?= $organization['email'] ?>">
                    </div>
                    <div>
                        <label class="block mb-2 font-semibold" for="website">เว็บไซต์</label>
                        <input id="website" name="website" type="text" class="w-full border rounded-lg px-3 py-2" value="<?= $organization['website'] ?>">
                    </div>
                    <div>
                        <label class="block mb-2 font-semibold" for="location">Google Map ที่อยู่บริษัท</label>
                        <input type="text" id="company-address" name="company-address" placeholder="กรอกลิงค์ Google Maps" class="w-full border rounded-lg px-3 py-2" value="<?= $organization['maps'] ?>" oninput="updateMap()">
                    </div>
                </div>
                <div id="map-container" class="flex items-center">
                    <iframe id="map" class="w-full h-[400px]" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                </div>

                <div class="text-right">
                    <button type="submit" name="update_org" class="bg-green-700 text-white font-bold py-2 px-4 rounded-lg">บันทึกข้อมูล</button>
                </div>
            </form>

        </div>
    </main>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <script>
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

        document.addEventListener('DOMContentLoaded', function() {
            const companyAddressInput = document.getElementById('company-address');
            if (companyAddressInput.value) {
                updateMap();
            }
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
// Include database connection file
include('../connection.php');

if (isset($_POST['update_org'])) {
    // Retrieve form data and sanitize it
    $organization_name = mysqli_real_escape_string($conn, $_POST['org_name']);
    $address_number = mysqli_real_escape_string($conn, $_POST['address']);
    $moo = mysqli_real_escape_string($conn, $_POST['village']);
    $floor = mysqli_real_escape_string($conn, $_POST['building']);
    $soy = mysqli_real_escape_string($conn, $_POST['soy']);
    $road = mysqli_real_escape_string($conn, $_POST['road']);
    $amphure = mysqli_real_escape_string($conn, $_POST['amphure']);
    $district = mysqli_real_escape_string($conn, $_POST['district']);
    $province = mysqli_real_escape_string($conn, $_POST['province']);
    $zip_code = mysqli_real_escape_string($conn, $_POST['zipcode']);
    $tel_phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $fax = mysqli_real_escape_string($conn, $_POST['fax']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $website = mysqli_real_escape_string($conn, $_POST['website']);
    $maps = mysqli_real_escape_string($conn, $_POST['company-address']);
    $type_organization = mysqli_real_escape_string($conn, $_POST['company_type']);

    $update_query = "UPDATE `organization` SET 
                    organization_name = '$organization_name',
                    address_number = '$address_number',
                    moo = '$moo',
                    floor = '$floor',
                    soy = '$soy',
                    road = '$road',
                    amphure = '$amphure',
                    district = '$district',
                    province = '$province',
                    zip_code = '$zip_code',
                    tel_phone = '$tel_phone',
                    fax = '$fax',
                    email = '$email',
                    website = '$website',
                    maps = '$maps',
                    type_organization = '$type_organization'
                WHERE organization_id = '$Org_ID'";


    if (mysqli_query($conn, $update_query)) {
        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'อัปเดตข้อมูลสำเร็จ',
                text: 'ข้อมูลสถานประกอบการถูกอัปเดตแล้ว',
                confirmButtonText: 'ตกลง'
            }).then(() => {
                window.location.href = 'organization.php'; // Redirect to the list page or another page
            });
        </script>";
    } else {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'เกิดข้อผิดพลาด',
                text: 'ไม่สามารถอัปเดตข้อมูลได้: " . mysqli_error($conn) . "',
                confirmButtonText: 'ตกลง'
            });
        </script>";
    }
}

// Close the database connection
mysqli_close($conn);
?>