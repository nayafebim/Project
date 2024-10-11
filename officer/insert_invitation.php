<?php
// เชื่อมต่อกับฐานข้อมูล
include('../connection.php');

session_start();
if (!isset($_SESSION['UserID'])) {
    ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "กรุณาล็อกอินก่อนใช้งาน!!",
            timer: 3000,
            showConfirmButton: false
        }).then(() => {
            document.location.href = '../index.php'; // เปลี่ยนไปที่หน้าเข้าสู่ระบบ
        });
    </script>
    <?php
    exit();
}

$User_ID = $_SESSION['UserID'];

// ดึงข้อมูลการตอบรับจากฐานข้อมูล
$sql = "SELECT * FROM acceptance WHERE User_ID = '$User_ID'";
$result = mysqli_query($conn, $sql);
$acceptance = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แบบตอบรับจากสถานประกอบการ</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="bg-gradient-to-br from-green-700 to-teal-500 text-white font-sans min-h-screen">
    <!-- header -->
    <?php include "header.php"; ?>

    <main class="p-4">
        <section class="bg-white rounded-lg shadow-lg p-6 text-black">
            <h1 class="text-2xl font-bold mb-2 text-center text-green-700">แบบตอบรับจากสถานประกอบการ</h1>

            <form method="POST" action="acceptance.php">
                <div class="mb-4">
                    <label for="company_name" class="block font-semibold">ชื่อสถานประกอบการ</label>
                    <input type="text" name="company_name" id="company_name" class="border border-gray-300 rounded px-4 py-2 w-full" value="<?php echo htmlspecialchars($acceptance['company_name'] ?? ''); ?>" required>
                </div>
                
                <div class="mb-4">
                    <label for="acceptance_status" class="block font-semibold">สถานะการตอบรับ</label>
                    <select name="acceptance_status" id="acceptance_status" class="border border-gray-300 rounded px-4 py-2 w-full" required>
                        <option value="">เลือกสถานะ</option>
                        <option value="1" <?php echo (isset($acceptance['status']) && $acceptance['status'] == 1) ? 'selected' : ''; ?>>รับเข้าร่วม</option>
                        <option value="0" <?php echo (isset($acceptance['status']) && $acceptance['status'] == 0) ? 'selected' : ''; ?>>ไม่รับเข้าร่วม</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="comments" class="block font-semibold">หมายเหตุ</label>
                    <textarea name="comments" id="comments" class="border border-gray-300 rounded px-4 py-2 w-full" rows="4"><?php echo htmlspecialchars($acceptance['comments'] ?? ''); ?></textarea>
                </div>

                <div class="flex justify-center mt-4">
                    <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg">บันทึก</button>
                </div>
            </form>

            <?php
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                // รับค่าจากแบบฟอร์ม
                $company_name = mysqli_real_escape_string($conn, $_POST['company_name']);
                $acceptance_status = mysqli_real_escape_string($conn, $_POST['acceptance_status']);
                $comments = mysqli_real_escape_string($conn, $_POST['comments']);

                // บันทึกหรือปรับปรุงข้อมูลการตอบรับในฐานข้อมูล
                if (empty($acceptance['id'])) {
                    // ถ้ายังไม่มีข้อมูลให้เพิ่มใหม่
                    $sqlInsert = "INSERT INTO acceptance (User_ID, company_name, status, comments) VALUES ('$User_ID', '$company_name', '$acceptance_status', '$comments')";
                    if (mysqli_query($conn, $sqlInsert)) {
                        echo "<script>
                            Swal.fire({
                                icon: 'success',
                                title: 'สำเร็จ!',
                                text: 'บันทึกข้อมูลการตอบรับสำเร็จ!',
                                timer: 3000,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.reload();
                            });
                        </script>";
                    } else {
                        echo "<script>
                            Swal.fire({
                                icon: 'error',
                                title: 'เกิดข้อผิดพลาด',
                                text: 'ไม่สามารถบันทึกข้อมูลได้: " . mysqli_error($conn) . "',
                            });
                        </script>";
                    }
                } else {
                    // ถ้ามีข้อมูลอยู่แล้วให้ปรับปรุง
                    $sqlUpdate = "UPDATE acceptance SET company_name = '$company_name', status = '$acceptance_status', comments = '$comments' WHERE User_ID = '$User_ID'";
                    if (mysqli_query($conn, $sqlUpdate)) {
                        echo "<script>
                            Swal.fire({
                                icon: 'success',
                                title: 'สำเร็จ!',
                                text: 'ปรับปรุงข้อมูลการตอบรับสำเร็จ!',
                                timer: 3000,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.reload();
                            });
                        </script>";
                    } else {
                        echo "<script>
                            Swal.fire({
                                icon: 'error',
                                title: 'เกิดข้อผิดพลาด',
                                text: 'ไม่สามารถปรับปรุงข้อมูลได้: " . mysqli_error($conn) . "',
                            });
                        </script>";
                    }
                }
            }
            ?>
        </section>
    </main>
</body>

</html>
