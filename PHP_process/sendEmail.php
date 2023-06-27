<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require 'phpmailer/src/Exception.php';
    require 'phpmailer/src/PHPMailer.php';
    require 'phpmailer/src/SMTP.php';

    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'lapirajr.isagani.t.1933@gmail.com';
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;

    $mail->setFrom('lapirajr.isagani.t.1933@gmail.com');

    $mail->addAddress('patrickjoseph.pronuevo.a@bulsu.edu.ph');
    $mail->addAddress('michaellashylle.ong.x@bulsu.edu.ph');
    $mail->isHTML(true);
    $mail->Subject = 'Practice Email sending';
    $mail->Body = "Practice email sending lang gamit system natin guys";


    if($mail->send()) echo'Email sent successfully';
    else echo 'Email not send';
?>