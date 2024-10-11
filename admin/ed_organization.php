<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('../connection.php');

if (isset($_POST['insert_type'])) {
    $type_name = mysqli_real_escape_string($conn, $_POST['type_name']);

    // Insert new type into the database
    $query = "INSERT INTO type_organization (type_name) VALUES ('$type_name')";
    if (mysqli_query($conn, $query)) {
        // Return success response
        echo json_encode(['success' => true, 'type_id' => mysqli_insert_id($conn)]);
    } else {
        // Return error response
        echo json_encode(['success' => false, 'message' => mysqli_error($conn)]);
    }

    // Close the connection
    mysqli_close($conn);
    exit(); // Ensure the script stops after handling AJAX request
}

$amphure = isset($_POST['amphure']) && !empty($_POST['amphure']) ? mysqli_real_escape_string($conn, $_POST['amphure']) : null;

// ตรวจสอบว่าค่า amphure ไม่เป็น null หรือว่าง
if (is_null($amphure)) {
    echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'เกิดข้อผิดพลาด!',
                text: 'กรุณาเลือกอำเภอ.',
                confirmButtonText: 'ตกลง'
            });
          </script>";
} else {
    // ดำเนินการต่อไปหากค่า amphure ถูกต้อง
    $query = "INSERT INTO organization (
                organization_name,
                address_number,
                moo,
                floor,
                soy,
                road,
                amphure,
                district,
                province,
                zip_code,
                tel_phone,
                fax,
                email,
                website,
                maps,
                type_organization
              ) VALUES (
                '$organization_name',
                '$address_number',
                '$moo',
                '$floor',
                '$soy',
                '$road',
                '$amphure',
                '$district',
                '$province',
                '$zip_code',
                '$tel_phone',
                '$fax',
                '$email',
                '$website',
                '$maps',
                '$type_organization'
              )";

    if (mysqli_query($conn, $query)) {
        echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกสำเร็จ!',
                    text: 'บันทึกสถานประกอบการเรียบร้อยแล้ว.',
                    confirmButtonText: 'ตกลง'
                }).then(() => {
                    window.location.href = 'organization.php';
                });
              </script>";
    } else {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด!',
                    text: 'ไม่สามารถบันทึกข้อมูลได้: " . mysqli_error($conn) . "',
                    confirmButtonText: 'ตกลง'
                });
              </script>";
    }
}

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

        /* Modal Background Fade-in Animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        /* Modal Fade-out Animation */
        @keyframes fadeOut {
            from {
                opacity: 1;
            }

            to {
                opacity: 0;
            }
        }

        /* Modal Slide-in Animation */
        @keyframes slideIn {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* Modal Slide-out Animation */
        @keyframes slideOut {
            from {
                transform: translateY(0);
                opacity: 1;
            }

            to {
                transform: translateY(-50px);
                opacity: 0;
            }
        }

        /* Modal Styles */
        #add-type-modal {
            animation: fadeIn 0.3s ease-in-out, slideIn 0.3s ease-in-out;
        }

        #add-type-modal.hidden {
            animation: fadeOut 0.3s ease-in-out, slideOut 0.3s ease-in-out;
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
            <h3 class="text-xl font-bold text-green-700 mb-4">จัดการข้อมูลที่เกี่ยวข้อง : เพิ่มข้อมูลสถานประกอบการ</h3>
            <form class="space-y-4" method="POST">
                <div class="flex justify-end">
                    <button type="button" id="add-new-type-btn" class="ml-2 bg-blue-500 hover:bg-blue-600 text-white text-sm px-2 py-2 rounded-lg">
                        เพิ่มประเภทใหม่
                    </button>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block mb-2 font-semibold" for="company-name">ชื่อสถานประกอบการ</label>
                        <input id="org_name" name="org_name" type="text" class="w-full border rounded-lg px-3 py-2">
                    </div>
                    <div>
                        <label class="block mb-2 font-semibold" for="company-type">ประเภทบริษัท</label>
                        <div class="flex items-center">
                            <select id="company_type" name="company_type" class="w-full border rounded-lg px-3 py-2">
                                <option value="">กรุณาเลือกประเภทบริษัท</option>
                                <?php
                                include('../connection.php');
                                $query = mysqli_query($conn, "SELECT * FROM type_organization");
                                while ($result = mysqli_fetch_assoc($query)) : ?>
                                    <option value="<?= $result['type_id'] ?>"><?= $result['type_name'] ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block mb-2 font-semibold" for="address">ที่อยู่เลขที่</label>
                        <input id="address" name="address" type="text" class="w-full border rounded-lg px-3 py-2">
                    </div>
                    <div>
                        <label class="block mb-2 font-semibold" for="village">หมู่ที่</label>
                        <input id="village" name="village" type="text" class="w-full border rounded-lg px-3 py-2">
                    </div>
                    <div>
                        <label class="block mb-2 font-semibold" for="building">ชั้น/อาคาร/ตึกที่</label>
                        <input id="building" name="building" type="text" class="w-full border rounded-lg px-3 py-2">
                    </div>
                    <div>
                        <label class="block mb-2 font-semibold" for="soy">ซอย</label>
                        <input id="soy" name="soy" type="text" class="w-full border rounded-lg px-3 py-2">
                    </div>
                    <div>
                        <label class="block mb-2 font-semibold" for="road">ถนน</label>
                        <input id="road" name="road" type="text" class="w-full border rounded-lg px-3 py-2">
                    </div>
                    <div>
                        <label class="block mb-2 font-semibold" for="province">จังหวัด</label>
                        <select name="province" id="province" class="w-full border rounded-lg px-3 py-2">
                            <option value="">กรุณาเลือกจังหวัด</option>
                            <?php
                            include('../connection.php');
                            $query = mysqli_query($conn, "SELECT * FROM thai_provinces");
                            while ($result = mysqli_fetch_assoc($query)) : ?>
                                <option value="<?= $result['id'] ?>"><?= $result['name_th'] ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block mb-2 font-semibold" for="district">อำเภอ</label>
                        <select name="amphure" id="amphure" class="w-full border rounded-lg px-3 py-2">
                            <option value="">กรุณาเลือกอำเภอ</option>
                        </select>
                    </div>
                    <div>
                        <label class="block mb-2 font-semibold" for="subdistrict">ตำบล</label>
                        <select name="district" id="district" class="w-full border rounded-lg px-3 py-2">
                            <option value="">กรุณาเลือกตำบล</option>
                        </select>
                    </div>
                    <div>
                        <label class="block mb-2 font-semibold" for="postcode">รหัสไปรษณีย์</label>
                        <input type="text" id="zipcode" name="zipcode" class="w-full border rounded-lg px-3 py-2" readonly>
                    </div>
                    <div>
                        <label class="block mb-2 font-semibold" for="phone">เบอร์โทรศัพท์</label>
                        <input id="phone" name="phone" type="text" class="w-full border rounded-lg px-3 py-2">
                    </div>
                    <div>
                        <label class="block mb-2 font-semibold" for="phone">โทรสาร/แฟกซ์ (Fax) </label>
                        <input id="fax" name="fax" type="text" class="w-full border rounded-lg px-3 py-2">
                    </div>
                    <div>
                        <label class="block mb-2 font-semibold" for="email">E-mail</label>
                        <input id="email" name="email" type="email" class="w-full border rounded-lg px-3 py-2">
                    </div>
                    <div>
                        <label class="block mb-2 font-semibold" for="website">Website บริษัท</label>
                        <input id="website" name="website" type="text" class="w-full border rounded-lg px-3 py-2">
                    </div>
                    <div>
                        <label class="block mb-2 font-semibold" for="location">Google Map ที่อยู่บริษัท</label>
                        <input type="text" id="company-address" name="company-address" placeholder="กรอกลิงค์ Google Maps" class="w-full border rounded-lg px-3 py-2" oninput="updateMap()">
                    </div>
                </div>
                <div id="map-container" class="flex items-center">
                    <iframe id="map" class="w-full h-[400px]" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                </div>

                <div class="flex justify-between mt-6">
                    <button type="button" class="bg-green-600 text-white px-4 py-2 rounded-lg">
                        <i class="fa fa-arrow-left mr-2"></i> ย้อนกลับ
                    </button>
                    <button type="submit" name="insert_org" class="bg-green-600 text-white px-4 py-2 rounded-lg">
                        บันทึก
                    </button>
                </div>
                <!-- Modal HTML -->

            </form>
            <!-- Modal HTML -->
            <div id="add-type-modal" class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-75 z-50 hidden">
                <div class="bg-white text-black rounded-lg shadow-lg p-6 w-1/3">
                    <h3 class="text-xl font-bold text-green-700 mb-4">เพิ่มประเภทบริษัทใหม่</h3>
                    <form id="add-type-form" class="space-y-4" method="POST">
                        <div>
                            <label class="block mb-2 font-semibold" for="new_type_name">ชื่อประเภทบริษัท</label>
                            <input id="new_type_name" name="new_type_name" type="text" class="w-full border rounded-lg px-3 py-2" required>
                        </div>
                        <div class="flex justify-between mt-6">
                            <button type="button" id="modal-close-btn" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                                ปิด
                            </button>
                            <button type="submit" name="insert_type" id="modal-submit-btn" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                                บันทึก
                            </button>
                        </div>
                    </form>
                </div>
            </div>

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

        document.getElementById('modal-submit-btn').addEventListener('click', function(event) {
            event.preventDefault();
            addNewType();
        });

        document.getElementById('modal-close-btn').addEventListener('click', function() {
            toggleModal(false);
        });

        // Toggle Modal
        function toggleModal(show) {
            const modal = document.getElementById('add-type-modal');
            if (show) {
                modal.classList.remove('hidden');
            } else {
                modal.classList.add('hidden');
            }
        }

        // Event Listeners for Modal
        document.getElementById('add-new-type-btn').addEventListener('click', function() {
            toggleModal(true);
        });

        document.getElementById('modal-close-btn').addEventListener('click', function() {
            toggleModal(false);
        });

        document.getElementById('modal-submit-btn').addEventListener('click', function(event) {
            event.preventDefault();
            addNewType();
        });

        function addNewType() {
            const typeName = document.getElementById('new_type_name').value;

            if (!typeName) {
                Swal.fire({
                    icon: 'warning',
                    title: 'กรุณากรอกชื่อประเภท',
                    text: 'กรุณากรอกชื่อประเภทบริษัทใหม่',
                    confirmButtonText: 'ตกลง'
                });
                return;
            }

            $.ajax({
                type: 'POST',
                url: 'insert_organization.php',
                data: {
                    type_name: typeName
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'เพิ่มประเภทบริษัทสำเร็จ!',
                            text: 'ประเภทบริษัทใหม่ถูกเพิ่มแล้ว.',
                            confirmButtonText: 'ตกลง'
                        }).then(() => {
                            // Add the new type to the dropdown
                            $('#company_type').append(new Option(typeName, response.type_id));
                            toggleModal(false); // Close the modal
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาด!',
                            text: 'ไม่สามารถเพิ่มประเภทบริษัทได้: ' + response.message,
                            confirmButtonText: 'ตกลง'
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด!',
                        text: 'ไม่สามารถติดต่อเซิร์ฟเวอร์ได้',
                        confirmButtonText: 'ตกลง'
                    });
                }
            });
        }
    </script>

</body>

</html>
