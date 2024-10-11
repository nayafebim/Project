<?php
include '../../connection.php';

if (isset($_POST['province_id'])) {
    $province_id = $_POST['province_id'];

    $query = "SELECT * FROM thai_amphures WHERE province_id = '$province_id' ORDER BY name_th ASC";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        echo '<option value="">กรุณาเลือกอำเภอ</option>';
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<option value="' . $row['id'] . '">' . $row['name_th'] . '</option>';
        }
    } else {
        echo '<option value="">ไม่มีข้อมูลอำเภอ</option>';
    }
}
?>
