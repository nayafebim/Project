<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ดึงข้อมูลจากไฟล์ CSV</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-br from-green-700 to-teal-500 min-h-screen text-white">
    <?php include "header.php" ?>
    <div class="p-4">
        <div class="text-center mb-4 border-b border-gray-600">
            <h1 class="text-2xl font-bold mb-2">แสดงข้อมูลจากไฟล์ CSV</h1>
            <p class="mb-2">โปรดแนบไฟล์ CSV เพื่อดึงข้อมูล</p>
        </div>

        <!-- File Upload Section -->
        <div class="bg-white text-black p-6 rounded-lg shadow-lg">
            <h3 class="text-xl font-bold text-green-700 mb-4">นำเข้าข้อมูล</h3>

            <form action="" method="POST" enctype="multipart/form-data" class="flex space-x-4 items-center">
                <input type="file" name="csv_file" class="border p-2 rounded-lg flex-grow" accept=".csv">
                <button type="submit" name="submit" class="bg-gray-700 text-white py-2 px-4 rounded-lg">แทรกไฟล์</button>
            </form>
        </div>

        <?php
        if (isset($_POST['submit'])) {
            // ตรวจสอบว่ามีไฟล์ที่อัปโหลดและเป็น CSV
            if (isset($_FILES['csv_file']) && $_FILES['csv_file']['error'] === 0) {
                $csvFile = fopen($_FILES['csv_file']['tmp_name'], 'r');

                // ข้ามแถวแรก (header) ถ้ามี header
                $header = fgetcsv($csvFile);

                echo '<div class="mt-6">';
                echo '<table class="min-w-full bg-white text-black rounded-lg overflow-hidden shadow-lg">';
                echo '<thead class="bg-gray-700 text-white">';
                echo '<tr>';
                foreach ($header as $col) {
                    echo '<th class="py-2 px-4 text-left">' . htmlspecialchars($col) . '</th>';
                }
                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';

                // แสดงข้อมูลทีละแถว
                while (($row = fgetcsv($csvFile)) !== FALSE) {
                    echo '<tr>';
                    foreach ($row as $col) {
                        echo '<td class="py-2 px-4 border-b">' . htmlspecialchars($col) . '</td>';
                    }
                    echo '</tr>';
                }

                echo '</tbody>';
                echo '</table>';
                echo '</div>';

                fclose($csvFile);
            } else {
                echo "<p class='text-red-500 mt-4'>Error: กรุณาอัปโหลดไฟล์ CSV ที่ถูกต้อง</p>";
            }
        }
        ?>

    </div>

</body>

</html>