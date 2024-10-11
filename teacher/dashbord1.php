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
            <!-- <h2 class="text-2xl font-semibold">คณะวิทยาศาสตร์และนวัตกรรมดิจิทัล</h2>
            <h3 class="text-xl mb-6">มหาวิทยาลัยทักษิณ วิทยาเขตพัทลุง</h3> -->
        </section>

        <!-- Buttons Section -->
        <section class="flex justify-center space-x-4 mb-8">
        <a href="dashbord1.php" target="_blank" class="bg-green-500 text-white px-6 py-3 rounded-lg flex items-center">
                ผลการเลือกสถานที่ประกอบการภายในและภายนอก
            </a>
            <a href="dashbord2.php" target="_blank" class="bg-yellow-500 text-white px-6 py-3 rounded-lg flex items-center">
                ผลการเลือกสถานที่ประกอบการ
            </a>
            <a href="dashbord3.php" target="_blank" class="bg-orange-500 text-white px-6 py-3 rounded-lg flex items-center">
                จังหวัดที่นิสิตเลือกฝึกสหกิจ
            </a>
            <a href="#" target="_blank" class="bg-pink-500 text-white px-6 py-3 rounded-lg flex items-center">
                Review สถานประกอบการ
            </a>
        </section>
        <section class="mt-8 flex justify-end space-x-4">
            <div class="relative">
                <input type="text" placeholder="ค้นหาสถานประกอบการ" class="pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                <i class="fa fa-search absolute left-3 top-2.5 text-gray-400"></i>
            </div>
            <div>
                <select class="pl-3 pr-10 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="เลือกปีการศึกษา">
                    <option>ประวัติการศึกษา 2567</option>
                    <option>ประวัติการศึกษา 2566</option>
                    <option>ประวัติการศึกษา 2565</option>
                </select>
            </div>
        </section>

        <!-- Chart Section -->
        <section class="flex justify-center space-x-16 mb-8">
            <div class="text-center">
                <h4 class="text-lg font-semibold mb-4">ภายในประเทศ</h4>
                <div class="bg-white p-4 rounded-lg shadow-lg">
                    <canvas id="chart1" width="500" height="350"></canvas>
                </div>
            </div>
            <div class="text-center">
                <h4 class="text-lg font-semibold mb-4">ภายนอกประเทศ</h4>
                <div class="bg-white p-4 rounded-lg shadow-lg">
                    <canvas id="chart2" width="500" height="350"></canvas>
                </div>
            </div>
        </section>

        <!-- Search and Filter Section -->

    </main>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        var ctx1 = document.getElementById('chart1').getContext('2d');
        var ctx2 = document.getElementById('chart2').getContext('2d');

        var chart1 = new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: ['CS', 'IT'],
                datasets: [{
                    label: 'ภายในประเทศ',
                    data: [20, 20],
                    backgroundColor: ['#FF6384', '#36A2EB']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value + ' คน'; // เพิ่มคำว่า 'คน' หลังจากค่าตัวเลขบนแกน y
                            }
                        }
                    }
                }
            }
        });

        var chart2 = new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: ['CS', 'IT'],
                datasets: [{
                    label: 'ภายนอกประเทศ',
                    data: [5, 20],
                    backgroundColor: ['#FF6384', '#36A2EB']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value + ' คน'; // เพิ่มคำว่า 'คน' หลังจากค่าตัวเลขบนแกน y
                            }
                        }
                    }
                }
            }
        });

        var chart3 = new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: ['CS', 'IT'],
                datasets: [{
                    label: 'ภายนอกประเทศ',
                    data: [5, 20],
                    backgroundColor: ['#FF6384', '#36A2EB']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value + ' คน'; // เพิ่มคำว่า 'คน' หลังจากค่าตัวเลขบนแกน y
                            }
                        }
                    }
                }
            }
        });
    </script>

</html>