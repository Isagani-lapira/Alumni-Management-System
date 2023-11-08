<?php

session_start();

require "../php/connection.php";
require "../php/logging.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['personal-info-form'])) {


        /**
         * array(11) { ["personal-info-form"]=> 
         * string(4) "true" ["firstName"]=> string(6) 
         * "Jayson" ["lastName"]=> string(6) "Batoon"
         *  ["birthday"]=> string(10) "2023-07-17" 
         * ["gender"]=> string(4) "male" 
         * ["address"]=> string(16) "Marilao, Bulacan" 
         * ["contactNo"]=> string(11) "09104905440" 
         * ["facebook"]=> string(0) ""
         *  ["instagram"]=> string(0) "" 
         * ["twitter"]=> string(0) "" 
         * ["linkedin"]=> string(0) "" } 
         * cover-image
         * {"message":"Post data","status":true,"data":[null]}
         * 
         */
        //  get the form data based from the array above 


        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $birthday = $_POST['birthday'];
        $gender = $_POST['gender'];
        $address = $_POST['address'];
        $contactNo = $_POST['contactNo'];
        $facebook = $_POST['facebook'];
        $instagram = $_POST['instagram'];
        $twitter = $_POST['twitter'];
        $linkedin = $_POST['linkedin'];
        $instagram = $_POST['instagram'];

        $personID = $_SESSION['personID'];

        $coverImageData = '';
        $profileImageData = '';

        try {
            // if there is cover and profile
            if (
                isset($_FILES['cover-img']) && $_FILES['cover-img']['error'] === UPLOAD_ERR_OK
                &&  isset($_FILES['personal-img']) && $_FILES['personal-img']['error'] === UPLOAD_ERR_OK
            ) {

                $coverImage = $_FILES['cover-img']['tmp_name'];
                $coverImageData = file_get_contents($coverImage);
                $profilePicture = $_FILES['personal-img']['tmp_name'];
                $profileImageData = file_get_contents($profilePicture);
                $stmt = $mysql_con->prepare("UPDATE person
        SET
            fname = ?,
            lname = ?,
            bday = ?,
            gender = ?,
            contactNo = ?,
            address = ?,
            profilepicture = ?,
            cover_photo = ?,
            facebookUN = ?,
            instagramUN = ?,
            twitterUN = ?,
            linkedInUN = ?
        WHERE personID = ?;");

                // *  Binds the variable to the '?', prevents sql injection
                $stmt->bind_param(
                    "sssssssssssss",
                    $firstName,
                    $lastName,
                    $birthday,
                    $gender,
                    $contactNo,
                    $address,
                    $profileImageData,
                    $coverImageData,
                    $facebookUN,
                    $instagramUN,
                    $twitterUN,
                    $linkedInUN,
                    $personID
                );
            } else  if (isset($_FILES['cover-img']) && $_FILES['cover-img']['error'] === UPLOAD_ERR_OK) {
                $coverImage = $_FILES['cover-img']['tmp_name'];
                $coverImageData = file_get_contents($coverImage);
                $stmt = $mysql_con->prepare("UPDATE person
        SET
            fname = ?,
            lname = ?,
            bday = ?,
            gender = ?,
            contactNo = ?,
            address = ?,
            cover_photo = ?,
            facebookUN = ?,
            instagramUN = ?,
            twitterUN = ?,
            linkedInUN = ?
        WHERE personID = ?;");

                // *  Binds the variable to the '?', prevents sql injection
                $stmt->bind_param(
                    "ssssssssssss",
                    $firstName,
                    $lastName,
                    $birthday,
                    $gender,
                    $contactNo,
                    $address,
                    $coverImageData,
                    $facebookUN,
                    $instagramUN,
                    $twitterUN,
                    $linkedInUN,
                    $personID
                );
            } else if (isset($_FILES['personal-img']) && $_FILES['personal-img']['error'] === UPLOAD_ERR_OK) {
                $profilePicture = $_FILES['personal-img']['tmp_name'];
                $profileImageData = file_get_contents($profilePicture);
                $stmt = $mysql_con->prepare("UPDATE person
        SET
            fname = ?,
            lname = ?,
            bday = ?,
            gender = ?,
            contactNo = ?,
            address = ?,
            profilepicture = ?,
            facebookUN = ?,
            instagramUN = ?,
            twitterUN = ?,
            linkedInUN = ?
        WHERE personID = ?;");

                // *  Binds the variable to the '?', prevents sql injection
                $stmt->bind_param(
                    "ssssssssssss",
                    $firstName,
                    $lastName,
                    $birthday,
                    $gender,
                    $contactNo,
                    $address,
                    $profileImageData,
                    $facebookUN,
                    $instagramUN,
                    $twitterUN,
                    $linkedInUN,
                    $personID
                );
            }
            // if there's no images
            else {

                $stmt = $mysql_con->prepare("UPDATE person
        SET
            fname = ?,
            lname = ?,
            bday = ?,
            gender = ?,
            contactNo = ?,
            address = ?,
            facebookUN = ?,
            instagramUN = ?,
            twitterUN = ?,
            linkedInUN = ?
        WHERE personID = ?;");

                // *  Binds the variable to the '?', prevents sql injection
                $stmt->bind_param(
                    "sssssssssss",
                    $firstName,
                    $lastName,
                    $birthday,
                    $gender,
                    $contactNo,
                    $address,
                    $facebookUN,
                    $instagramUN,
                    $twitterUN,
                    $linkedInUN,
                    $personID
                );
            }


            //code...        $mysql_con->stmt_init();

            // execute the query
            $stmt->execute();
            setNewActivity(
                $mysql_con,
                $_SESSION['adminID'],
                "Update",
                "Updated Personal Information"
            );

            // update the session information
            $_SESSION['fname'] = $firstName;
            $_SESSION['lname'] = $lastName;
            // get the full name
            $fullName = $firstName . " " . $lastName;

            // session full name 
            $_SESSION['fullName'] = $fullName;



            echo json_encode(
                array(
                    "message" => "Post data",
                    "status" => true,
                    "response" => "success"

                )
            );
        } catch (\Throwable $th) {

            echo json_encode(
                array(
                    "message" => "Post data",
                    "status" => true,
                    "response" => "fail",
                    "error" => $th

                )
            );
        }



        // update the person table








    } else   if (isset($_POST['general-settings-form'])) {

        $adminID = $_SESSION['adminID'];
        $personID = $_SESSION['personID'];

        // get the form data
        $email = $_POST['email'];
        $bulsuEmail = $_POST['bulsuemail'];

        try {
            //code...        $mysql_con->stmt_init();
            $stmt = $mysql_con->prepare("UPDATE person
        SET
            personal_email = ?,
            bulsu_email = ?
        WHERE personID = ?;");

            // *  Binds the variable to the '?', prevents sql injection
            $stmt->bind_param(
                "sss",
                $email,
                $bulsuEmail,
                $personID
            );
            // execute the query
            $stmt->execute();
            setNewActivity(
                $mysql_con,
                $_SESSION['adminID'],
                "Update",
                "Updated Personal Information"
            );

            echo json_encode(
                array(
                    "message" => "Post data",
                    "status" => true,
                    "response" => "success"

                )
            );
        } catch (\Throwable $th) {
            // throw $th;
            echo json_encode(
                array(
                    "message" => "Post data",
                    "status" => false,
                    "response" => "failed"

                )
            );
        }
    } else  if (isset($_POST['update-password-form'])) {
        // get the form data
        $oldPass = $_POST['old-password'];
        $username = $_POST['username'];

        $newPass = $_POST['password'];
        $confirmPass = $_POST['confirmPassword'];

        if ($newPass === $confirmPass) {
            $adminID = $_SESSION['adminID'];
            $stmt = $mysql_con->prepare("SELECT password FROM user WHERE username = ?;");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            // if (password_verify($oldPass, $row['password'])) {
            if ($oldPass == $row['password']) {
                $stmt = $mysql_con->prepare("UPDATE user SET password = ? WHERE username = ?;");
                // $stmt->bind_param("ss", password_hash($newPass, PASSWORD_DEFAULT), $personID);
                $stmt->bind_param("ss", $newPass, $username);
                $stmt->execute();
                setNewActivity(
                    $mysql_con,
                    $_SESSION['adminID'],
                    "Update",
                    "Updated Password"
                );
                echo json_encode(
                    array(
                        "message" => "Successfully updated password",
                        "status" => true,
                        "response" => "success"

                    )
                );
            } else {
                echo json_encode(
                    array(
                        "message" => "Password does not match the old password",
                        "status" => false,
                        "response" => "failed"

                    )
                );
            }
        } else {
            echo json_encode(
                array(
                    "message" => "Passwords does not match",
                    "status" => false,
                    "response" => "failed"

                )
            );
        }
    } else {



        echo json_encode(
            array(
                "message" => "No post data",
                "status" => false,
                "response" => "failed"
            )
        );
    }
}
