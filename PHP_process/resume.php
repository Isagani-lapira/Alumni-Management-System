<?php

session_start();
require_once 'connection.php';

if (isset($_POST['action'])) {
    $action = $_POST['action'];
    if ($action == 'insertData') {
        insertionOfData($mysql_con);
    }
} else echo 'ayaw';

function insertionOfData($con)
{
    $personID = $_SESSION['personID'];
    $random = rand(0, 5000);
    $uniqID = substr(md5(uniqid()), 0, 10);
    $resumeID = $random . '-' . $uniqID;
    $objective = $_POST['objective'];
    $fullname = $_POST['firstname'] . ' ' . $_POST['lastname'];
    $contactNo = $_POST['contactNo'];
    $address = $_POST['address'];
    $emailAdd = $_POST['emailAdd'];

    $primaryJSON = $_POST['primary'];
    $primaryArray = json_decode($primaryJSON, true);

    $secondaryJSON = $_POST['secondary'];
    $secondaryArray = json_decode($secondaryJSON, true);

    $tertiaryJSON = $_POST['tertiary'];
    $tertiaryArray = json_decode($tertiaryJSON, true);

    if (haveResume($personID, $con)) {
        //update only

    } else {
        //perform insertion
        $query = "INSERT INTO `resume`(`resumeID`, `personID`, `objective`, `fullname`, `contactNo`, 
        `address`, `emailAdd`) VALUES ('$resumeID','$personID','$objective','$fullname','$contactNo','$address','$emailAdd')";
        $result = mysqli_query($con, $query);

        $educationLevel = "Primary education";
        if ($result) {
            //change education level as it goes to upward
            if (educationResume($resumeID, $primaryArray, $educationLevel, $con)) {
                $educationLevel = "Secondary education";
                if (educationResume($resumeID, $secondaryArray, $educationLevel, $con)) {
                    $educationLevel = "Tertiary education";
                    if (educationResume($resumeID, $tertiaryArray, $educationLevel, $con)) {
                        //insert the value for work
                        $work = json_decode($_POST['work'], true);
                        $count = count($work);

                        if ($count > 0) {
                            $checker = false;
                            //traverse the data of work
                            foreach ($work as $workEntry) {
                                $jobTitle = $workEntry['jobTitle'];
                                $companyName = $workEntry['companyName'];
                                $year = $workEntry['year'];
                                $workID = substr(md5(uniqid()), 0, 10) . '-' . rand(0, 5000);
                                // query the insertion
                                $query = "INSERT INTO `work_exp`(`workID`, `resumeID`, `job_title`, `companyName`, `year`) 
                                VALUES ('$workID','$resumeID','$jobTitle','$companyName','$year')";
                                $workExp = mysqli_query($con, $query);

                                if ($workExp) $checker = true;
                            }
                            if ($checker) {
                                $skills = json_decode($_POST['skills'], true);

                                $response = false;
                                //traverse to insert every skill
                                foreach ($skills as $skill) {
                                    $skillID = substr(md5(uniqid()), 0, 10) . '-' . rand(0, 5000);
                                    $query = "INSERT INTO `resume_skill`(`skillID`, `resumeID`, `skill`) 
                                        VALUES ('$skillID','$resumeID','$skill')";
                                    if (mysqli_query($con, $query))
                                        $response = true;
                                }
                                if ($response) echo 'Successful';
                                else echo 'Failed';
                            }
                        } else { //skip the work experience
                            echo 'ayaw';
                        }
                    } else echo 'failed';
                }
            }
        } else echo 'Failed';
    }
}

function educationResume($resumeID, $school, $educationLevel, $con)
{
    $random = rand(0, 5000);
    $uniqID = substr(md5(uniqid()), 0, 10);
    $educID = $random . '-' . $uniqID;

    $degree = $school[0];
    $year = $school[1] . '-' . $school[2];
    $query = "INSERT INTO `education`(`educationID`, `resumeID`,`education_level`, `degree`, `year`) 
    VALUES ('$educID','$resumeID','$educationLevel','$degree','$year')";
    $result = mysqli_query($con, $query);

    if ($result) return true;
    else return false;
}
//check if the user already have resume
function haveResume($personID, $con)
{
    $query = "SELECT * FROM `resume` WHERE `personID` = '$personID'";
    $result = mysqli_query($con, $query);
    $row = mysqli_num_rows($result);

    if ($result && $row > 0) return true;
    else return false;
}
