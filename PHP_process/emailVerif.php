<?php

require_once './connection.php';
session_start();
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once "../PHP_process/phpmailer/src/PHPMailer.php";
require_once "../PHP_process/phpmailer/src/SMTP.php";
require_once "../PHP_process/phpmailer/src/Exception.php";
require_once "../vendor/autoload.php";





$email = "";
$name = "";
$errors = array();
/*** database schema for email
 * 
 * CREATE DATABASE email_verification;

USE email_verification;

-- Table to store email verification information without a user_id
CREATE TABLE email_verification (
    email VARCHAR(255) PRIMARY KEY,
    verification_code CHAR(6) NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    verified TINYINT(1) DEFAULT 0
);
 */


function sendVerificationEmail($email, $verificationCode)
{
    try {
        $html = "<p>Hi there,</p>";
        $html .= "<p>Thank you for registering with us. Please copy the code below to verify your email address:</p>";
        $html .= "<h1>$verificationCode</h1>";
        $html .= "<p>If you didn't initiate this sign-up, ignore this email. Contact us immediately for assistance and assurance.</p>";
        $html .= "<p>Thank you,</p>";
        $html .= "<p>BulSU Connect</p>";
        $mail = new PHPMailer(true);
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'bulsualumnioffice@gmail.com';
        $mail->Password = 'vloe qwyq tyxi tacv';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
        $mail->setFrom('bulsualumnioffice@gmail.com', 'BulSU Connect');
        $mail->addAddress($email);
        $mail->Subject = 'Please verify your email address';
        $mail->Body = $html;
        $mail->AltBody = strip_tags($html);
        $mail->send();
        $info = "Mail has been sent successfully! We've sent a password reset otp to your email - $email";
        $_SESSION['info'] = $info;
        $_SESSION['email'] = $email;
        return true;
    } catch (\Throwable $th) {
        throw $th;
    }
}

//if user click button in forgot password form
if (isset($_POST['action'])) {
    $action = $_POST['action'];

    header('Content-Type: application/json');
    if ($action == 'send_otp') {

        try {
            $email = mysqli_real_escape_string($mysql_con, $_POST['email_address']);
            // Query the email_verification table to see if the email exists and is not yet verified
            $query = "SELECT * FROM email_verification WHERE email = ? AND verified = 0";
            $stmt = $mysql_con->prepare($query);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();

            if ($result->num_rows === 0) {
                //   the email does not exist. create a new one. 
                $verification_code = (string) rand(100000, 999999); // Generates a 6-digit code
                // Calculate the code expiration time (e.g., 1 hour from now)
                $timestamp = date('Y-m-d H:i:s');

                $query = "INSERT INTO email_verification (email, verification_code) VALUES (?, ?)";
                $stmt = $mysql_con->prepare($query);
                $stmt->bind_param("ss", $email, $verification_code);
                // check if it executed
                if (!$stmt->execute()) {
                    echo json_encode(array("success" => false, "message" => "Email not sent! " . $stmt->error, 'error' => true));
                    exit();
                }


                // Send the verification email
                sendVerificationEmail($email, $verification_code);

                echo json_encode(array("success" => true, "message" => "Email sent successfully!", 'post' => $_POST, 'status' => true));
            } else {
                // the email exists. update the code and expiration
                $verification_code = rand(100000, 999999); // Generates a 6-digit code
                $timestamp = date('Y-m-d H:i:s');
                $query = "UPDATE email_verification SET verification_code = ?, timestamp = ? WHERE email = ?";
                $stmt = $mysql_con->prepare($query);
                $stmt->bind_param("sss", $verification_code, $timestamp, $email);
                $stmt->execute();
                $stmt->close();
                // Send the verification email
                sendVerificationEmail($email, $verification_code);

                echo json_encode(array("success" => true, "message" => "Email sent successfully!", 'post' => $_POST, 'status' => true));
            }
        } catch (\Throwable $th) {
            //throw $th;
            echo json_encode(array("success" => false, "message" => "Email not sent! " . $th->getMessage(), 'error' => true));
        }
    } else if ($action == 'resend_otp') {

        $email = mysqli_real_escape_string($mysql_con, $_POST['email_address']);
        // Query the email_verification table to see if the email exists and is not yet verified
        $query = "SELECT * FROM email_verification WHERE email = ?";
        $stmt = $mysql_con->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        try {
            $email = mysqli_real_escape_string($mysql_con, $_POST['email_address']);
            // Query the email_verification table to see if the email exists and is not yet verified
            $query = "SELECT * FROM email_verification WHERE email = ? AND verified = 0";
            $stmt = $mysql_con->prepare($query);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();

            echo json_encode(array("success" => true, "message" => "Email sent successfully!", 'post' => $_POST, 'status' => true));
        } catch (\Throwable $th) {
            //throw $th;
            echo json_encode(array("success" => false, "message" => "Email not sent! " . $th->getMessage(), 'error' => true));
        }
    } else if ($action == 'verify_code') {
        // compare teh code with the one in the database
        try {
            $verification_code = mysqli_real_escape_string($mysql_con, $_POST['verification_code']);
            $email = mysqli_real_escape_string($mysql_con, $_POST['email_address']);
            $query = "SELECT * FROM email_verification WHERE email = ? AND verified = 0";
            $stmt = $mysql_con->prepare($query);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                // Check if the code is still valid and not expired
                $current_time = date('Y-m-d H:i:s');
                $timestamp = strtotime($row['timestamp']);
                $currentTimestamp = time();
                $expirationTime = 60 * 60; // 1 hour (adjust as needed)


                if ($currentTimestamp - $timestamp > $expirationTime) {
                    echo json_encode(array("success" => false, "message" => "Your reset code has expired! Please request a new one.", 'statusCode' => 2));
                } else {
                    // compare the code with the one in the database
                    $code = $row['verification_code'];
                    if ($verification_code == $code) {
                        // update the verified column to 1
                        $query = "UPDATE email_verification SET verified = 1 WHERE email = ?";
                        $stmt = $mysql_con->prepare($query);
                        $stmt->bind_param("s", $email);
                        $stmt->execute();
                        $stmt->close();
                        echo json_encode(array("success" => true, "message" => "Email verified successfully!"));
                    } else {
                        echo json_encode(array("success" => false, "message" => "You've entered incorrect code!", 'code' => $code, 'statusCode' => 1));
                    }
                }
            } else {
                echo json_encode(array("success" => false, "message" => "Can't find your code! Please try again."));
            }
        } catch (\Throwable $th) {
            // throw $th;
            echo json_encode(array("success" => false, "message" => "Something went wrong! " . $th->getMessage()));
        }
    }
}

// //if user click check reset otp button
// if (isset($_POST['check-reset-otp'])) {
//     $_SESSION['info'] = "";

//     $otp_code = mysqli_real_escape_string($mysql_con, $_POST['otp']);

//     $personID = $_SESSION['personID'];
//     $query = "SELECT * FROM password_resets WHERE personID = '$personID' ";


//     try {
//         $code_res = mysqli_query($mysql_con, $query);
//         if (mysqli_num_rows($code_res) > 0) {



//             $fetch_data = mysqli_fetch_assoc($code_res);

//             // Check if the code is still valid and not expired
//             $current_time = date('Y-m-d H:i:s');
//             $code_expiration = $fetch_data['code_expiration'];
//             if ($code_expiration < $current_time) {
//                 $errors['otp-error'] = "Your reset code has expired! Please request a new one.";
//             } else {
//                 // compare the code with the one in the database
//                 $code = $fetch_data['code'];
//                 if (password_verify($otp_code, $code)) {
//                     $info = "Please create a new password..";
//                     $_SESSION['info'] = $info;
//                     $_SESSION['email'] = $email;
//                     header('location: new-password.php');
//                     exit();
//                 } else {
//                     $errors['otp-error'] = "You've entered incorrect code!" . $_SESSION['personID'];
//                 }
//             }
//         } else {
//             $errors['otp-error'] = "Can't find your code! Please try again." . mysqli_error($mysql_con);
//         }
//     } catch (\Throwable $th) {
//         $errors['otp-error'] = "Something went wrong! " . $th->getMessage();
//     }
// }

// //if user click change password button
// if (isset($_POST['change-password'])) {
//     $_SESSION['info'] = "";
//     $password = mysqli_real_escape_string($mysql_con, $_POST['password']);
//     $cpassword = mysqli_real_escape_string($mysql_con, $_POST['cpassword']);
//     if ($password !== $cpassword) {
//         $errors['password'] = "passwords do not match!";
//     } else {
//         // dissect the personID, get the type of user
//         $pattern = '/^([a-zA-Z]+)/'; // This regular expression matches the first letters ('admin', 'UnivAdmin', 'User').
//         $userType = '';
//         $personID = $_SESSION['personID'];
//         if (preg_match($pattern, $_SESSION['personID'], $matches)) {
//             $userType = match ($matches[1]) {
//                 'admin' => 'coladmin',
//                 'UnivAdmin' => 'univadmin',
//                 'User' => 'user',
//             };
//         } else {
//             echo "No match found.";
//             exit();
//         }

//         $code = 0;
//         $email = $_SESSION['email']; //getting this email using session
//         $encpass = password_hash($password, PASSWORD_BCRYPT);

//         // update the password in the user table
//         if ($userType == 'user') {
//             // Matches all the usernames that have the same personID in student and alumni
//             $update_pass = "UPDATE user AS U 
//             JOIN (
//                 SELECT DISTINCT a.username AS username
//                 FROM alumni AS a
//                 WHERE a.personID = '$personID'
//                 UNION
//                 SELECT DISTINCT s.username AS username
//                 FROM student AS s
//                 WHERE s.personID = '$personID'
//             ) as matches
//             on u.username = matches.username
//             set u.password = '$password'
//             ";
//         } else {
//             $update_pass = "UPDATE `user` AS u INNER JOIN `$userType` AS p ON u.username = p.username
//             SET u.password = '$password' 
//             WHERE p.personID = '$personID'";
//         }
//         $run_query = mysqli_query($mysql_con, $update_pass);
//         if ($run_query) {
//             $info = "Your password changed. Now you can login with your new password.";
//             $_SESSION['info'] = $info;
//             header('Location: password-changed.php');
//         } else {
//             $errors['db-error'] = "Failed to change your password!";
//         }
//     }
// }
