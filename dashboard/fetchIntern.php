<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('../connection.php');

// Modify query to fetch all provinces with intern count, even if no interns exist in that province
$sql = "
SELECT thai_provinces.name_th AS province_th, 
       thai_provinces.name_en AS province_en, 
       COUNT(intern.intern_id) AS num_interns,
       GROUP_CONCAT(CONCAT(student.student_prefix, ' ', student.student_surname, ' ', student.student_lastname) ORDER BY student.student_id SEPARATOR '|') AS intern_names,
       GROUP_CONCAT(course.course_name ORDER BY student.student_id SEPARATOR '|') AS intern_courses,
       GROUP_CONCAT(student.year ORDER BY student.student_id SEPARATOR '|') AS intern_years,
       GROUP_CONCAT(student.student_id ORDER BY student.student_id SEPARATOR '|') AS intern_ids,
       GROUP_CONCAT(organization.organization_name ORDER BY student.student_id SEPARATOR '|') AS organization_names
FROM thai_provinces
LEFT JOIN organization ON thai_provinces.id = organization.province
LEFT JOIN intern ON organization.organization_id = intern.organization_id
LEFT JOIN student ON intern.student_id = student.student_id
LEFT JOIN course ON student.course = course.course_id
GROUP BY thai_provinces.name_th, thai_provinces.name_en
ORDER BY thai_provinces.name_th
;

";

$result = $conn->query($sql);

$data = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // สร้าง array ว่างเพื่อเก็บรายละเอียดนักศึกษา
        $interns = array();

        // ตรวจสอบว่ามีข้อมูลนักศึกษาหรือไม่
        if ($row['intern_names']) {
            // Splitting student details
            $names = explode('|', $row['intern_names']);
            $courses = explode('|', $row['intern_courses']);
            $years = explode('|', $row['intern_years']);
            $ids = explode('|', $row['intern_ids']);
            $organization_names = explode('|', $row['organization_names']); // Adding organization names

            // Loop to store intern data
            for ($i = 0; $i < count($names); $i++) {
                $interns[] = array(
                    'name' => $names[$i],
                    'course' => $courses[$i],
                    'year' => $years[$i],
                    'student_id' => $ids[$i],
                    'organization_name' => $organization_names[$i] // Adding organization name to the output
                );
            }
        }


        // เก็บข้อมูลจังหวัดและข้อมูลนักศึกษาใน array สุดท้าย
        $data[] = array(
            'province_th' => $row['province_th'],
            'province_en' => $row['province_en'],
            'num_interns' => $row['num_interns'],
            'interns' => $interns
        );
    }
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($data);
