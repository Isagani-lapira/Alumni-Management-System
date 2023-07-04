<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';
require_once 'connection.php';

if (isset($_POST['message']) && isset($_POST['recipient'])) {

    $recipient = $_POST['recipient'];
    $message = $_POST['message'];
    //check for recipient to check what is the available data 
    if ($recipient == 'groupEmail') {
        $college = $_POST['college'];
        $user = strtolower($_POST['user']);

        //if the user is alumni / student
        if ($user != 'all') {
            $query = 'SELECT * FROM `' . $user . '` WHERE `colCode` = "' . $college . '"'; //check which college and type of user 
            $result = mysqli_query($mysql_con, $query);
            $personID = '';
            $personalEmail = '';

            if ($result) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $personID = $row['personID']; //use to pass on the query for getting personal email

                    //retrieve the personal email
                    $queryEmail = "SELECT person.personal_email
                                FROM $user JOIN person ON 
                                $user.personID = person.personID
                                WHERE $user.personID = '$personID'";
                    $resultEmail = mysqli_query($mysql_con, $queryEmail);
                    if ($resultEmail) {
                        $row = mysqli_fetch_assoc($resultEmail);
                        $recipient = $row['personal_email'];

                        //send email
                        sendEmail($message, $recipient);
                    }
                }
            }
        }
        //for all user
        else $query = '';
    } else {
        $recipient = $_POST['searchEmail'];
        sendEmail($message, $recipient);
    }
} else echo 'ayaw';

function sendEmail($message, $recipient)
{
    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'lapirajr.isagani.t.1933@gmail.com';
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;

    $mail->setFrom('lapirajr.isagani.t.1933@gmail.com');
    $mail->Password = 'fqhpqaaisqvctcfg';

    $mail->addAddress($recipient);
    $mail->isHTML(true);
    $mail->Subject = 'Practice Email sending';
    $mail->Body = $message;


    if ($mail->send()) echo 'Email sent successfully';
    else echo 'Email not send';
}
