

<?php

// Path: college/job-opportunities/apiJobs.php
session_start();

require "../php/connection.php";
require_once "../php/checkLogin.php";



// // check if the user is logged in
// if (!isset($_SESSION["username"])) {
//     echo json_encode(array("error" => "You are not logged in"));
//     exit();
// }


header("Content-Type: application/json");
// check request method
if ($_SERVER["REQUEST_METHOD"] === "GET") {


    $colCode = $_SESSION["colCode"];



    // if there is status parameter
    if (isset($_GET['status']) && $_GET['status'] === 'unverified') {

        $stmt = $mysql_con->prepare("SELECT jobTitle, companyName, author, date_posted, status, careerID FROM `career` WHERE colCode = ? AND status = 'unverified';");
        $stmt->bind_param("s", $colCode);
        // Execute the statement
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();
        $data = array();

        // Fetch the rows
        while ($row = $result->fetch_assoc()) {
            // Process each row as needed
            // $row contains the data from the database
            $data[] = $row;
        }


        $stmt->close();
        echo json_encode(array("error" => false, "data" => $data, "message" => "Success", "status" => true));
    } else   if (isset($_GET['status']) && $_GET['status'] === 'all') {
        // Bind the parameter
        $stmt = $mysql_con->prepare("SELECT jobTitle, companyName, author, date_posted, status, careerID FROM `career` WHERE colCode = ? ;");
        $stmt->bind_param("s", $colCode);
        // Execute the statement
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();
        $data = array();

        // Fetch the rows
        while ($row = $result->fetch_assoc()) {
            // Process each row as needed
            // $row contains the data from the database
            $data[] = $row;
        }

        // Close the statement

        $stmt->close();
        echo json_encode(array("error" => false, "data" => $data, "message" => "Success", "status" => true));
    }

    // get all the verified status
    else   if (isset($_GET['status']) && $_GET['status'] === 'verified') {
        // Bind the parameter
        $stmt = $mysql_con->prepare("SELECT jobTitle, companyName, author, date_posted, status, careerID FROM `career` WHERE colCode = ? AND status = 'verified' ;");
        $stmt->bind_param("s", $colCode);
        // Execute the statement
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();
        $data = array();

        // Fetch the rows
        while ($row = $result->fetch_assoc()) {
            // Process each row as needed
            // $row contains the data from the database
            $data[] = $row;
        }

        // Close the statement

        $stmt->close();
        echo json_encode(array("error" => false, "data" => $data, "message" => "Success", "status" => true));
    } else {
        echo json_encode(array("error" => "Something went wrong", "message" => "Error in preparing the statement: " . $mysql_con->error));
    }
} else if ($_SERVER['REQUEST_METHOD'] === "POST") {

    if (isset($_POST['create-new']) && $_POST['create-new'] === 'post') {

        /** POST request  */
        //    ["create-new"]=>
        //   string(4) "post"
        //   ["jobTitle"]=>
        //   string(4) "asdf"
        //   ["projDescriptTxt"]=>
        //   string(4) "asdf"
        //   ["companyName"]=>
        //   string(4) "asdf"
        //   ["jobLocation"]=>
        //   string(4) "asdf"
        //   ["qualificationTxt"]=>
        //   string(3) "adf"
        //   ["minSalary"]=>
        //   string(6) "123123"
        //   ["maxSalary"]=>
        //   string(6) "123123"

        // get the data from the form
        $jobTitle = $_POST['jobTitle'];
        $projDescriptTxt = $_POST['projDescriptTxt'];

        $companyName = $_POST['companyName'];
        $jobLocation = $_POST['jobLocation'];
        $qualificationTxt = $_POST['qualificationTxt'];
        $minSalary = $_POST['minSalary'];
        $maxSalary = $_POST['maxSalary'];

        $image = '';
        // check if there's a file uploaded
        if (isset($_FILES['jobLogoInput']) && $_FILES['jobLogoInput']['size'] > 0) {
            $image = file_get_contents($_FILES['jobLogoInput']['tmp_name']);
        } else {
            // $image = null;
        }

        // get the skills
        $skill1 = $_POST['inputSkill1'];
        $skill2 = $_POST['inputSkill2'];
        $skill3 = $_POST['inputSkill3'];


        try {
            //code...

            $stmt = $mysql_con->prepare("INSERT INTO `career` (jobTitle, companyName,jobDescript , jobqualification, companyLogo, minSalary, maxSalary, colCode, author, status, location,careerID,personID,date_posted) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?, CURDATE());");

            //for career ID
            $uniqueId = substr(md5(uniqid()), 0, 7); //unique id with length of 7
            $careerID = 'career' . $uniqueId;
            $status = 'verified';
            $personID  = $_SESSION['personID'];

            $stmt->bind_param("sssssssssssss", $jobTitle, $companyName, $projDescriptTxt, $qualificationTxt, $image, $minSalary, $maxSalary, $_SESSION['colCode'], $_SESSION['username'], $status, $jobLocation, $careerID, $personID);
            $stmt->execute();

            // return the careerID and response
            echo json_encode(array("error" => false, "message" => "Success", "status" => true, "careerID" => $careerID));

            $stmt->close();
        } catch (\Throwable $th) {
            //throw $th;
            // return the error
            echo json_encode(array("throw" => $th->getMessage(), "error" => true, "message" => "Error in preparing the statement: " . $mysql_con->error, "status" => false));
        }
        // add to database
    }

    // echo json_encode(array("error" => "Something went wrong", "message" => "Error in preparing the statement: " . $mysql_con->error));
    else if (isset($_POST['action']) && $_POST['action'] === 'approve') {



        $careerID = $_POST['careerID'];
        $stmt = $mysql_con->prepare("UPDATE `career` SET status = 'verified' WHERE careerID = ?;");
        $stmt->bind_param("s", $careerID);
        $stmt->execute();
        $stmt->close();
        echo json_encode(array("error" => false, "message" => "Success", "status" => true));
    } else if (isset($_POST['action']) && $_POST['action'] === 'reject') {
        $careerID = $_POST['careerID'];
        $stmt = $mysql_con->prepare("UPDATE `career` SET status = 'rejected' WHERE careerID = ?;");
        $stmt->bind_param("s", $careerID);
        $stmt->execute();
        $stmt->close();
        echo json_encode(array("error" => false, "message" => "Success", "status" => true));
    } else if (isset($_POST['action']) && $_POST['action'] === 'delete') {
        $careerID = $_POST['careerID'];
        $stmt = $mysql_con->prepare("DELETE FROM `career` WHERE careerID = ?;");
        $stmt->bind_param("s", $careerID);
        $stmt->execute();
        $stmt->close();
        echo json_encode(array("error" => false, "message" => "Success", "status" => true));
    } else if (isset($_POST['action']) && $_POST['action'] === 'view') {
        $careerID = $_POST['careerID'];

        $stmt = $mysql_con->prepare("SELECT * FROM `career` WHERE careerID = ?;");
        $stmt->bind_param("s", $careerID);
        // Execute the statement
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();
        $data = array();

        // Fetch the rows
        while ($row = $result->fetch_assoc()) {
            // Process each row as needed
            // $row contains the data from the database
            $row['companyLogo'] = base64_encode($row['companyLogo']);
            $data = $row;
        }

        // Close the statement

        $stmt->close();
        echo json_encode(array("error" => false, "data" => $data, "message" => "Success", "status" => true));
    } else if (isset($_POST['action'])) {
    }
}



//  if (isset($_POST['action'])) {
//     try {
//         $data = $_POST['action'];
//         $arrayData = json_decode($data, true);
//         $action = $arrayData['action'];
//         $readCareer = new Career();
//         $apply = new Applicant();

//         if ($action == 'create') {
//             $author = $_POST['author'];

//             //skills
//             $skillData = $_POST['skills'];
//             $skillArray = json_decode($skillData, true);

//             //logo
//             $image = file_get_contents($_FILES['jobLogoInput']['tmp_name']);

//             //retrieve the value 
//             $jobTitle = $_POST['jobTitle'];
//             $companyName = $_POST['companyName'];
//             $projectDescript = $_POST['projDescriptTxt'];
//             $minSalary = $_POST['minSalary'];
//             $maxSalary = $_POST['maxSalary'];
//             $qualification = $_POST['qualificationTxt'];
//             $personID = $_POST['personID'];
//             $college = $_POST['collegeJob'];
//             $location = $_POST['jobLocation'];
//             //for career ID
//             $uniqueId = substr(md5(uniqid()), 0, 7); //unique id with length of 7
//             $careerID = 'career' . $uniqueId;

//             //insert a data 
//             $career = new Career();
//             $career->insertionJob(
//                 $careerID,
//                 $jobTitle,
//                 $companyName,
//                 $projectDescript,
//                 $qualification,
//                 $image,
//                 $minSalary,
//                 $maxSalary,
//                 $college,
//                 $author,
//                 $skillArray,
//                 $personID,
//                 $location,
//                 $mysql_con
//             );

//             if ($career) echo 'Job successfully added on the hunt';
//             else echo 'Unexpected issue: Try again later';
//         } else if ($action == 'read') {
//             $offset = $_POST['offset'];
//             $readCareer->selectData($offset, $mysql_con);
//         } else if ($action == 'readWithCol') {
//             $colCode = $arrayData['colCode'];
//             $offset = $_POST['offset'];
//             $readCareer->selectDataForCollege($colCode, $offset, $mysql_con);
//         } else if ($action == 'readWithCareerID') {
//             $careerID = $arrayData['careerID'];
//             $readCareer->selectWithCareerID($mysql_con, $careerID);
//         } else if ($action == "searching") {
//             $jobTitle = $arrayData['jobTitle'];
//             $readCareer->selectSearchJob($jobTitle, $mysql_con);
//         } else if ($action == 'readWithAuthor') {
//             $colCode = $arrayData['colCode'];
//             $offset = $_POST['offset'];
//             $readCareer->selectCareerAdmin($colCode, $offset, $mysql_con);
//         } else if ($action == "applyJob") {
//             $careerID = $_POST['careerID'];
//             $apply->resumeApplication($careerID, $mysql_con);
//         } else if ($action == 'deleteApplication') {
//             $careerID = $_POST['careerID'];
//             $username = $_SESSION['username'];
//             $apply->deleteApplication($username, $careerID, $mysql_con);
//         } else if ($action == "retrievedApplied") {
//             $offset = $_POST['offset'];
//             $readCareer->appliedJob($offset, $mysql_con);
//         } else if ($action == 'createjobuser') {
//             $author = $_SESSION['username'];

//             //skills
//             $tag1 = $_POST['tag1'];
//             $tag2 = $_POST['tag2'];
//             $tag3 = $_POST['tag3'];
//             $skillData = [$tag1, $tag2, $tag3];

//             // Encode the array as JSON
//             $skillDataJson = json_encode($skillData);
//             $skillArray = json_decode($skillDataJson, true);

//             //logo
//             $image = file_get_contents($_FILES['companyLogo']['tmp_name']);
//             //retrieve the value 
//             $jobTitle = $_POST['job_title'];
//             $companyName = $_POST['companyName'];
//             $projectDescript = $_POST['jobDesc'];
//             $minSalary = $_POST['minSalary'];
//             $maxSalary = $_POST['maxSalary'];
//             $qualification = $_POST['job_quali'];
//             $personID = $_SESSION['personID'];
//             $college = $_SESSION['colCode'];
//             $location = $_POST['jobLocation'];
//             //for career ID
//             $uniqueId = substr(md5(uniqid()), 0, 7); //unique id with length of 7
//             $careerID = 'career' . $uniqueId;

//             $status = "unverified";
//             $career = new Career();
//             $career->insertionJob(
//                 $careerID,
//                 $jobTitle,
//                 $companyName,
//                 $projectDescript,
//                 $qualification,
//                 $image,
//                 $minSalary,
//                 $maxSalary,
//                 $college,
//                 $author,
//                 $skillArray,
//                 $personID,
//                 $location,
//                 $mysql_con,
//                 $status
//             );

//             if ($career) echo 'Successful';
//             else echo 'Unexpected issue: Try again later';
//         } else if ($action == 'currentUserJobPost') {
//             $personID = $_SESSION['personID'];
//             $offset = $_POST['offset'];
//             $maxLimit = $_POST['maxLimit'];
//             $readCareer->userPost($personID, $offset, $mysql_con, $maxLimit);
//         } else if ($action == 'applicantDetails') {
//             $careerID = $_POST['careerID'];
//             $apply->getApplicantDetails($careerID, $mysql_con);
//         }
//     } catch (Exception $e) {
//         echo $e->getMessage();
//     }
// } else echo 'Action has not been yet initialized'; -->