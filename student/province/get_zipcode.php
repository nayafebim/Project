<?php
include '../../connection.php';

if (isset($_POST['district_id'])) {
    $district_id = $_POST['district_id'];
    $query = "SELECT zip_code FROM thai_tambons WHERE id = '$district_id'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    
    echo $row['zip_code'];
}
?>
