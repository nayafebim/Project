<?php
include("../../connection.php");
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_POST['organization_id'])) {
    $organizationId = $_POST['organization_id'];
    $status = 2;

    // Query to get student information
    $sql = "SELECT intern.intern_id, student.student_id, student.student_prefix, 
                   student.student_surname, student.student_lastname, course.course_name 
            FROM intern 
            JOIN student ON intern.student_id = student.student_id 
            JOIN course ON student.course = course.course_id 
            WHERE intern.organization_id = ? AND intern.status = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ii", $organizationId, $status);
        $stmt->execute();
        $result = $stmt->get_result();

        $students = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Add each student to the array
                $students[] = $row;
            }
        }

        // Send the response as JSON
        header('Content-Type: application/json');
        echo json_encode($students);
    } else {
        echo json_encode(['error' => 'SQL prepare error: ' . $conn->error]);
    }
}
