<?php
include("../../connection.php");

if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $sql = "SELECT * FROM organization 
            WHERE organization_id LIKE '%$search%' 
            OR organization_name LIKE '%$search%' 
            OR organization_address LIKE '%$search%'"; // Adjust based on your fields
    $query = mysqli_query($conn, $sql);

    if (mysqli_num_rows($query) > 0) {
        echo '<ul class="border border-gray-300 rounded-lg">';
        while ($result = mysqli_fetch_assoc($query)) {
            echo '<li class="px-4 py-2 hover:bg-gray-100 cursor-pointer" onclick="selectOrganization(\'' . htmlspecialchars($result["organization_name"]) . '\')">' . htmlspecialchars($result["organization_name"]) . ' (ID: ' . htmlspecialchars($result["organization_id"]) . ')</li>';
        }
        echo '</ul>';
    } else {
        echo '<p class="px-4 py-2">ไม่พบสถานประกอบการ</p>'; // No organizations found
    }
}
?>
