<?php
include("../../connection.php");

if (isset($_POST['type_id'])) {
    $type_id = $_POST['type_id'];

    // ป้องกัน SQL Injection
    $stmt = $conn->prepare("SELECT organization_id, organization_name FROM organization WHERE type_organization = ?");
    $stmt->bind_param("s", $type_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $organizations = [];
    while ($row = $result->fetch_assoc()) {
        $organizations[] = $row;
    }

    // ส่งข้อมูลกลับในรูปแบบ JSON
    echo json_encode(['organizations' => $organizations]);
} else {
    echo json_encode(['error' => 'Invalid type_id']);
}
?>
