<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Intern Map</title>
  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="main.css" rel="stylesheet" />
</head>

<body class="bg-gradient-to-br from-green-700 to-teal-500 text-white font-sans min-h-screen">
  <!-- Navbar -->
  <?php include "header.php" ?>
  <h1 class="flex justify-center items-center text-2xl font-bold mb-4 mt-10">ข้อมูลนิสิตที่เข้าร่วมฝึกสหกิจศึกษาของแต่ละจังหวัด</h1>
  <div id="container" class="flex">
    <div id="result" class="flex-1"></div>
    <div id="details" class="flex-1 p-6">
      <!-- Table for Intern Details -->
      <div class="overflow-x-auto bg-white shadow-lg rounded-xl">
        <table id="intern-table" class="min-w-full text-left text-sm bg-white shadow-lg rounded-xl">
          <thead class="bg-gradient-to-r from-gray-100 to-gray-300 text-gray-700">
            <tr>
              <th class="px-6 py-4 border-b-2 border-gray-300 text-left text-xs font-bold uppercase tracking-wider"></th>
              <th class="px-6 py-4 border-b-2 border-gray-300 text-left text-xs font-bold uppercase tracking-wider">รหัสนิสิต</th>
              <th class="px-6 py-4 border-b-2 border-gray-300 text-left text-xs font-bold uppercase tracking-wider">ชื่อ-นามสกุล</th>
              <th class="px-6 py-4 border-b-2 border-gray-300 text-left text-xs font-bold uppercase tracking-wider">หลักสูตร</th>
              <th class="px-6 py-4 border-b-2 border-gray-300 text-left text-xs font-bold uppercase tracking-wider">ชั้นปี</th>
              <th class="px-6 py-4 border-b-2 border-gray-300 text-left text-xs font-bold uppercase tracking-wider">สถานที่ฝึก</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <!-- Rows will be dynamically inserted here -->
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/d3@7"></script>
  <script
    src="https://code.jquery.com/jquery-3.1.1.min.js"
    integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="
    crossorigin="anonymous"></script>
  <script src="main.js"></script>
</body>

</html>
