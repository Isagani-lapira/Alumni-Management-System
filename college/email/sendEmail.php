<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require $_SERVER['DOCUMENT_ROOT'] . '/PHP_process/phpmailer/src/Exception.php';
require $_SERVER['DOCUMENT_ROOT'] . '/PHP_process/phpmailer/src/PHPMailer.php';
require $_SERVER['DOCUMENT_ROOT'] . '/PHP_process/phpmailer/src/SMTP.php';
require 'emailTable.php';
require_once '../php/connection.php';

if (isset($_POST['message']) && isset($_POST['recipient']) && isset($_POST['subject'])) {

    //data to be use
    $recipient = trim($_POST['recipient']);
    $message = trim($_POST['message']);
    $subject = trim($_POST['subject']);
    $selectedImages = (isset($_FILES['images'])) ? $_FILES['images'] : null;
    $selectedFiles =  (isset($_FILES['files'])) ? $_FILES['files'] : null;

    $emailInsertion = new EmailTable();
    $random = rand(0, 4000);
    $uniqueId = substr(md5(uniqid()), 0, 7);
    $emailID = 'Admin' . $uniqueId . $random;
    $date = date('y/m/d');
    $personID = $_SESSION['personID']; //to be changed

    // //check for recipient to check what is the available data 
    if ($recipient == 'groupEmail') {
        $college = $_POST['college'];
        $user = strtolower($_POST['user']);

        //if the user is alumni / student
        if ($user != 'all') {
            sendEmailToManyUser($user, $college, $subject, $message, $selectedImages, $selectedFiles, $mysql_con);
            $email = $emailInsertion->insertEmail($emailID, $user, $college, $date, $personID, $subject, $message, $mysql_con);
        }
        //for all user
        else {
            sendEmailToManyUser('alumni', $college, $subject, $message, $selectedImages, $selectedFiles, $mysql_con);
            sendEmailToManyUser('student', $college, $subject, $message, $selectedImages, $selectedFiles, $mysql_con);

            //store a record of creating an email
            $email = $emailInsertion->insertEmail($emailID, 'All', $college, $date, $personID, $subject, $message, $mysql_con);
        }
    } else {

        // Individual Email

        //get user's college based on email
        $recipient = $_POST['searchEmail'];



        $college = getUserCollege($recipient, $mysql_con);

        if ($college != '') {
            $college = $college;
            sendEmail($subject, $message, $recipient, $selectedImages, $selectedFiles);
            $email = $emailInsertion->insertEmail($emailID, $recipient, $college, $date, $personID, $subject, $message, $mysql_con);
        } else {
            echo 'user is not existing';
        }
    }
} else echo 'ayaw';


//retrieve college if the email is individual
function getUserCollege($email, $con)
{
    $college = '';
    $query = "SELECT `personID` FROM `person` WHERE `personal_email` = '$email';";

    $result = mysqli_query($con, $query);
    $rows = mysqli_num_rows($result);
    //get person ID using the email provided
    if ($result && $rows > 0) {
        $row = mysqli_fetch_assoc($result);
        $personID = $row['personID'];


        //check if it is a student
        $queryCollegeStudent = 'SELECT `colCode` FROM `student` WHERE `personID` = "' . $personID . '"';
        $studentResult = mysqli_query($con, $queryCollegeStudent);
        $studentRow = mysqli_num_rows($studentResult);
        if ($studentRow > 0) {
            $row = mysqli_fetch_assoc($studentResult);
            $college = $row['colCode'];
            return $college;
        }

        //check if it is a alumni
        $queryCollegeAlumni = "SELECT `colCode` FROM `alumni` WHERE `personID` = '$personID'";

        $alumniResult = mysqli_query($con, $queryCollegeAlumni);
        $alumniRow = mysqli_num_rows($alumniResult);

        if ($alumniRow > 0) {

            $row = mysqli_fetch_assoc($alumniResult);
            $college = $row['colCode'];

            return $college;
        }


        //check if it is college admin
        $queryCollegeAlumni = 'SELECT `colCode` FROM `colAdmin` WHERE `personID` = "' . $personID . '"';
        $alumniResult = mysqli_query($con, $queryCollegeAlumni);
        $alumniRow = mysqli_num_rows($alumniResult);
        if ($alumniRow > 0) {
            $row = mysqli_fetch_assoc($alumniResult);
            $college = $row['colCode'];

            return $college;
        }

        return null;
    } else return null;
}

function sendEmailToManyUser($user, $college, $subject, $message, $selectedImages, $selectedFiles, $con)
{
    $query = 'SELECT * FROM `' . $user . '` WHERE `colCode` = "' . $college . '"'; //check which college and type of user 
    $result = mysqli_query($con, $query);
    $personID = '';

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $personID = $row['personID']; //use to pass on the query for getting personal email

            //retrieve the personal email
            $queryEmail = "SELECT person.personal_email
                                FROM $user JOIN person ON 
                                $user.personID = person.personID
                                WHERE $user.personID = '$personID'";
            $resultEmail = mysqli_query($con, $queryEmail);
            if ($resultEmail) {
                $row = mysqli_fetch_assoc($resultEmail);
                $recipient = $row['personal_email'];

                //send email
                sendEmail($subject, $message, $recipient, $selectedImages, $selectedFiles);
            }
        }
    }
}

//use to send email
function sendEmail($subject, $message, $recipient, $images, $files)
{
    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'lapirajr.isagani.t.1933@gmail.com';
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;

    //account that is currently using
    $mail->setFrom('lapirajr.isagani.t.1933@gmail.com');
    $mail->Password = 'fqhpqaaisqvctcfg';

    $mail->addAddress($recipient);
    $mail->isHTML(true);
    $mail->Subject = $subject; //subject
    $mail->Body = $message; //message

    //adding attachment
    if ($images != null) {
        foreach ($images['tmp_name'] as $index => $tmp_name) {
            $file_name = $images['name'][$index];
            $mail->addAttachment($tmp_name, $file_name);
        }
    }

    if ($files != null) {
        foreach ($files['tmp_name'] as $index => $tmp_name) {
            $file_name = $files['name'][$index];
            $mail->addAttachment($tmp_name, $file_name);
        }
    }


    $mail->send(); //send the email
}
