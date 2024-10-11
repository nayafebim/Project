<?php
include('../../connection.php');

header('Content-Type: application/json');

if (isset($_POST['new_type_name'])) {
    $type_name = $_POST['new_type_name'];

    // เริ่มต้น transaction เพื่อป้องกันการเพิ่มข้อมูลซ้ำ
    $conn->begin_transaction();

    try {
        // Prepare statement to check if type_name already exists
        $check_stmt = $conn->prepare("SELECT * FROM type_organization WHERE type_name = ?");
        $check_stmt->bind_param('s', $type_name);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            // ถ้าข้อมูลมีอยู่แล้วให้คืนค่า error
            $response = [
                'success' => false,
                'message' => 'ชื่อประเภทนี้มีอยู่แล้วในระบบ'
            ];
        } else {
            // ถ้าไม่มีข้อมูล ให้ทำการเพิ่มข้อมูลใหม่
            $insert_stmt = $conn->prepare("INSERT INTO type_organization (type_name) VALUES (?)");
            $insert_stmt->bind_param('s', $type_name);

            if ($insert_stmt->execute()) {
                // ส่งข้อมูลสำเร็จ พร้อมกับคืนค่า id ที่เพิ่งถูกเพิ่ม
                $response = [
                    'success' => true,
                    'type_id' => $insert_stmt->insert_id
                ];

                // ยืนยันการเพิ่มข้อมูล
                $conn->commit();
            } else {
                // ถ้าเกิดข้อผิดพลาดในการเพิ่มข้อมูลให้ rollback
                $conn->rollback();
                $response = [
                    'success' => false,
                    'message' => 'ไม่สามารถเพิ่มประเภทได้ กรุณาลองใหม่อีกครั้ง'
                ];
            }
        }

        $check_stmt->close();
        $insert_stmt->close();
    } catch (Exception $e) {
        // ถ้ามีข้อผิดพลาดให้ rollback และแสดงข้อผิดพลาด
        $conn->rollback();
        $response = [
            'success' => false,
            'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
        ];
    }

    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    $conn->close();
}
?>
