<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการข้อมูลสหกิจศึกษา</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        body {
            background: linear-gradient(to bottom right, #004d00, #008000);
            font-family: Arial, sans-serif;
            color: white;
        }

        header {
            background-color: #005900;
            padding: 20px;
        }

        header img {
            width: 100px;
            height: auto;
        }

        .nav-links {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .nav-links a {
            margin: 0 15px;
            color: yellow;
            text-decoration: none;
        }

        .nav-links a:hover {
            color: white;
        }

        .buttons {
            margin: 20px 0;
            display: flex;
            justify-content: space-between;
        }

        .buttons button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            color: white;
            cursor: pointer;
        }

        .buttons button.bg-green-600 {
            background-color: #008000;
        }

        .buttons button.bg-green-600:hover {
            background-color: #006400;
        }

        .buttons button.bg-yellow-500 {
            background-color: #FFD700;
        }

        .buttons button.bg-yellow-500:hover {
            background-color: #DAA520;
        }

        .buttons button.bg-purple-600 {
            background-color: #6A0DAD;
        }

        .buttons button.bg-purple-600:hover {
            background-color: #4B0082;
        }

        .form-container {
            background-color: #f0f8ff;
            color: black;
            padding: 20px;
            border-radius: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #f0f8ff;
            color: black;
        }

        th,
        td {
            border: 1px solid #dddddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #008000;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .table-buttons i {
            cursor: pointer;
            font-size: 1.2em;
        }

        .table-buttons i.fa-pencil-alt {
            color: green;
        }

        .table-buttons i.fa-trash-alt {
            color: red;
        }
    </style>
</head>

<body>

    <!-- Header -->
    <?php include "header.php" ?>

    <main class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">จัดการข้อมูลสหกิจศึกษา</h1>
        <h2 class="text-xl mb-4">คณะวิทยาศาสตร์และนวัตกรรมดิจิทัล</h2>

        

        <div class="form-container">
            <h3 class="text-lg font-semibold mb-4">จัดการข้อมูลที่เกี่ยวข้อง: ข้อมูลสถานประกอบการ</h3>
            <div class="flex justify-end">
                <a href="insert_organization.php" class="mt-2 bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg hover:bg-green-700">
                    <i class="fa-solid fa-plus"></i> เพิ่มข้อมูลสถานประกอบการ
                </a>
            </div>

            <div class="flex justify-between items-center mb-4 mt-5">
                <div>
                    <label for="region">ภาค</label>
                    <select id="region" name="region" class="ml-2 w-full border rounded-lg px-3 py-2">
                        <option value="">กรุณาเลือกภาค</option>
                        <?php
                        include('../connection.php');
                        $query = mysqli_query($conn, "SELECT * FROM thai_geographies");
                        while ($result = mysqli_fetch_assoc($query)) : ?>
                            <option value="<?= $result['id'] ?>"><?= $result['name'] ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div>
                    <label for="province">จังหวัด</label>
                    <select name="province" id="province" class="ml-2 w-full border rounded-lg px-3 py-2">
                        <option>กรุณาเลือกจังหวัด</option>
                    </select>
                </div>

                <div>
                    <label for="district">อำเภอ</label>
                    <select name="amphure" id="amphure" class="ml-2 w-full border rounded-lg px-3 py-2">
                        <option>กรุณาเลือกอำเภอ</option>
                    </select>
                </div>

                <div>
                    <label for="business-type">ประเภทธุรกิจ</label>
                    <select id="business-type" class="ml-2 w-full border rounded-lg px-3 py-2">
                        <option>กรุณาเลือกประเภทธุรกิจ</option>
                        <?php
                        include('../connection.php');
                        $query = mysqli_query($conn, "SELECT * FROM type_organization");
                        while ($result = mysqli_fetch_assoc($query)) : ?>
                            <option value="<?= $result['type_id'] ?>"><?= $result['type_name'] ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div>
                    <input type="text" placeholder="ค้นหาสถานประกอบการ..." class="p-2 border rounded-md">
                    <button class="ml-2 p-2 bg-green-600 text-white rounded-md">
                        <i class="fa-solid fa-search"></i>
                    </button>
                </div>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>จังหวัด</th>
                        <th>อำเภอ</th>
                        <th>ประเภทธุรกิจ</th>
                        <th>สถานประกอบการ</th>
                        <th>แก้ไข</th>
                        <th>ลบ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include_once "../connection.php";

                    // SQL Query with proper JOIN syntax
                    $sql = "SELECT
                                organization.*,
                                type_organization.type_name,
                                thai_provinces.name_th AS province_name,
                                thai_amphures.name_th AS amphure_name
                            FROM
                                organization
                            JOIN
                                type_organization
                            ON
                                organization.type_organization = type_organization.type_id
                            JOIN
                                thai_provinces
                            ON
                                organization.province = thai_provinces.id
                            JOIN
                                thai_amphures
                            ON
                                organization.amphure = thai_amphures.id
                            ORDER BY
                                organization.organization_id;";
                    $query = mysqli_query($conn, $sql);
                    while ($result = mysqli_fetch_array($query)) {?>
                        <tr>
                            <td class="text-base"><?= $result["province_name"]; ?></td>
                            <td class="text-base"><?= $result["amphure_name"]; ?></td>
                            <td class="text-base"><?= $result["type_name"]; ?></td>
                            <td class="text-base"><?= $result["organization_name"]; ?></td>
                            <td>
                                <a href="edit_organization.php?organization_id=<?= $result["organization_id"]; ?>" class="text-green-700"><i class="fa-solid fa-pencil-alt"></i></a>
                            </td>
                            <td>
                                <a data-id="<?= $result["organization_id"]; ?>" href="?delete=<?= $result["organization_id"]; ?>" class="text-red-500"><i class="fa-solid fa-trash-alt"></i></a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </main>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#region').change(function() {
                var region_id = $(this).val();
                $.ajax({
                    url: 'province/get_provinces.php',
                    type: 'POST',
                    data: {
                        region_id: region_id
                    },
                    success: function(data) {
                        $('#province').html(data);
                        $('#amphure').html('<option>กรุณาเลือกอำเภอ</option>');
                    }
                });
            });

            $('#province').change(function() {
                var province_id = $(this).val();
                $.ajax({
                    url: 'province/get_amphures.php',
                    type: 'POST',
                    data: {
                        province_id: province_id
                    },
                    success: function(data) {
                        $('#amphure').html(data);
                    }
                });
            });
        });
    </script>
</body>

</html>