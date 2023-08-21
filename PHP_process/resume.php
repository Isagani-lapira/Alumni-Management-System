<?php

require_once 'connection.php';
if (isset($_POST['action'])) {
    $action = $_POST['action'];
    if ($action == 'insertData') {
        insertionOfData($mysql_con);
    } else if ($action == 'retrievalData') {
        retrieveResume($mysql_con);
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
        $query = "INSERT INTO `resume`(`resumeID`, `personID`, `objective`, `fullname`, `contactNo`, `address`, `emailAdd`)
          VALUES (\"$resumeID\",\"$personID\",\"$objective\",\"$fullname\",\"$contactNo\",\"$address\",\"$emailAdd\")";
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
                                $responsibility = $workEntry['responsibility'];
                                $workID = substr(md5(uniqid()), 0, 10) . '-' . rand(0, 5000);
                                // query the insertion
                                $query = 'INSERT INTO `work_exp`(`workID`, `resumeID`, `job_title`, `companyName`, `work_description`, `year`) 
                                VALUES ("' . $workID . '","' . $resumeID . '","' . $jobTitle . '","' . $companyName . '","' . $responsibility . '","' . $year . '")';
                                $workExp = mysqli_query($con, $query);

                                if ($workExp) $checker = true;
                            }
                            if ($checker) {

                                if (insertSkill($con, $resumeID)) {
                                    $resultQuery = insertReference($con, $resumeID);

                                    if ($resultQuery) echo 'Successful';
                                    else echo 'Failed';
                                } else echo 'Failed';
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


function insertSkill($con, $resumeID)
{
    $skills = json_decode($_POST['skills'], true);

    $response = false;
    //traverse to insert every skill
    foreach ($skills as $skill) {
        $skillID = substr(md5(uniqid()), 0, 10) . '-' . rand(0, 5000);
        $query = "INSERT INTO `resume_skill`(`skillID`, `resumeID`, `skill`) 
                                        VALUES (\"$skillID\",\"$resumeID\",\"$skill\")";
        if (mysqli_query($con, $query)) $response = true;
        else $response = false;
    }

    return $response;
}

function insertReference($con, $resumeID)
{
    $resultQuery = false;
    //for reference table
    $references = json_decode($_POST['references'], true);
    //insert the reference details

    foreach ($references as $reference) {
        $fullname = $reference['fullname'];
        $jobTitle = $reference['jobTitle'];
        $contactNo = $reference['contactNo'];
        $emailAdd = $reference['emailAdd'];

        $referenceID = substr(md5(uniqid()), 0, 10) . '-' . rand(0, 5000);
        //insertion of reference
        $queryReferences = "INSERT INTO `reference_resume`(`referenceID`, `resumeID`, `reference_name`, 
            `job_title`, `contactNo`, `emailAddress`) VALUES ('$referenceID','$resumeID','$fullname',
            '$jobTitle','$contactNo','$emailAdd')";

        if (mysqli_query($con, $queryReferences)) $resultQuery = true;
        else $resultQuery = false;
    }

    return $resultQuery;
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

function retrieveResume($con)
{
    session_start();
    $personID = $_SESSION['personID'];
    $query = "SELECT * FROM `resume` WHERE `personID` = '$personID'";
    $result = mysqli_query($con, $query);
    $row = mysqli_num_rows($result);

    //data to be retrieve
    $response = "";
    $objective = "";
    $fullname = "";
    $contactNo = "";
    $address = "";
    $emailAdd = "";
    $skills = array();
    $educations = array();
    $educLvl = array();
    $degree = array();
    $year = array();
    $workExpirience = null;
    $referenceData = array();

    if ($result && $row > 0) {
        $response = "Success"; //meaning there's an existing resume
        $data = mysqli_fetch_assoc($result);

        $resumeID = $data['resumeID'];
        $objective = $data['objective'];
        $fullname = $data['fullname'];
        $contactNo = $data['contactNo'];
        $address = $data['address'];
        $emailAdd = $data['emailAdd'];

        //get the resume Skill
        $querySkill = "SELECT * FROM `resume_skill` WHERE `resumeID` = '$resumeID'";
        $resultSkill = mysqli_query($con, $querySkill);

        //get all the skill that has this resume ID
        while ($skill = mysqli_fetch_assoc($resultSkill)) {
            $skills[] = $skill['skill'];
        }

        //get the resume education
        $queryEducation = "SELECT * FROM `education` WHERE `resumeID` = '$resumeID'";
        $resultEducation = mysqli_query($con, $queryEducation);

        //get all the skill that has this resume ID
        while ($education = mysqli_fetch_assoc($resultEducation)) {
            $educLvl[] = $education['education_level'];
            $degree[] = $education['degree'];
            $year[] = $education['year'];
        }

        $educations =  array(
            "educationLevel" => $educLvl,
            "degree" => $degree,
            'year' => $year
        );

        //get work experience
        $queryWorkExp = "SELECT * FROM `work_exp` WHERE `resumeID` = '$resumeID'";
        $resultWorkExp = mysqli_query($con, $queryWorkExp);
        $rowWork = mysqli_num_rows($resultWorkExp);

        $jobTitle = array();
        $companyName = array();
        $workDescript = array();
        $yearWork = array();

        if ($resultWorkExp && $rowWork > 0) {
            while ($data = mysqli_fetch_assoc($resultWorkExp)) {
                $jobTitle[] = $data['job_title'];
                $companyName[] = $data['companyName'];
                $workDescript[] = $data['work_description'];
                $yearWork[] = $data['year'];
            }

            $workExpirience = array(
                "jobTitle" => $jobTitle,
                "companyName" => $companyName,
                "workDescript" => $workDescript,
                "year" => $yearWork
            );
        }


        //get references
        $queryReferences = "SELECT * FROM `reference_resume` WHERE `resumeID` = '$resumeID'";
        $resultReference = mysqli_query($con, $queryReferences);

        $refFullname = array();
        $refJobTitle = array();
        $refContactNo = array();
        $refEmailAdd = array();

        while ($data = mysqli_fetch_assoc($resultReference)) {
            $refFullname[] = $data['reference_name'];
            $refJobTitle[] = $data['job_title'];
            $refContactNo[] = $data['contactNo'];
            $refEmailAdd[] = $data['emailAddress'];
        }

        $referenceData = array(
            "fullname" => $refFullname,
            "jobTitle" => $refJobTitle,
            "contactNo" => $refContactNo,
            "emailAdd" => $refEmailAdd,
        );
    } else $response = "Failed";

    //send back the data as json
    $data = array(
        "response" => $response,
        "objective" => $objective,
        "fullname" => $fullname,
        "contactNo" => $contactNo,
        "address" => $address,
        "emailAdd" => $emailAdd,
        "skills" => $skills,
        "education" => $educations,
        "workExp" => $workExpirience,
        "references" => $referenceData
    );

    echo json_encode($data);
}

function getResumeID($con)
{
    $personID = $_SESSION['personID'];
    $query = "SELECT * FROM `resume` WHERE `personID` = '$personID'";
    $result = mysqli_query($con, $query);

    $data = mysqli_fetch_assoc($result);
    $resumeID = $data['resumeID'];

    return $resumeID;
}
