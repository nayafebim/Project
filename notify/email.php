<?php
$to = "642021124@gmail.com";  // ที่อยู่อีเมลของผู้รับ
$subject = "แจ้งเตือนจากระบบ";  // หัวข้อของอีเมล
$message = "นี่คือข้อความแจ้งเตือน";  // ข้อความของอีเมล
$headers = "From: knxakn@gmail.com";  // ที่อยู่อีเมลของผู้ส่ง

// ส่งอีเมล
if(mail($to, $subject, $message, $headers)){
    echo "อีเมลถูกส่งสำเร็จ";
} else {
    echo "การส่งอีเมลล้มเหลว";
}
?>

<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';  // โหลด PHPMailer

$mail = new PHPMailer(true);

try {
    // ตั้งค่าการเชื่อมต่อ SMTP
    $mail->isSMTP();
    $mail->Host = 'smtp.example.com';  // เซิร์ฟเวอร์ SMTP ของคุณ
    $mail->SMTPAuth = true;
    $mail->Username = 'yourname@example.com';  // ชื่อผู้ใช้ SMTP
    $mail->Password = 'yourpassword';  // รหัสผ่าน SMTP
    $mail->SMTPSecure = 'tls';  // การเข้ารหัส (TLS หรือ SSL)
    $mail->Port = 587;  // พอร์ต SMTP

    // ข้อมูลผู้ส่งและผู้รับ
    $mail->setFrom('knxakn@gmail.com', 'มะคนสวย');
    $mail->addAddress('642021124@gmail.com', 'โค้กๆ')  ;

    // หัวข้อและข้อความ
    $mail->isHTML(true);  // กำหนดให้เนื้อหาอีเมลเป็น HTML
    $mail->Subject = 'แจ้งเตือนจากระบบ';
    
    // ข้อความ HTML ในรูปแบบที่สอดคล้องกับตัวอย่างที่คุณส่งมา
    $mail->Body = '
    <html>
    <head>
    <title>โครงการการเข้าร่วมสหกิจศึกษา</title>
    </head>
    <body>
    <p>เรียนคุณแนตรวารี ศรีน้อย</p>
    <p>วันที่ 20/06/2566</p>
    <p>สถานะเอกสาร : ยื่นเอกสารสำเร็จ</p>
    <p>นิสิตสามารถดูเพิ่มเติมได้ที่ : <a href="https://coopmis.tsu.ac.th/">https://coopmis.tsu.ac.th/</a></p>
    <p>*สอบถามปัญหาการใช้งาน e-mail : jiraporn@tsu.ac.th</p>
    </body>
    </html>
    ';

    // ข้อความแบบธรรมดาสำหรับอีเมลที่ไม่รองรับ HTML
    $mail->AltBody = 'เรียนคุณแนตรวารี ศรีน้อย วันที่ 20/06/2566 สถานะเอกสาร: ยื่นเอกสารสำเร็จ นิสิตสามารถดูเพิ่มเติมได้ที่ https://coopmis.tsu.ac.th/ *สอบถามปัญหาการใช้งาน e-mail: jiraporn@tsu.ac.th';

    // ส่งอีเมล
    $mail->send();
    echo 'อีเมลถูกส่งสำเร็จ';
} catch (Exception $e) {
    echo "การส่งอีเมลล้มเหลว: {$mail->ErrorInfo}";
}
?>
