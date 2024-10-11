<?php
header('Content-Type: application/json');
include('../../connection.php');

// รับค่า organization_id ที่ส่งมาจากการเรียก AJAX
$organization_id = $_POST['organization_id'];

// ตรวจสอบการเชื่อมต่อฐานข้อมูล
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ใช้ prepared statement เพื่อความปลอดภัย
$sql = "
    SELECT o.*, 
           t.type_name, 
           p.name_th AS province_name, 
           a.name_th AS amphure_name, 
           b.name_th AS tambon_name
    FROM organization o
    JOIN type_organization t ON o.type_organization = t.type_id
    JOIN thai_provinces p ON o.province = p.id
    JOIN thai_amphures a ON o.amphure = a.id
    JOIN thai_tambons b ON o.district = b.id
    WHERE o.organization_id = ?
";

if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("s", $organization_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $organizationData = $result->fetch_assoc();
    echo json_encode($organizationData);
    $stmt->close();
} else {
    echo json_encode(["error" => "Error preparing statement: " . $conn->error]);
}

$conn->close();
?>
