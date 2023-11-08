
<?php

session_start();

require "../php/connection.php";
require "../php/logging.php";


// if server is get, and the action is get-courses
if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    if (isset($_GET['action'])) {

        if ($_GET['action'] === 'get-courses' && isset($_GET['colCode'])) {
            $colCode = $_GET['colCode'];

            // get all the courses
            // get the course

            $stmt = $mysql_con->prepare("SELECT * FROM course WHere colCode = ?;");
            $stmt->bind_param("s", $colCode);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
            $courses = array();
            while ($row = $result->fetch_assoc()) {
                array_push($courses, $row);
            }
            echo json_encode(
                array(
                    "message" => "Get data",
                    "status" => true,
                    "response" => "success",
                    "data" => $courses
                )
            );
        }
    }
    exit();
}




if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['post-new-course'])) {
        try {

            $courseCode = $_POST['courseCode'];
            $courseName = $_POST['courseName'];
            $colCode = $_SESSION['colCode'];

            $stmt = $mysql_con->prepare("INSERT INTO course (courseCode, courseName, colCode) VALUES (?, ?, ?);");
            $stmt->bind_param("sss", $courseCode, $courseName, $colCode);
            $stmt->execute();
            $stmt->close();
            // return json response
            setNewActivity(
                $mysql_con,
                $_SESSION['adminID'],
                "added",
                "Added New Course"
            );
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
                    "status" => false,
                    "response" => "failed",
                    "error" => $th->getMessage()
                )
            );
        }
    } else if (isset($_POST['update-course'])) {
        try {


            // update a course
            $courseCode = $_POST['courseCode'];
            $courseName = $_POST['courseName'];
            $colCode = $_SESSION['colCode'];
            // course id
            $courseID = $_POST['courseID'];

            $stmt = $mysql_con->prepare("UPDATE course SET courseCode = ?, courseName = ?, colCode = ? WHERE courseID = ?;");
            $stmt->bind_param("ssss", $courseCode, $courseName, $colCode, $courseID);
            $stmt->execute();
            $stmt->close();


            // return json response
            setNewActivity(
                $mysql_con,
                $_SESSION['adminID'],
                "updated",
                "Updated a course"
            );
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
                    "status" => false,
                    "response" => "failed",
                    "error" => $th->getMessage()
                )
            );
        }
    }


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
            setNewActivity(
                $mysql_con,
                $_SESSION['adminID'],
                "updated",
                "Updated Personal Information"
            );
        } catch (\Throwable $th) {
            throw $th;
        }



        // update the person table







        echo json_encode(
            array(
                "message" => "Post data",
                "status" => true,
                "response" => "success"

            )
        );
    } else  if (isset($_POST['update-college-form'])) {



        //"UPDATE `college` SET `colCode`='[value-1]',`colname`='[value-2]',`colEmailAdd`='[value-3]',`colContactNo`='[value-4]',`colWebLink`='[value-5]',`colLogo`='[value-6]',`colDean`='[value-7]',`colDeanImg`='[value-8]',`description`='[value-9]' WHERE 1";

        $colCode = $_SESSION['colCode'];

        $colName = $_POST['colName'];
        $colEmailAdd = $_POST['colEmailAdd'];
        $colContactNo = $_POST['colContactNo'];
        $colWebLink = $_POST['colWebLink'];
        $colDean = $_POST['colDean'];
        $description = $_POST['description'];

        $colLogoData = '';
        $colDeanImgData = '';

        if (isset($_FILES['colLogo']) && $_FILES['colLogo']['error'] === UPLOAD_ERR_OK && isset($_FILES['colDeanImg']) && $_FILES['colDeanImg']['error'] === UPLOAD_ERR_OK) {
            // there are logo and dean image uploaded
            $colLogo = $_FILES['colLogo']['tmp_name'];
            $colLogoData = file_get_contents($colLogo);


            $colDeanImg = $_FILES['colDeanImg']['tmp_name'];
            $colDeanImgData = file_get_contents($colDeanImg);


            $stmt = $mysql_con->prepare("UPDATE college SET  colname = ?, colEmailAdd = ?, colContactNo = ?, colWebLink = ?, colLogo = ?, colDean = ?, colDeanImg = ?, description = ? WHERE colCode = ?;");
            // *  Binds the variable to the '?', prevents sql injection
            $stmt->bind_param(
                "sssssssss",
                $colName,
                $colEmailAdd,
                $colContactNo,
                $colWebLink,
                $colLogoData,
                $colDean,
                $colDeanImgData,
                $description,
                $colCode
            );
        } else if (
            // there is only logo uploaded
            isset($_FILES['colLogo']) && $_FILES['colLogo']['error'] === UPLOAD_ERR_OK
        ) {
            $colLogo = $_FILES['colLogo']['tmp_name'];
            $colLogoData = file_get_contents($colLogo);


            $stmt = $mysql_con->prepare("UPDATE college SET  colname = ?, colEmailAdd = ?, colContactNo = ?, colWebLink = ?, colLogo = ?, colDean = ?,  description = ? WHERE colCode = ?;");
            // *  Binds the variable to the '?', prevents sql injection
            $stmt->bind_param(
                "ssssssss",
                $colName,
                $colEmailAdd,
                $colContactNo,
                $colWebLink,
                $colLogoData,
                $colDean,
                $description,
                $colCode
            );
        } else if (
            isset($_FILES['colDeanImg']) && $_FILES['colDeanImg']['error'] === UPLOAD_ERR_OK
        ) {
            $colDeanImg = $_FILES['colDeanImg']['tmp_name'];
            $colDeanImgData = file_get_contents($colDeanImg);


            $stmt = $mysql_con->prepare("UPDATE college SET  colname = ?, colEmailAdd = ?, colContactNo = ?, colWebLink = ?,  colDean = ?, colDeanImg = ?, description = ? WHERE colCode = ?;");
            // *  Binds the variable to the '?', prevents sql injection
            $stmt->bind_param(
                "ssssssss",
                $colName,
                $colEmailAdd,
                $colContactNo,
                $colWebLink,
                $colDean,
                $colDeanImgData,
                $description,
                $colCode
            );
        } else {
            // there is no image uploaded
            $stmt = $mysql_con->prepare("UPDATE college SET  colname = ?, colEmailAdd = ?, colContactNo = ?, colWebLink = ?, colDean = ?, description = ? WHERE colCode = ?;");
            // *  Binds the variable to the '?', prevents sql injection
            $stmt->bind_param(
                "sssssss",
                $colName,
                $colEmailAdd,
                $colContactNo,
                $colWebLink,
                $colDean,
                $description,
                $colCode
            );
        }
        //  make a query to update the college table using mysqli prepared statement
        try {
            $stmt->execute();
            // return json response
            setNewActivity(
                $mysql_con,
                $_SESSION['adminID'],
                "updated",
                "Updated College Information"
            );
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
                    "status" => false,
                    "response" => "failed",
                    "error" => $th->getMessage()
                )
            );
        }
        $stmt->close();
    } else {
        echo json_encode(
            array(
                "message" => "Post data",
                "status" => false,
                "response" => "failed"
            )
        );
    }
}
