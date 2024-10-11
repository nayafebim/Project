<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สถานะเอกสารการเข้าร่วมโครงการสหกิจศึกษา</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="anonymous" referrerpolicy="no-referrer" />
    <script>
        function handleCourseChange() {
            document.getElementById('filter-form').submit();
        }

        function handleSearchInput() {
            const searchQuery = document.getElementById('search-input').value.toLowerCase();
            const rows = document.querySelectorAll('#data-table tbody tr');

            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                const match = Array.from(cells).some(cell => cell.textContent.toLowerCase().includes(searchQuery));
                row.style.display = match ? '' : 'none';
            });
        }
    </script>
</head>

<body class="bg-gradient-to-br from-green-700 to-teal-500 text-white font-sans min-h-screen">
    <!-- Navbar -->
    <?php include "header.php" ?>

    <main class="p-4">
        <section class="bg-white rounded-lg shadow-lg p-6 text-black max-w-[1200px] mx-auto">
            <h1 class="text-2xl font-bold mb-4 text-center text-green-700">สถานะเอกสารการเข้าร่วมโครงการสหกิจศึกษาของนิสิต</h1>
            <h2 class="text-xl font-semibold mb-6 text-center">คณะวิทยาศาสตร์และนวัตกรรมดิจิทัล</h2>

            <form id="filter-form" method="GET" action="">
                <div class="mb-4">
                    <h3 class="text-lg font-semibold mb-2">ตรวจสอบและอนุมัติเอกสารในการเข้าร่วมโครงการสหกิจศึกษา</h3>
                    <div class="flex justify-between items-center">
                        <div class="relative w-1/3">
                            <input type="text" id="search-input" placeholder="ค้นหาชื่อ" class="pl-10 pr-4 py-2 border rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-green-500" onkeyup="handleSearchInput()">
                            <i class="fa-solid fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500"></i>
                        </div>
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
                </div>
            </form>

            <table id="data-table" class="w-full text-left bg-white rounded-lg shadow-lg mt-6">
                <thead>
                    <tr class="bg-green-200 text-green-700">
                        <th class="border"></th>
                        <th class="border px-4 py-2">ชื่อ - สกุล</th>
                        <th class="border px-4 py-2">หลักสูตร</th>
                        <th class="border px-4 py-2">เวลาดำเนินการ</th>
                        <th class="border px-4 py-2">สถานะเอกสาร</th>
                        <th class="border px-4 py-2">จัดการข้อมูล</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // SQL Query to join `intern`, `student`, and `course` tables
                    $course_id = isset($_GET['course_id']) ? $_GET['course_id'] : '';

                    $sql = "
                            SELECT i.*, s.student_prefix, s.student_surname, s.student_lastname, s.course, c.course_name
                            FROM `intern` i
                            JOIN `student` s ON i.student_id = s.student_id
                            JOIN `course` c ON s.course = c.course_id
                            WHERE ('$course_id' = '' OR s.course = '$course_id')
                            ORDER BY i.student_id
                        ";

                    $query = mysqli_query($conn, $sql);

                    // Function to get status text based on status value
                    function getStatusText($status)
                    {
                        switch ($status) {
                            case 1:
                                return '<span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-bold bg-sky-600 text-white">ยื่นเอกสารสำเร็จ</span>';
                            case 2:
                                return '<span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-bold bg-green-500 text-white">อาจารย์เห็นชอบการเข้าร่วม</span>';
                            case 3:
                                return '<span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-bold bg-red-600 text-white">อาจารย์ไม่เห็นชอบการเข้าร่วม</span>';
                            case 4:
                                return '<span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-bold bg-green-600 text-white">เจ้าหน้าที่ทำหนังสือขอความอนุเคราะห์</span>';
                            default:
                                return '<span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-bold bg-orage-400 text-white">ผิดพลาด!!</span>';
                        }
                    }

                    // Function to format datetime for Thailand
                    function formatDatetime($datetime)
                    {
                        // Create a DateTime object from the input
                        $date = new DateTime($datetime);
                        // Set the timezone to Bangkok
                        $date->setTimezone(new DateTimeZone('Asia/Bangkok'));

                        // Format the date and time in the Thai Buddhist calendar
                        $formattedDate = $date->format('d/m/Y เวลา H:i น.');

                        // Convert the year to the Buddhist calendar (adding 543 years)
                        $year = $date->format('Y') + 543;
                        $formattedDate = str_replace(date('Y'), $year, $formattedDate);

                        return $formattedDate;
                    }

                    while ($result = mysqli_fetch_array($query)) { ?>
                        <tr>
                            <td class=""><img src='../assets/img/student/<?php echo htmlspecialchars($result["student_id"]); ?>.jpg' width="60" height="auto"></td>
                            <td class="border px-4 py-2">
                                <?php echo htmlspecialchars($result["student_prefix"]) . '' . htmlspecialchars($result["student_surname"]) . ' ' . htmlspecialchars($result["student_lastname"]); ?>
                            </td>
                            <td class="border px-4 py-2"><?php echo htmlspecialchars($result["course_name"]); ?></td>
                            <td class="border px-4 py-2"><?php echo formatDatetime($result["datetime"]); ?></td>
                            <td class="border px-4 py-2"><?php echo getStatusText($result["status"]); ?></td>
                            <td class="flex justify-center items-center py-5">
                                <a href="approve.php?student_id=<?= $result["student_id"]; ?>" class="py-2 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-gray-800 text-white hover:bg-gray-900 focus:outline-none focus:bg-gray-900 disabled:opacity-50 disabled:pointer-events-none">แสดง <i class="fas fa-eye"></i></a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </section>
    </main>
</body>

</html>
