<?php
session_start();
require "../PHP_process/connection.php";
$email = "";
$name = "";
$errors = array();

//if user click button in forgot password form
if (isset($_POST['check-email'])) {
    $email = mysqli_real_escape_string($mysql_con, $_POST['email']);
    $check_email = "SELECT personal_email,personID FROM person WHERE personal_email='$email'";
    $run_sql = mysqli_query($mysql_con, $check_email);

    // Will now send the token to the user's email
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

        if (mysqli_num_rows($result) == 1) {
            // Update the existing record with the new code and expiration
            echo "Updating the existing record";
            $updateCodeQuery = "UPDATE  password_resets SET code='$hashedCode', code_expiration='$code_expiration' WHERE personID='$personID'";

            mysqli_query($mysql_con, $updateCodeQuery);
        } else {
            // Insert a new record for the user
            echo "Inserting a new record";
            $insert_code = "INSERT INTO  password_resets (personID, code, code_expiration) VALUES ('$personID', '$hashedCode', '$code_expiration');";
            $run_query =  mysqli_query($mysql_con, $insert_code);
        }


        echo "Running the mail function";
        die();
        if ($run_query) {
            $subject = "Password Reset Code";
            $message = "Your password reset code is $code";
            $sender = "From: shahiprem7890@gmail.com";
            if (mail($email, $subject, $message, $sender)) {
                $info = "We've sent a password reset otp to your email - $email";
                $_SESSION['info'] = $info;
                $_SESSION['email'] = $email;
                header('location: reset-code.php');
                exit();
            } else {
                $errors['otp-error'] = "Failed while sending code!";
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
    $check_code = "SELECT * FROM usertable WHERE code = $otp_code";
    $code_res = mysqli_query($mysql_con, $check_code);
    if (mysqli_num_rows($code_res) > 0) {
        $fetch_data = mysqli_fetch_assoc($code_res);
        $email = $fetch_data['email'];
        $_SESSION['email'] = $email;
        $info = "Please create a new password that you don't use on any other site.";
        $_SESSION['info'] = $info;
        header('location: new-password.php');
        exit();
    } else {
        $errors['otp-error'] = "You've entered incorrect code!";
    }
}

//if user click change password button
if (isset($_POST['change-password'])) {
    $_SESSION['info'] = "";
    $password = mysqli_real_escape_string($mysql_con, $_POST['password']);
    $cpassword = mysqli_real_escape_string($mysql_con, $_POST['cpassword']);
    if ($password !== $cpassword) {
        $errors['password'] = "mysql$mysql_confirm password not matched!";
    } else {
        $code = 0;
        $email = $_SESSION['email']; //getting this email using session
        $encpass = password_hash($password, PASSWORD_BCRYPT);
        $update_pass = "UPDATE usertable SET code = $code, password = '$encpass' WHERE email = '$email'";
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
