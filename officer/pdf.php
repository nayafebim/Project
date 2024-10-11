<?php

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL); 

require('fpdf.php');

// สร้างคลาส PDF และตั้งค่าเอกสาร
class PDF extends FPDF
{
    // Header
    function Header()
    {
        // โลโก้ (ใส่ไฟล์รูปโลโก้ที่ใช้ เช่น 'logo.png')
        $imgWidth = 22.8;  // 2.28 เซนติเมตร = 22.8 มม.
        $imgHeight = 40;   // 4 เซนติเมตร = 40 มม.

        // ความกว้างของหน้ากระดาษ A4 คือ 210 มม.
        $pageWidth = 210;

        // คำนวณตำแหน่ง X ให้รูปอยู่ตรงกลาง
        $centerX = ($pageWidth - $imgWidth) / 2;

        // วางรูปภาพให้อยู่ตรงกลาง
        $this->Image('../assets/img/tsu.png', $centerX, 6, $imgWidth, $imgHeight);
        // ตั้งค่า font
        // $this->SetFont('THSarabunNew', '', 16);
        // // ข้อความหัวเอกสาร
        // $this->Cell(0, 10, iconv('UTF-8', 'TIS-620', 'มหาวิทยาลัยทักษิณ'), 0, 1, 'C');
        // เว้นบรรทัด
        $this->Ln(15);
    }

    // Footer
    function Footer()
    {
        // ตำแหน่ง Y 15 หน่วยจากล่างสุดของหน้า
        $this->SetY(-15);
        // ตั้งค่า font
        $this->SetFont('Arial', 'I', 12);
        // เลขหน้าที่มุมขวาล่าง
        $this->Cell(0, 10, iconv('UTF-8', 'TIS-620', 'หน้าที่ ') . $this->PageNo(), 0, 0, 'C');
    }
}

// // Create instance of FPDF class
// $pdf = new FPDF();

// // Add a page
// $pdf->AddPage();

// // Set font
// $pdf->SetFont('Arial', 'B', 16);

// // Add a cell (A cell is a rectangular area to display content)
// $pdf->Cell(40, 10, 'Hello World!');

// // Line break
// $pdf->Ln(20);

// // Add another text (Set font first if you want different style)
// $pdf->SetFont('Arial', '', 12);
// $pdf->MultiCell(0, 10, "This is an example of how to create a PDF file using FPDF in PHP.\nFPDF is a library that allows you to generate PDF documents directly from PHP.");

// // Add more content
// $pdf->Ln(10);
// $pdf->Cell(40, 10, 'Another line of text...');

// // Add image (optional, if you want to insert an image)
// // $pdf->Image('path/to/image.jpg', 10, 50, 30); // x, y, width

// // Output the PDF (I for inline display in the browser, or D for download)
// $pdf->Output('I', 'example.pdf');

// ------------------------------------------------


// สร้างเอกสาร PDF ใหม่
$pdf = new PDF();
$pdf->AddPage();

// ตั้งค่า font (อาจจะเปลี่ยนเป็นฟอนต์ภาษาไทย เช่น TH Sarabun ผ่านการติดตั้งฟอนต์เพิ่มเติม)
// $pdf->SetFont('Arial','',14);
$pdf->AddFont('THSarabunNew', '', 'THSarabunNew.php');
$pdf->SetFont('THSarabunNew', '', 16);
$top_margin = 15;  // 1.5 ซม. = 15 มม.
$left_margin = 30; // 3 ซม. = 30 มม.
$right_margin = 20; // 2 ซม. = 20 มม.
$bottom_margin = 0; // 0 ซม.
// $pdf->SetMargins($left_margin, $top_margin, $right_margin);
// $pdf->SetAutoPageBreak(true, $bottom_margin);

$cellWidth = 80;

// เนื้อหาจดหมาย
$pdf->Cell(0, 8, iconv('UTF-8', 'TIS-620', 'ที่ อว.12345/2567'), 0, 1, 'L');
$pdf->Cell(0, 8, iconv('UTF-8', 'TIS-620', 'มหาวิทยาลัยทักษิณ'), 0, 1, 'R');
$pdf->Cell(0, 8, iconv('UTF-8', 'TIS-620', '226/2 หมู่ที่ 6 ตำบลบ้านท่าว้า'), 3, 2, 'R');
$pdf->Cell(0, 8, iconv('UTF-8', 'TIS-620', 'อำเภอเมือง '), 0, 1, 'R');
$pdf->Cell(0, 8, iconv('UTF-8', 'TIS-620', 'จังหวัดนครราชสีมา 12345'), 0, 1, 'R');
$pdf->Cell(0, 8, iconv('UTF-8', 'TIS-620', '5 สิงหาคม 2567'), 0, 1, 'R');


$pdf->Cell(0, 8, iconv('UTF-8', 'TIS-620', 'เรื่อง ขอความอนุเคราะห์รับนิสิตเข้าฝึกปฏิบัติสหกิจศึกษา'), 0, 1, 'L');

$pdf->Cell(0, 8, iconv('UTF-8', 'TIS-620', 'เรียน ผู้จัดการโรงเรียนบ้านบางนา'), 0, 1, 'L');

$pdf->Ln(2.12);
$pdf->MultiCell(0, 8, iconv('UTF-8', 'TIS-620', "สิ่งที่ส่งมาด้วย:  1. แบบตอบรับนิสิตฝึกปฏิบัติสหกิจศึกษา จำนวน 1 ฉบับ"));

// ตั้งค่า X ให้ตรงกับตำแหน่งของตัวเลข 1. สำหรับบรรทัดต่อมา
$pdf->SetX( $pdf->GetX() + 24); // ปรับค่า 5 เพื่อให้ตำแหน่งตรงกัน
$pdf->MultiCell(0, 8, iconv('UTF-8', 'TIS-620', "2. ใบประเมินผลการฝึกงาน จำนวน 1 ฉบับ"));

// ย่อหน้าเฉพาะบรรทัดแรก
// ย่อหน้าเฉพาะบรรทัดแรก
$pdf->SetX($pdf->GetX() + 24); // ย่อหน้า 24 มม. จากขอบซ้าย
$pdf->MultiCell(0, 8, iconv('UTF-8', 'TIS-620', "ด้วยคณะวิทยาศาสตร์และนวัตกรรมดิจิทัล มหาวิทยาลัยทักษิณ ได้จัดให้มีรายวิชาสหกิจศึกษาสำหรับนิสิตระดับปริญญาตรี หลักสูตรวิทยาศาสตรบัณฑิต (วท.บ.) และมีประสบการณ์ในวิชาชีพก่อนเข้าสู่การทำงานจริง โดยมีชั่วโมงปฏิบัติงานเป็นเวลา ๑ ภาคเรียน (๑๖ สัปดาห์) นั้น"));

// คืนค่า X กลับไปที่ขอบซ้ายปกติ
$pdf->SetX(30); // คืนค่า X กลับไปที่ขอบซ้าย (ปกติคือ 10 มม.)
$pdf->MultiCell(0, 8, iconv('UTF-8', 'TIS-620', ""));

// $pdf->Ln(2.12);
$pdf->SetX($pdf->GetX() + 24);
$pdf->SetX(30); // ย่อหน้า 24 มม.
$pdf->MultiCell(0, 8, iconv('UTF-8', 'TIS-620', "ด้วยคณะวิทยาศาสตร์และนวัตกรรมดิจิทัล มหาวิทยาลัยทักษิณ พิจารณาเห็นว่า ชื่อสถานประกอบการ เป็นหน่วยงานที่มีชื่อเสียงมีมาตราการดำเนินงานเป็นที่ยอมรับอย่างกว้างขวาง จึงขอความอนุเคราะห์พิจารณารับนิสิตหลักสูตรวิทยาศาสตรบัณฑิต สาขาวิชา ชั้นปีที่ 4  เข้ารับปฏฺิบัติงานสหกิจศึกษา ตั้งแต่วันที่ ๑๓ พฤศจิกายน ๒๕๖๗ ถึงวันที่ ๑ มีนาคม ๒๕๖๘ จำนวน ๒คน"));

$pdf->Ln(20);
$pdf->Cell(0, 10, iconv('UTF-8', 'TIS-620', 'ขอแสดงความนับถือ'), 0, 1, 'R');
$pdf->Cell(0, 10, iconv('UTF-8', 'TIS-620', '(ผู้ช่วยศาสตราจารย์ ดร. นพมาศ ปักเข็ม)'), 0, 1, 'R');

$pdf->Ln(10);
$pdf->Cell(0, 8, iconv('UTF-8', 'TIS-620', 'คณะวิทยาศาสตร์นวัตกรรมดิจิทัล'), 0, 1, 'L');
$pdf->Cell(0, 8, iconv('UTF-8', 'TIS-620', 'โทร. 012-3456789'), 0, 1, 'L');
$pdf->Cell(0, 8, iconv('UTF-8', 'TIS-620', 'E-mail: example@.ac.th'), 0, 1, 'L');

// บันทึกเป็นไฟล์ PDF
$pdf->Output('D', 'organization.pdf');
