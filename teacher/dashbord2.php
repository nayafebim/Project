<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>โครงการสหกิจศึกษา</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="bg-gradient-to-br from-green-700 to-teal-500 text-white font-sans min-h-screen">
    <!-- Navbar -->
    <?php include "header.php" ?>

    <main class="p-6">
        <!-- Title Section -->
        <section class="text-center text-white mb-8">
            <h1 class="text-4xl font-bold mb-4">โครงการสหกิจศึกษา</h1>
            <h2 class="text-2xl font-semibold">คณะวิทยาศาสตร์และนวัตกรรมดิจิทัล</h2>
            <h3 class="text-xl mb-6">มหาวิทยาลัยทักษิณ วิทยาเขตพัทลุง</h3>
        </section>

        <!-- Buttons Section -->
        <section class="flex justify-center space-x-4 mb-8">
            <a href="dashbord1.php" target="_blank" class="bg-green-500 text-white px-6 py-3 rounded-lg flex items-center hover:bg-green-600 transition duration-300 shadow-lg">
                ผลการเลือกสถานที่ประกอบการภายในและภายนอก
            </a>
            <a href="dashbord2.php" target="_blank" class="bg-yellow-500 text-white px-6 py-3 rounded-lg flex items-center hover:bg-yellow-600 transition duration-300 shadow-lg">
                ผลการเลือกสถานที่ประกอบการ
            </a>
            <a href="dashbord3.php" target="_blank" class="bg-orange-500 text-white px-6 py-3 rounded-lg flex items-center hover:bg-orange-600 transition duration-300 shadow-lg">
                จังหวัดที่นิสิตเลือกฝึกสหกิจ
            </a>
            <a href="#" target="_blank" class="bg-pink-500 text-white px-6 py-3 rounded-lg flex items-center hover:bg-pink-600 transition duration-300 shadow-lg">
                Review สถานประกอบการ
            </a>
        </section>

        <!-- Search and Filter Section -->
        <section class="mt-8 flex justify-center space-x-4">
            <div class="relative">
                <input type="text" placeholder="ค้นหาสถานประกอบการ" class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 text-gray-700 shadow-sm">
                <i class="fa fa-search absolute left-3 top-2.5 text-gray-400"></i>
            </div>
            <div>
                <select class="pl-3 pr-10 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 text-gray-700 shadow-sm">
                    <option>ประวัติการศึกษา 2567</option>
                    <option>ประวัติการศึกษา 2566</option>
                    <option>ประวัติการศึกษา 2565</option>
                </select>
            </div>
        </section>

        <!-- Chart with White Box -->
        <section class="flex justify-center mt-12">
            <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-4xl">
                <h4 class="text-lg font-semibold text-center mb-4 text-gray-700">กราฟการเลือกสถานประกอบการ</h4>
                <canvas id="myChart" width="400" height="200"></canvas>
            </div>
        </section>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar', // ใช้ bar แนวนอน (indexAxis 'y')
            data: {
                labels: ['บริษัท A', 'บริษัท B', 'บริษัท C', 'บริษัท D', 'บริษัท E', 'บริษัท F', 'บริษัท G'], // ชื่อบริษัท
                datasets: [{
                    label: 'CS',
                    data: [53, 75, 86, 90, 55, 80, 65], // ข้อมูลสำหรับ CS
                    backgroundColor: '#FF6384' // สีสำหรับ CS
                },
                {
                    label: 'IT',
                    data: [43, 65, 72, 85, 48, 70, 60], // ข้อมูลสำหรับ IT
                    backgroundColor: '#36A2EB' // สีสำหรับ IT
                },
                {
                    label: 'คณิตศาสตร์',
                    data: [50, 60, 70, 85, 40, 65, 75], // ข้อมูลสำหรับ คณิตศาสตร์
                    backgroundColor: '#FFCE56' // สีสำหรับ คณิตศาสตร์
                },
                {
                    label: 'เคมี',
                    data: [40, 55, 65, 75, 35, 60, 70], // ข้อมูลสำหรับ เคมี
                    backgroundColor: '#4BC0C0' // สีสำหรับ เคมี
                },
                {
                    label: 'ชีววิทยา',
                    data: [45, 60, 68, 80, 50, 70, 65], // ข้อมูลสำหรับ ชีววิทยา
                    backgroundColor: '#9966FF' // สีสำหรับ ชีววิทยา
                },
                {
                    label: 'ประมง',
                    data: [30, 45, 55, 65, 40, 50, 60], // ข้อมูลสำหรับ ประมง
                    backgroundColor: '#FF9F40' // สีสำหรับ ประมง
                },
                {
                    label: 'สิ่งแวดล้อม',
                    data: [35, 50, 60, 70, 45, 55, 65], // ข้อมูลสำหรับ สิ่งแวดล้อม
                    backgroundColor: '#8E44AD' // สีสำหรับ สิ่งแวดล้อม
                }]
            },
            options: {
                responsive: true,
                indexAxis: 'y', // ทำให้กราฟเป็นแนวนอน
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value + '%'; // เพิ่มหน่วย %
                            }
                        }
                    },
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    title: {
                        display: true,
                        text: 'ผลการเลือกสถานประกอบการสหกิจศึกษา'
                    }
                }
            }
        });
    </script>

</body>

</html>
