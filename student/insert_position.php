<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่มตำแหน่งงาน</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="bg-gradient-to-br from-green-700 to-teal-500 text-white min-h-screen">
    <!-- Header -->
    <?php include "header.php"; ?>

    <!-- Main Content -->
    <main class="p-6">
        <div class="text-center mb-4">
            <h1 class="text-2xl font-bold">คณะวิทยาศาสตร์และนวัตกรรมดิจิทัล</h1>
            <h2 class="text-2xl font-bold text-white mb-4">เพิ่มตำแหน่งงานใหม่</h2>
        </div>

        <div class="bg-white text-black rounded-lg shadow-lg p-6">
            <h3 class="text-xl font-bold text-green-700 mb-4">เพิ่มตำแหน่งงาน</h3>
            <form method="POST" class="space-y-4">
                <div>
                    <label class="block mb-2 font-semibold" for="position_name">ชื่อตำแหน่งงาน</label>
                    <input id="position_name" name="position_name" type="text" class="w-full border rounded-lg px-3 py-2" required>
                </div>

                <div class="flex justify-end mt-6">
                    <button type="submit" name="insert_position" class="bg-green-600 text-white px-4 py-2 rounded-lg">
                        บันทึก
                    </button>
                </div>
            </form>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>

<?php
// Include database connection file
include('../connection.php');

if (isset($_POST['insert_position'])) {
    $position_name = mysqli_real_escape_string($conn, $_POST['position_name']);

    // Insert new position into the database
    $query = "INSERT INTO position_type (position_name) VALUES ('$position_name')";
    
    if (mysqli_query($conn, $query)) {
        echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกสำเร็จ!',
                    text: 'เพิ่มตำแหน่งงานใหม่เรียบร้อยแล้ว.',
                    confirmButtonText: 'ตกลง'
                }).then(() => {
                    window.location.href = 'insert_Intern.php'; // Replace with the page you want to redirect
                });
              </script>";
    } else {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด!',
                    text: 'ไม่สามารถบันทึกตำแหน่งงานได้: " . mysqli_error($conn) . "',
                    confirmButtonText: 'ตกลง'
                });
              </script>";
    }

    // Close the connection
    mysqli_close($conn);
}
?>
