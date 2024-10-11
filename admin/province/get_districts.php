<?php
include '../../connection.php';

if (isset($_POST['amphure_id'])) {
    $amphure_id = $_POST['amphure_id'];

    $query = "SELECT * FROM thai_tambons WHERE amphure_id = '$amphure_id' ORDER BY name_th ASC";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        echo '<option value="">กรุณาเลือกตำบล</option>';
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<option value="' . $row['id'] . '">' . $row['name_th'] . '</option>';
        }
    } else {
        echo '<option value="">ไม่มีข้อมูลตำบล</option>';
    }
}
?>
