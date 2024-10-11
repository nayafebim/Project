
<?php
require('WriteHTML.php');
session_start();
include_once "../connection.php";
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$OrganizationID = null;
if (isset($_GET["organization_id"])) {
    $OrganizationID = $_GET["organization_id"];
}

$Course_ID = null;
if (isset($_GET["course_id"])) {
    $Course_ID = $_GET["course_id"];
}

// ตั้งค่าเขตเวลาเป็นเวลาประเทศไทย
date_default_timezone_set('Asia/Bangkok');
$date = date("Y-m-d");
$year = date("Y");

// แปลงรูปแบบให้เป็นรูปแบบ timestamp
$timestamp = strtotime($date);
$yearstamp = strtotime($year);
include_once('../assets/Thaidate/Thaidate.php');
include_once('../assets/Thaidate/thaidate-functions.php');

use Rundiz\Thaidate\Thaidate;

// สร้างออบเจ็กต์ของ Thaidate
$thaidate = new Thaidate();

// ใช้งานฟังก์ชัน date() เพื่อแสดงวันที่ในรูปแบบไทย
$thaiDate = $thaidate->date('j F Y', $timestamp);
$thaiYear = $thaidate->date('Y', $yearstamp);

// แปลงเป็นเลขไทย
$thaiDateWithNumbers = $thaidate->toThaiNumber($thaiDate);
$thaiYearNew = $thaidate->toThaiNumber($thaiYear);

// echo $thaiYearNew;

// Function to convert Arabic numbers to Thai numbers
function convertToThaiNumbers($number)
{
    $thaiNumbers = [
        '0' => '๐',
        '1' => '๑',
        '2' => '๒',
        '3' => '๓',
        '4' => '๔',
        '5' => '๕',
        '6' => '๖',
        '7' => '๗',
        '8' => '๘',
        '9' => '๙'
    ];

    return strtr($number, $thaiNumbers);
}

// Start fetch จำนวนและชื่อ organization

$sql = "SELECT COUNT(DISTINCT student.student_id) AS student_count, organization.organization_name, course.course_name
        FROM intern
        JOIN organization ON intern.organization_id = organization.organization_id
        JOIN student ON intern.student_id = student.student_id
        JOIN course ON student.course = course.course_id
        WHERE organization.organization_id = ? 
        AND student.course = ?
        GROUP BY organization.organization_id, organization.organization_name, course.course_name
";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("ii", $OrganizationID, $Course_ID);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if there are results
    if ($result->num_rows > 0) {
        while ($OrgData = $result->fetch_assoc()) {
            $student_Count = $OrgData['student_count'];
            $Org_Name = $OrgData['organization_name'];
            $couse_name = $OrgData['course_name'];
            $studentCount = convertToThaiNumbers($student_Count);
        }
    } else {
        echo "No students found.";
    }

    $stmt->close();
} else {
    echo "Error preparing statement: " . $conn->error;
    exit();
}


//ดึงชื่อคณบดี
$query = "SELECT * FROM type_document";
$result = mysqli_query($conn, $query);

if(!$result) {
    die("Query Failed: " . mysqli_error($conn));
}
// End fetch จำนวนและชื่อ organization


//ดึงเลขอว
$query = "SELECT docnum, thaiYearNew FROM document WHERE id = 1"; // ปรับตามเงื่อนไขที่ต้องการ เช่น WHERE id = ...
$result = mysqli_query($conn, $query);

if(!$result) {
    die("Query Failed: " . mysqli_error($conn));
}




$htmlTable1 =
    iconv('UTF-8', 'TIS-620', 'สำหรับนิสิตระดับ ปริญญาตรี หลักสูตรวิทยาศาสตรบัณฑิต (วท.บ.) และมีประสบการณ์ ในวิชาชีพก่อนเข้าสู่การทำงานจริง โดยมีชั่วโมงปฏิบัติงานเป็นเวลา ๑ ภาคเรียน (๑๖ สัปดาห์) นั้น');
$htmlTable2 =
    iconv('UTF-8', 'TIS-620', "ประกอบการเป็นหน่วยงานที่มีชื่อเสียง มีมาตราการดำเนินงานเป็นที่ยอมรับอย่างกว้างขวาง จึงขอความอนุเคราะห์พิจารณารับนิสิตหลักสูตรวิทยาศาสตรบัณฑิต สาขาวิชา$couse_name ชั้นปีที่ 4 เข้ารับปฏฺิบัติงานสหกิจศึกษา ตั้งแต่วันที่ ๑๓ พฤศจิกายน ๒๕๖๗ ถึงวันที่ ๑ มีนาคม ๒๕๖๘ จำนวน $studentCount คน ดังรายชื่อต่อไปนี้");
$htmlTable3 =
    iconv('UTF-8', 'TIS-620', 'ฝึกปฏิบัติงานสหกิจศึกษา รายละเอียดปรากฏตามสิ่งที่ส่งมาด้วย ๑ ส่งคืนไปยัง คณะวิทยาศาสตร์และ นวัตกรรมดิจิทัล มหาวิทยาลัยทักษิณ เพื่อเตรียมความพร้อมของนิสิตก่อนเข้ารับการฝึก ปฏิบัติงานสหกิจต่อไป');







$pdf = new PDF_HTML();
$pdf->AddPage();
$pdf->AddFont('THSarabunNew', '', 'THSarabunNew.php');
$pdf->SetFont('THSarabunNew', '', 16);
$top_margin = 15;  // 1.5 ซม. = 15 มม.
$left_margin = 30; // 3 ซม. = 30 มม.
$right_margin = 20; // 2 ซม. = 20 มม.
$bottom_margin = 0; // 0 ซม.
$pdf->SetMargins($left_margin, $top_margin, $right_margin);
// $pdf->SetAutoPageBreak(true, $bottom_margin);

$cellWidth = 80;
$pdf->SetY(40);
// $pdf->Cell(20);
$pdf->Cell(20, 6.5, iconv('UTF-8', 'TIS-620', "ที่ อว.$docnum/$thaiYearNew"), 0, 1, 'L');
$pdf->SetY(40);
$pdf->Cell(115, 50);
$pdf->Cell(0, 6.5, iconv('UTF-8', 'TIS-620', 'มหาวิทยาลัยทักษิณ'), 0, 1, 'L', 0);
$pdf->Cell(115);
$pdf->Cell(0, 6.5, iconv('UTF-8', 'TIS-620', '๒๒๒ หมู่ที่ ๒ ตำบลบ้านพร้าว'), 0, 1, 'L');
$pdf->Cell(115);
$pdf->Cell(0, 6.5, iconv('UTF-8', 'TIS-620', 'อำเภอป่าพะยอม '), 0, 1, 'L');
$pdf->Cell(115);
$pdf->Cell(0, 6.5, iconv('UTF-8', 'TIS-620', 'จังหวัดพัทลุง ๙๓๒๑๐'), 0, 1, 'L');
$pdf->Cell(75.5);
$pdf->Cell(0, 8, iconv('UTF-8', 'TIS-620', $thaiDateWithNumbers), 0, 1, 'L');


$cellWidth = 80;
$pdf->Cell(0, 9, iconv('UTF-8', 'TIS-620', 'เรื่อง ขอความอนุเคราะห์รับนิสิตเข้าฝึกปฏิบัติสหกิจศึกษา'), 0, 1, 'L');
$pdf->Cell(0, 8, iconv('UTF-8', 'TIS-620', "เรียน ผู้จัดการบริษัท $Org_Name"), 0, 1, 'L');
$pdf->Ln(1.12);
$pdf->MultiCell(0, 8, iconv('UTF-8', 'TIS-620', "สิ่งที่ส่งมาด้วย:  ๑. แบบตอบรับนิสิตฝึกปฏิบัติสหกิจศึกษา จำนวน 1 ฉบับ"));
$pdf->SetX($pdf->GetX() + 24); // ปรับค่า 5 เพื่อให้ตำแหน่งตรงกัน
$pdf->MultiCell(0, 8, iconv('UTF-8', 'TIS-620', "๒. ใบประเมินผลการฝึกงาน จำนวน 1 ฉบับ"));

$cellWidth = 80;
$pdf->SetX($pdf->GetX() + 24); // ย่อหน้า 24 มม. จากขอบซ้าย
$pdf->Cell(0, 8, iconv('UTF-8', 'TIS-620', "ด้วยคณะวิทยาศาสตร์และนวัตกรรมดิจิทัล มหาวิทยาลัยทักษิณ ได้จัดให้มีรายวิชาสหกิจศึกษา"));
// $cellWidth = 190;
// $pdf->MultiCell(0, 8, iconv('UTF-8', 'TIS-620', "ปริญญาตรี หลักสูตรวิทยาศาสตรบัณฑิต (วท.บ.) และมีประสบการณ์ในวิชาชีพก่อนเข้าสู่การทำงานจริง โดยมีชั่วโมงปฏิบัติงานเป็นเวลา ๑ ภาคเรียน (๑๖ สัปดาห์) นั้น"), 0, 1);
// $pdf->WriteHTML($htmlTable);
$pdf->Ln(6);
$pdf->Justify($htmlTable1, 163, 7);

$cellWidth = 80;
$pdf->SetX($pdf->GetX() + 24); // ย่อหน้า 24 มม. จากขอบซ้าย
$pdf->Cell(0, 8, iconv('UTF-8', 'TIS-620', "ด้วยคณะวิทยาศาสตร์และนวัตกรรมดิจิทัล มหาวิทยาลัยทักษิณ  พิจารณาเห็นว่า ชื่อสถานประ"));
// $cellWidth = 190;
$pdf->Ln(6);
$pdf->Justify($htmlTable2, 163, 7);


// Start fetch ชื่อนิสิต และรหัสนิสิต

$sql_organization = "SELECT intern.*, organization.*, student.*, course.* FROM intern 
JOIN organization ON intern.organization_id = organization.organization_id
JOIN student ON intern.student_id = student.student_id
JOIN course ON student.course = course.course_id
WHERE organization.organization_id = ? AND student.course = ?
ORDER BY intern.student_id";
if ($stmt = $conn->prepare($sql_organization)) {
    $stmt->bind_param("ii", $OrganizationID, $Course_ID);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if there are results
    if ($result->num_rows > 0) {
        // Set the width for the name column, label column, and ID column
        $nameWidth = 50;   // Adjust this width based on name length
        $labelWidth = 30;  // Width for "รหัสประจำตัวนิสิต" label
        $idWidth = 30;     // Width for the student ID column
        $studentCounter = 1;

        while ($OrganizationData = $result->fetch_assoc()) {
            // Construct the student's name
            $studentName = htmlspecialchars($OrganizationData['student_prefix'] . "" .
                $OrganizationData['student_surname'] . " " .
                $OrganizationData['student_lastname']);

            // Convert student counter to Thai numbers
            $studentNumberThai = convertToThaiNumbers($studentCounter);

            // Convert student ID to Thai numbers
            $studentId = convertToThaiNumbers($OrganizationData['student_id']);

            // Set X for column layout, print the student's name
            $pdf->SetX($pdf->GetX() + 24);
            $pdf->Cell(5, 8, iconv('UTF-8', 'TIS-620', $studentNumberThai . '.'), 0, 0, 'L'); // Print Thai number counter
            $pdf->Cell($nameWidth, 8, iconv('UTF-8', 'TIS-620', $studentName), 0, 0, 'L');

            // Print student ID label
            $pdf->Cell($labelWidth, 8, iconv('UTF-8', 'TIS-620', 'รหัสประจำตัวนิสิต'), 0, 0, 'L');

            // Print the student's ID
            $pdf->Cell($idWidth, 8, iconv('UTF-8', 'TIS-620', $studentId), 0, 1, 'L');

            // Increment the student counter
            $studentCounter++;
        }
    } else {
        echo "No students found.";
    }

    $stmt->close();
} else {
    echo "Error preparing statement: " . $conn->error;
    exit();
}





// $pdf->MultiCell(0, 8, iconv('UTF-8', 'TIS-620', "๑. นางสาวอังคนา เหมาะกิจ         รหัสประจำตัวนิสิต ๖๔๒๐๒๑๑๓๕"));

// End fetch ชื่อนิสิต และรหัสนิสิต


$cellWidth = 80;
$pdf->Cell(0, 8, iconv('UTF-8', 'TIS-620', "ทั้งนี้ ขอความกรุณาระบุรายละเอียดเกี่ยวกับงานและคุณสมบัติเพิ่มเติมในการปฏิบัติงานตามแบบตอบรับนิสิตเข้า"));
$pdf->Ln(6);
$pdf->Justify($htmlTable3, 163, 7);


$pdf->Ln(1.12);
$pdf->SetX($pdf->GetX() + 24);
$pdf->MultiCell(0, 8, iconv('UTF-8', 'TIS-620', "จึงเรียนมาเพื่อโปรดพิจารณาให้ความอนุเคราะห์และจักขอบคุณยิ่ง"));

$pdf->Ln(3);
$pdf->Cell(75.5);
$pdf->Cell(0, 8, iconv('UTF-8', 'TIS-620', 'ขอแสดงความนับถือ'), 0, 1, 'L');


$pdf->Ln(15);
$pdf->Cell(63);
while($row = mysqli_fetch_assoc($result)) {
    $text = iconv('UTF-8', 'TIS-620', $row['type_name']); // Convert the encoding
    $pdf->Cell(0, 8, $text, 0, 1, 'L');
}

$pdf->Cell(62);
$pdf->Cell(0, 6, iconv('UTF-8', 'TIS-620', 'คณบดีคณะวิทยาศาสตร์ ปฏิบัติหน้าที่แทน'), 0, 1, 'L');
$pdf->Cell(71);
$pdf->Cell(0, 6, iconv('UTF-8', 'TIS-620', 'อธิการบดีมหาวิทยาลัยทักษิณ'), 0, 1, 'L');


$pdf->Cell(0, 6, iconv('UTF-8', 'TIS-620', 'คณะวิทยาศาสตร์และนวัตกรรมดิจิทัล'), 0, 1, 'L');
$pdf->Cell(0, 6, iconv('UTF-8', 'TIS-620', 'โทร. ๐ ๗๔๖๐ ๙๖๐๐ ต่อ ๒๑๐๘'), 0, 1, 'L');
$pdf->Cell(0, 6, iconv('UTF-8', 'TIS-620', 'โทรสาร. ๐ ๗๔๖๐ ๙๖๐๗'), 0, 1, 'L');
$pdf->Cell(0, 6, iconv('UTF-8', 'TIS-620', 'ไปรษณีย์อิเล็กทรอนิกส์ : jiraporn@tsu.ac.th'), 0, 1, 'L');
$pdf->Output();
?>