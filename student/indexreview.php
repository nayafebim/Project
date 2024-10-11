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

// Get the selected academic year from the query string
$academic_year = isset($_GET['academic_year']) ? intval($_GET['academic_year']) - 543 : null; // Convert to Gregorian year
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ผลการเข้าร่วมโครงการสหกิจศึกษา</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script>
        function handleSearchInput() {
            const searchQuery = document.getElementById('search-input').value.toLowerCase();
            const rows = document.querySelectorAll('#data-table tbody tr');

            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                const match = Array.from(cells).some(cell => cell.textContent.toLowerCase().includes(searchQuery));
                row.style.display = match ? '' : 'none';
            });
        }

        function handleCourseChange() {
            const selectedYear = document.querySelector('select[name="academic_year"]').value;
            const selectedCourse = document.querySelector('select[name="course_id"]').value;

            let url = window.location.pathname + '?';
            if (selectedYear) url += 'academic_year=' + selectedYear;
            if (selectedCourse) url += '&course_id=' + selectedCourse;

            document.location.href = url;
        }
    </script>
</head>

<body class="bg-gradient-to-br from-green-700 to-teal-500 min-h-screen text-white">
    <!-- Header -->
    <?php include "header.php" ?>

    <!-- Main Content -->
    <main class="p-4">
        <section class="bg-white rounded-lg shadow-lg p-6 text-green-900">
            <h1 class="text-2xl font-bold mb-2 text-center text-green-700">ผลการเข้าร่วมโครงการสหกิจศึกษา</h1>
            <h2 class="text-xl font-semibold mb-4 text-center">คณะวิทยาศาสตร์และนวัตกรรมดิจิทัล</h2>
            <h3 class="text-center text-green-700">มหาวิทยาลัยทักษิณ วิทยาเขตพัทลุง</h3>

            <!-- <div class="grid grid-cols-1 md:grid-cols-3 gap-4 my-6">
                <button class="bg-yellow-400 text-white py-3 px-4 rounded-lg text-center">ผลการเลือกสถานที่ฝึกงานในประเทศ</button>
                <button class="bg-orange-400 text-white py-3 px-4 rounded-lg text-center">ผลการเลือกสถานที่ประกอบการ</button>
                <button class="bg-green-400 text-white py-3 px-4 rounded-lg text-center">จังหวัดที่นิสิตนิยมเลือกฝึกสหกิจศึกษา</button>
            </div> -->

            <div class="bg-green-100 rounded-lg p-4 shadow-inner">
                <h3 class="text-lg font-semibold mb-4">รีวิวสถานประกอบการจากนิสิต</h3>
                <div class="flex justify-between mb-4">
                    <div class="relative flex-grow mr-2">
                        <input type="text" id="search-input" placeholder="ค้นหาสถานประกอบการ" class="pl-10 pr-4 py-2 border rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-green-500" onkeyup="handleSearchInput()">
                        <i class="fa-solid fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500"></i>
                    </div>
                    <select name="academic_year" class="border px-4 py-2 rounded-md" onchange="handleCourseChange()">
                        <option value="">เลือกปีการศึกษา</option>
                        <?php
                        include("../connection.php");

                        // Query to get distinct years from review_date
                        $sql = "SELECT DISTINCT YEAR(review_date) AS year FROM review ORDER BY year DESC";
                        $result = mysqli_query($conn, $sql);

                        while ($row = mysqli_fetch_assoc($result)) {
                            $year = $row['year'] + 543; // Convert to Thai Buddhist year
                            $selected = (isset($_GET['academic_year']) && $_GET['academic_year'] == $year) ? 'selected' : '';
                            echo "<option value=\"{$year}\" $selected>ประจำปีการศึกษา {$year}</option>";
                        }
                        ?>
                    </select>

                    <?php
                    include("../connection.php");

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
                    ?>
                    <select name="course_id" class="pl-3 pr-10 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" onchange="handleCourseChange()">
                        <option value="">เลือกหลักสูตร</option>
                        <?php foreach ($courses as $course): ?>
                            <option value="<?php echo htmlspecialchars($course['course_id']); ?>" <?php echo isset($_GET['course_id']) && $_GET['course_id'] == $course['course_id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($course['course_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <table id="data-table" class="w-full text-left bg-white rounded-lg shadow-lg">
                    <thead>
                        <tr class="bg-green-200">
                            <th class="border px-4 py-2">สถานประกอบการ</th>
                            <th class="border px-4 py-2">รีวิวโดย</th>
                            <th class="border px-4 py-2">วันที่</th>
                            <th class="border px-4 py-2">อ่านรีวิว</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include("../connection.php");

                        $sql = "SELECT review.*, organization.*, student.*, course.* 
                                FROM review 
                                JOIN organization ON review.organization_id = organization.organization_id
                                JOIN student ON review.student_id = student.student_id
                                JOIN course ON student.course = course.course_id
                                WHERE 1=1";  // Add default WHERE condition

                        // กรองตามปีการศึกษา (academic_year)
                        if (isset($_GET['academic_year']) && $_GET['academic_year'] !== "") {
                            $academic_year = intval($_GET['academic_year']) - 543; // Convert back to Gregorian year
                            $sql .= " AND YEAR(review_date) = $academic_year";
                        }

                        // กรองตามหลักสูตร (course)
                        if (isset($_GET['course_id']) && $_GET['course_id'] !== "") {
                            $course_id = intval($_GET['course_id']);
                            $sql .= " AND student.course = $course_id";
                        }

                        $sql .= " ORDER BY review.review_date DESC";
                        $query = mysqli_query($conn, $sql);


                        while ($result = mysqli_fetch_array($query)) {
                            $date = $result["review_date"];
                            $review_id = $result['review_id'];
                            $student_id = $result['student_id'];

                            // แปลงรูปแบบให้เป็นรูปแบบ timestamp
                            $timestamp = strtotime($date);
                            include_once('../assets/Thaidate/Thaidate.php');
                            include_once('../assets/Thaidate/thaidate-functions.php');

                            $thaiDate = thaidate('วันl ที่ j F พ.ศ. Y เวลา H:i น.', $timestamp); ?>

<tr>
                                <td class="border px-4 py-2"><?= $result["organization_name"]; ?></td>
                                <td class="border px-4 py-2">
                                    <p><?= $result["student_prefix"] . " " . $result["student_surname"] . " " . $result["student_lastname"] ?> รหัสนิสิต <?= $result["student_id"]; ?></p>
                                    <p><?= $result["course_name"]; ?></p>
                                </td>
                                <td class="border px-4 py-2"><?= $thaiDate; ?></td>
                                <td class="border px-4 py-2 text-center">
                                    <a href="review.php?review_id=<?= $review_id; ?>" class="bg-blue-500 text-white px-4 py-2 rounded-md"><i class="fa-solid fa-file-invoice"></i> อ่านรีวิว</a>
                                    <?php
                                    if ($student_id == $User_ID) { ?>
                                        <a href="edit_review.php?review_id=<?= $review_id; ?>" class="bg-yellow-500 text-white px-4 py-2 rounded-md"><i class="fas fa-edit"></i> แก้ไข</a>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                


                <?php
                include("../connection.php");
                $sql = "SELECT student_id FROM review WHERE student_id = $User_ID";
                $result = mysqli_query($conn, $sql);
                $row = mysqli_num_rows($result);
                if (!$row) { ?>
                    <div class="flex justify-center mt-6">
                        <a href="revieworganize.php?student_id=<?= $User_ID; ?>" class="bg-yellow-500 text-white px-6 py-2 rounded-lg">เพิ่มรีวิวสถานประกอบการ</a>
                    </div>
                <?php
                }
                ?>
            </div>
        </section>
    </main>

    <!-- <footer class="bg-green-900 text-white text-center py-4 mt-8">
        <p>© 2024 มหาวิทยาลัยทักษิณ วิทยาเขตพัทลุง</p>
    </footer> -->
</body>

</html>