
<?php

session_start();

require "../php/connection.php";
require "../php/logging.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['update-college-form'])) {



        if (isset($_FILES['cover-img']) && $_FILES['cover-img']['error'] === UPLOAD_ERR_OK) {
            $coverImage = $_FILES['cover-img']['tmp_name'];
            $coverImageData = file_get_contents($coverImage);
        }

        if (isset($_FILES['personal-img']) && $_FILES['personal-img']['error'] === UPLOAD_ERR_OK) {
            $profilePicture = $_FILES['personal-img']['tmp_name'];
            $profileImageData = file_get_contents($profilePicture);
        }


        try {
            //code...        $mysql_con->stmt_init();
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
            // execute the query
            $stmt->execute();
        } catch (\Throwable $th) {
            throw $th;
        }



        // update the person table





        setNewActivity(
            $mysql_con,
            $_SESSION['colAdmin'],
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
