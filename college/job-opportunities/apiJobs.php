

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


    // Your parameter
    $colCode = $_SESSION["colCode"];

    // Prepare the statement
    if (true) {
        // Bind the parameter
        $stmt = $mysql_con->prepare("SELECT jobTitle, companyName, author, date_posted, status, careerID FROM `career` WHERE colCode = ?;");
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

    echo json_encode(array("error" => "Something went wrong", "message" => "Error in preparing the statement: " . $mysql_con->error));
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