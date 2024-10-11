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

?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>การกรอกข้อมูลการเข้าร่วมโครงการสหกิจศึกษา</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="anonymous" referrerpolicy="no-referrer" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Include jQuery -->
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #1d4d41;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            padding: 20px;
        }

        .form-section {
            background-color: #e6f4f1;
            padding: 20px;
            border-radius: 8px;
            width: 40%;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .form-section h2 {
            color: #ff9900;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-group select,
        .form-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .form-group input[type="date"] {
            cursor: pointer;
        }

        .form-group button {
            background-color: #007f66;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }

        .form-group button:hover {
            background-color: #006b55;
        }

        .table-section {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            width: 50%;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .table-section h2 {
            color: #007f66;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table,
        th,
        td {
            border: 1px solid #ccc;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #007f66;
            color: white;
        }
    </style>
</head>

<body>
    <?php include "header.php" ?>
    <div class="container">
        <!-- ฟอร์มกรอกข้อมูล -->
        <div class="form-section">
            <h2>กรอกข้อมูลการเข้าร่วมโครงการสหกิจศึกษา</h2>
            <form>
                <div class="form-group">
                    <label for="company">โปรดเลือกสถานประกอบการ:</label>
                    <select id="company" onchange="fetchStudents(this.value)">
                        <option value="">เลือกสถานประกอบการ</option>
                        <?php
                        include("../connection.php");
                        $sql = "SELECT o.organization_id, 
                                        o.organization_name, 
                                        GROUP_CONCAT(i.intern_id) AS intern_ids
                                    FROM organization o
                                    JOIN intern i ON o.organization_id = i.organization_id
                                    GROUP BY o.organization_id, o.organization_name";


                        $query = mysqli_query($conn, $sql);
                        if ($query) {
                            while ($result = mysqli_fetch_assoc($query)) {
                        ?>
                                <option value="<?php echo htmlspecialchars($result['organization_id']); ?>">
                                    <?php echo htmlspecialchars($result['organization_name']); ?>
                                </option>
                        <?php
                            }
                        } else {
                            echo "<option value=''>Error loading organizations</option>";
                        }
                        ?>
                    </select>
                </div>


                <div class="form-group">
                    <label for="startDate">โปรดเลือกวันที่เข้าร่วมสหกิจ:</label>
                    <input type="date" id="startDate" onchange="setMinEndDate()" placeholder="กรุณาเลือกวันที่">
                </div>

                <div class="form-group">
                    <label for="endDate">โปรดเลือกวันที่สิ้นสุดการสหกิจ:</label>
                    <input type="date" id="endDate" placeholder="กรุณาเลือกวันที่">
                </div>


                <div class="form-group">
                    <label for="docNum">โปรดระบุเลขหนังสือ:</label>
                    <input type="text" id="docNum" placeholder="อว. 158/2567">
                </div>

                <div class="form-group">
                    <label for="supervisor">โปรดเลือกผู้ลงนาม:</label>
                    <select id="supervisor" name="supervisor" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                        <option value="">เลือกผู้ลงนาม</option>
                        <?php
                        include("../connection.php");
                        $sql = "SELECT type_id, type_name FROM type_document";
                        $query = mysqli_query($conn, $sql);

                        if ($query) {
                            // วนลูปดึงข้อมูลและแสดงผลในรูปแบบของ <option>
                            while ($result = mysqli_fetch_assoc($query)) {
                                echo '<option value="' . htmlspecialchars($result['type_id']) . '">' . htmlspecialchars($result['type_name']) . '</option>';
                            }
                        } else {
                            echo '<option value="">Error loading supervisors</option>';
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <button type="submit">บันทึกข้อมูล</button>
                </div>
            </form>
        </div>

        <!-- ตารางรายชื่อนิสิต -->
        <div class="table-section">
            <h2>รายชื่อนิสิตที่เข้าร่วมฝึกสหกิจบริษัท A</h2>
            <table>
                <thead>
                    <tr>
                        <th>รหัสนิสิต</th>
                        <th>ชื่อ - สกุล</th>
                        <th>หลักสูตร</th>
                    </tr>
                </thead>
                <tbody id="studentTableBody">
                    <!-- Student data will be populated here via AJAX -->
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function setMinEndDate() {
            const startDateInput = document.getElementById('startDate');
            const endDateInput = document.getElementById('endDate');

            // Set the minimum value of endDate to the selected startDate
            if (startDateInput.value) {
                endDateInput.min = startDateInput.value;
            } else {
                endDateInput.min = ''; // Reset if no start date is selected
            }
        }

        function fetchStudents(organizationId) {
            $.ajax({
                url: 'ajax/fetch_students.php', // URL of the PHP script
                type: 'POST',
                data: {
                    organization_id: organizationId
                },
                success: function(data) {
                    $('#studentTableBody').html(data); // Update the table body with fetched data
                },
                error: function() {
                    alert('Error loading students.');
                }
            });
        }
    </script>

</body>

</html>
<?php
// Connection to the database
include("../connection.php");

// Start the session and ensure the user is logged in
session_start();

if (!isset($_SESSION['UserID'])) {
    echo "<script>
        alert('กรุณาล็อกอินก่อนใช้งาน');
        window.location.href = '../index.php';
    </script>";
    exit();
}

// Validate the incoming data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize inputs
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $startdate = mysqli_real_escape_string($conn, $_POST['startdate']);
    $enddate = mysqli_real_escape_string($conn, $_POST['enddate']);
    $docnum = mysqli_real_escape_string($conn, $_POST['docnum']);
    $userid = $_SESSION['UserID'];

    // Make sure none of the important fields are empty
    if (empty($id) || empty($startdate) || empty($enddate) || empty($doc_num)) {
        echo "<script>
            alert('กรุณากรอกข้อมูลให้ครบถ้วน');
            window.history.back();
        </script>";
        exit();
    }

    // Insert the data into the database (adjust according to your database structure)
    $sql = "INSERT INTO document (id, startdate, enddate, docnumber)
            VALUES ('$user_id', '$id', '$startdate', '$enddate', '$docnum')";

    if (mysqli_query($conn, $sql)) {
        // Redirect back to the form or to a success page
        echo "<script>
            alert('บันทึกข้อมูลสำเร็จ');
            window.location.href = 'index.php'; // Adjust the redirection
        </script>";
    } else {
        // Handle errors
        echo "<script>
            alert('เกิดข้อผิดพลาดในการบันทึกข้อมูล');
            window.history.back();
        </script>";
    }
} else {
    // echo "<script>
    //     alert('Invalid request');
    //     window.history.back();
    // </script>";
}


?>