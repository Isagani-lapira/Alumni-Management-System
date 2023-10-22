<?php
session_start();
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;



require "../PHP_process/connection.php";



require_once "../PHP_process/phpmailer/src/PHPMailer.php";
require_once "../PHP_process/phpmailer/src/SMTP.php";
require_once "../PHP_process/phpmailer/src/Exception.php";
require_once "../vendor/autoload.php";



$email = "";
$name = "";
$errors = array();

//if user click button in forgot password form
if (isset($_POST['check-email'])) {
    $email = mysqli_real_escape_string($mysql_con, $_POST['email']);
    $check_email = "SELECT personal_email,personID FROM person WHERE personal_email='$email'";
    $run_sql = mysqli_query($mysql_con, $check_email);

    if (mysqli_num_rows($run_sql) > 0) {


        // get the personID
        $row = mysqli_fetch_array($run_sql);
        $personID = $row['personID'];

        // Generate a random code
        $code = rand(100000, 999999); // Generates a 6-digit code
        // Calculate the code expiration time (e.g., 1 hour from now)
        $code_expiration = date('Y-m-d H:i:s', strtotime('+1 hour'));


        $hashedCode = password_hash($code, PASSWORD_DEFAULT);


        // Check if there's an existing record in the password_recovery table for the user
        $checkExistingQuery = "SELECT personID FROM password_resets WHERE personID = '$personID'";
        $result = mysqli_query($mysql_con, $checkExistingQuery);
        $sql_query = '';
        if (mysqli_num_rows($result) == 1) {
            // Update the existing record with the new code and expiration
            // echo "Updating the existing record";
            $sql_query = "UPDATE  password_resets SET code='$hashedCode', code_expiration='$code_expiration' WHERE personID='$personID'";
        } else {
            // Insert a new record for the user
            // echo "Inserting a new record";
            $sql_query = "INSERT INTO  password_resets (personID, code, code_expiration) VALUES ('$personID', '$hashedCode', '$code_expiration');";
        }
        $run_query =  mysqli_query($mysql_con, $sql_query);


        // echo "Running the mail function";
        // Emails the user the reset code
        if ($run_query) {
            try {
                $mail = new PHPMailer(true);
                $mail->SMTPDebug = 0;
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                // ! Add the username and password first
                $mail->Username = 'bulsualumnioffice@gmail.com';
                $mail->Password = 'vloe qwyq tyxi tacv';
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                $mail->setFrom('wiltestrun458@gmail.com', 'Alumni Coordinator');
                $mail->addAddress($email, $name);
                $mail->Subject = 'Password Reset Code';
                $mail->Body = "Your password reset code is $code. This is available for 1 hour only.";
                $mail->AltBody = "Your password reset code is $code. The code is only available for an hour. .";
                $mail->send();
                $info = "Mail has been sent successfully! We've sent a password reset otp to your email - $email";
                $_SESSION['info'] = $info;
                $_SESSION['email'] = $email;
                $_SESSION['personID'] = $personID;
                header('location: reset-code.php');
                echo "Mail has been sent successfully!";
                exit();
            } catch (Exception $error) {
                $errors['otp-error'] = "Failed sending the email!! Mailer Error: {$error->errorMessage()} ";

                // echo "Message could not be sent. Mailer Error: {$error->errorMessage()}";
            }
        } else {
            $errors['db-error'] = "Something went wrong!";
        }
    } else {
        $errors['email'] = "This email address does not exist in our database! Please try again. Use your personal email address";
    }
}

//if user click check reset otp button
if (isset($_POST['check-reset-otp'])) {
    $_SESSION['info'] = "";

    $otp_code = mysqli_real_escape_string($mysql_con, $_POST['otp']);

    $personID = $_SESSION['personID'];
    $query = "SELECT * FROM password_resets WHERE personID = '$personID' ";


    try {
        $code_res = mysqli_query($mysql_con, $query);
        if (mysqli_num_rows($code_res) > 0) {



            $fetch_data = mysqli_fetch_assoc($code_res);

            // Check if the code is still valid and not expired
            $current_time = date('Y-m-d H:i:s');
            $code_expiration = $fetch_data['code_expiration'];
            if ($code_expiration < $current_time) {
                $errors['otp-error'] = "Your reset code has expired! Please request a new one.";
            } else {
                // compare the code with the one in the database
                $code = $fetch_data['code'];
                if (password_verify($otp_code, $code)) {
                    $info = "Please create a new password..";
                    $_SESSION['info'] = $info;
                    $_SESSION['email'] = $email;
                    header('location: new-password.php');
                    exit();
                } else {
                    $errors['otp-error'] = "You've entered incorrect code!" . $_SESSION['personID'];
                }
            }
        } else {
            $errors['otp-error'] = "Can't find your code! Please try again." . mysqli_error($mysql_con);
        }
    } catch (\Throwable $th) {
        $errors['otp-error'] = "Something went wrong! " . $th->getMessage();
    }
}

//if user click change password button
if (isset($_POST['change-password'])) {
    $_SESSION['info'] = "";
    $password = mysqli_real_escape_string($mysql_con, $_POST['password']);
    $cpassword = mysqli_real_escape_string($mysql_con, $_POST['cpassword']);
    if ($password !== $cpassword) {
        $errors['password'] = "passwords do not match!";
    } else {
        // dissect the personID, get the type of user
        $pattern = '/^([a-zA-Z]+)/'; // This regular expression matches the first letters ('admin', 'UnivAdmin', 'User').
        $userType = '';
        $personID = $_SESSION['personID'];
        if (preg_match($pattern, $_SESSION['personID'], $matches)) {
            $userType = match ($matches[1]) {
                'admin' => 'coladmin',
                'UnivAdmin' => 'univadmin',
                'User' => 'user',
            };
        } else {
            echo "No match found.";
            exit();
        }

        $code = 0;
        $email = $_SESSION['email']; //getting this email using session
        $encpass = password_hash($password, PASSWORD_BCRYPT);

        // update the password in the user table
        if ($userType == 'user') {
            // Matches all the usernames that have the same personID in student and alumni
            $update_pass = "UPDATE user AS U 
            JOIN (
                SELECT DISTINCT a.username AS username
                FROM alumni AS a
                WHERE a.personID = '$personID'
                UNION
                SELECT DISTINCT s.username AS username
                FROM student AS s
                WHERE s.personID = '$personID'
            ) as matches
            on u.username = matches.username
            set u.password = '$password'
            ";
        } else {
            $update_pass = "UPDATE `user` AS u INNER JOIN `$userType` AS p ON u.username = p.username
            SET u.password = '$password' 
            WHERE p.personID = '$personID'";
        }
        $run_query = mysqli_query($mysql_con, $update_pass);
        if ($run_query) {
            $info = "Your password changed. Now you can login with your new password.";
            $_SESSION['info'] = $info;
            header('Location: password-changed.php');
        } else {
            $errors['db-error'] = "Failed to change your password!";
        }
    }
}
