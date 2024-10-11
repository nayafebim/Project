<?php
include("../../connection.php");

$status = 1;

// Validate organization_id and course_id
$organizationId = filter_var(isset($_GET['organization_id']) ? $_GET['organization_id'] : null, FILTER_VALIDATE_INT);
$courseId = filter_var(isset($_GET['course_id']) ? $_GET['course_id'] : null, FILTER_VALIDATE_INT);

// ตรวจสอบว่าค่าถูกต้องหรือไม่
if ($organizationId === false || $courseId === false) {
    echo "<tr><td colspan='4' class='text-center py-2'>ข้อมูลไม่ถูกต้อง กรุณาลองใหม่</td></tr>";
    exit;
}

$sql = "SELECT intern.*, organization.*, student.*, course.*, document.Intern_ID 
        FROM intern 
        JOIN organization ON intern.organization_id = organization.organization_id
        JOIN student ON intern.student_id = student.student_id
        JOIN course ON student.course = course.course_id
        LEFT JOIN document ON intern.intern_id = document.Intern_ID
        WHERE intern.status_final = ? 
          AND intern.organization_id = ? 
          AND student.course = ?
        ORDER BY student.student_id";

if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("iii", $status, $organizationId, $courseId);

    if ($stmt->execute()) {
        $result = $stmt->get_result();

        // Output student data
        if ($result->num_rows > 0) {
            while ($student = $result->fetch_assoc()) {
                outputStudentRow($student);
            }
        } else {
            echo "<tr><td colspan='4' class='text-center py-2'>ไม่พบข้อมูลนักเรียน</td></tr>";
        }
    } else {
        // Output SQL error if the statement fails
        echo "<tr><td colspan='4' class='text-center py-2'>เกิดข้อผิดพลาดในการเรียกข้อมูล: " . htmlspecialchars($stmt->error) . "</td></tr>";
    }

    $stmt->close();
} else {
    // Log error for debugging
    error_log("Error preparing statement: " . $conn->error);
    echo "<tr><td colspan='4' class='text-center py-2'>เกิดข้อผิดพลาดในการเตรียมคำสั่ง SQL</td></tr>";
}

// Function to output student row
function outputStudentRow($student)
{
    echo '<tr class="border-t">';
    echo '<td class="py-2 px-4 border">' . htmlspecialchars($student["student_prefix"] . " " . $student["student_surname"] . " " . $student["student_lastname"]) . '</td>';
    echo '<td class="py-2 px-4 border">' . htmlspecialchars($student["course_name"]) . '</td>';
    echo '<td class="py-2 px-4 border"><a href="fillstd.php?student_id=' . htmlspecialchars($student['student_id']) . '" class="bg-orange-400 text-white px-4 py-2 rounded">ตรวจสอบเอกสาร</a></td>';
    echo '<td class="py-2 px-4 border"><button class="bg-green-500 text-white px-4 py-2 rounded" onclick="uploadResponse(' . htmlspecialchars($student['student_id']) . ')">อัปโหลดการตอบกลับ</button></td>';
    echo '</tr>';
}
