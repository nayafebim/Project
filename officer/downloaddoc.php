<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ตรวจสอบเอกสารโครงการสหกิจศึกษา</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="anonymous" referrerpolicy="no-referrer" />
    <script>
        function fetchStudents(courseId) {
            const organizationId = document.getElementById('organization_id').value;

            // ตรวจสอบให้แน่ใจว่า organizationId และ courseId ไม่เป็นค่าว่าง
            if (!organizationId || !courseId) {
                document.getElementById('studentTableBody').innerHTML = "<tr><td colspan='4' class='text-center py-2'>กรุณาเลือกหลักสูตรและตรวจสอบ ID องค์กร</td></tr>";
                return;
            }

            const xhr = new XMLHttpRequest();
            xhr.open('GET', 'ajax/get_students.php?course_id=' + courseId + '&organization_id=' + organizationId, true);
            xhr.onload = function() {
                if (this.status === 200) {
                    document.getElementById('studentTableBody').innerHTML = this.responseText;
                } else {
                    document.getElementById('studentTableBody').innerHTML = "<tr><td colspan='4' class='text-center py-2'>เกิดข้อผิดพลาดในการดึงข้อมูล</td></tr>";
                }
            };
            xhr.onerror = function() {
                document.getElementById('studentTableBody').innerHTML = "<tr><td colspan='4' class='text-center py-2'>เกิดข้อผิดพลาดในการเชื่อมต่อ</td></tr>";
            };
            xhr.send();
        }
    </script>
</head>

<body class="bg-gradient-to-br from-green-700 to-teal-500 text-white min-h-screen">

    <!-- Header -->
    <?php include "header.php"; ?>
    <!-- Main Content -->
    <main class="p-6">
        <div class="bg-white text-black rounded-lg shadow-lg p-6">
            <h2 class="text-center text-2xl font-bold mb-6 text-green-700">ตรวจสอบเอกสารและจัดการข้อมูลนิสิตโครงการสหกิจศึกษา</h2>

            <div class="overflow-x-auto">
                <input type="hidden" id="organization_id" value="<?= isset($_GET['organization_id']) ? htmlspecialchars($_GET['organization_id']) : '' ?>">

                <div class="mb-4">
                    <label for="course_id" class="block text-green-700">เลือกหลักสูตร</label>
                    <select id="course_id" name="course_id" onchange="fetchStudents(this.value)" class="border border-green-700 rounded px-4 py-2 w-full">
                        <option value="">เลือกหลักสูตร</option>
                        <?php
                        $OrganizationID = null;
                        if (isset($_GET["organization_id"])) {
                            $OrganizationID = $_GET["organization_id"];
                        }
                        // Fetch courses for the dropdown
                        include("../connection.php");
                        // $course_sql = "SELECT course_id, course_name FROM course";
                        $course_sql = "SELECT course.*
                                        FROM intern 
                                        JOIN organization ON intern.organization_id = organization.organization_id
                                        JOIN student ON intern.student_id = student.student_id
                                        JOIN course ON student.course = course.course_id
                                        LEFT JOIN document ON intern.intern_id = document.Intern_ID
                                        WHERE intern.status_final = 1 AND document.Organization_ID = '$OrganizationID'";
                        $course_result = $conn->query($course_sql);

                        while ($course = $course_result->fetch_assoc()) {
                            echo '<option value="' . htmlspecialchars($course["course_id"]) . '">' . htmlspecialchars($course["course_name"]) . '</option>';
                        }
                        ?>
                    </select>
                </div>

                <table class="min-w-full bg-white border border-green-700 mt-4">
                    <thead class="bg-green-500 text-white">
                        <tr>
                            <th class="py-2 px-4 border">ชื่อ - สกุล</th>
                            <th class="py-2 px-4 border">หลักสูตร</th>
                            <th class="py-2 px-4 border">เอกสารและผลตอบกลับ</th>
                            <th class="py-2 px-4 border">ผลการตอบกลับ</th>
                        </tr>
                    </thead>
                    <tbody id="studentTableBody">
                        <!-- Student data will be populated here -->
                    </tbody>
                </table>

                <div class="flex justify-end mt-4 space-x-2">
                    <a href="#" id="downloadLink" target="_blank" class="bg-black text-white px-4 py-2 rounded-lg">ดาวน์โหลดหนังสือ</a>
                </div>

                <script>
                    document.getElementById('downloadLink').onclick = function(event) {
                        const courseId = document.getElementById('course_id').value;
                        const organizationId = document.getElementById('organization_id').value;

                        // Check if both courseId and organizationId are selected
                        if (courseId && organizationId) {
                            this.href = 'ex-test.php?organization_id=' + organizationId + '&course_id=' + courseId;
                        } else {
                            alert('กรุณาเลือกหลักสูตรและตรวจสอบ ID องค์กร');
                            event.preventDefault(); // Prevent the link from being followed if not valid
                            this.href = '#'; // Reset href if not valid
                        }
                    };
                </script>


            </div>

        </div>
    </main>

</body>

</html>