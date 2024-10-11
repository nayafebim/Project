<?php
include('../../connection.php');

if (isset($_POST['region_id'])) {
    $region_id = $_POST['region_id'];
    $query = mysqli_query($conn, "SELECT * FROM thai_provinces WHERE geography_id = '$region_id'");
    echo '<option value="">กรุณาเลือกจังหวัด</option>';
    while ($result = mysqli_fetch_assoc($query)) {
        echo '<option value="' . $result['id'] . '">' . $result['name_th'] . '</option>';
    }
}
?>
