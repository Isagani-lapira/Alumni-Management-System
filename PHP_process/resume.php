<?php

require_once 'connection.php';
if (isset($_POST['action'])) {
    $action = $_POST['action'];
    if ($action == 'insertData') {
        insertionOfData($mysql_con);
    } else if ($action == 'retrievalData') {
        retrieveResume($mysql_con);
    } else if ($action == 'haveResume') {
        session_start();
        $personID = $_SESSION['personID'];
        $result = haveResume($personID, $mysql_con);
        echo $result;
    } else if ($action == 'updateResume') {
        $table = $_POST['table'];
        $column = $_POST['column'];
        $recentVal = $_POST['recentVal'];
        $value = $_POST['value'];
        $resumeID = $_POST['resumeID'];
        updateResumeData($table, $column, $value, $resumeID, $recentVal, $mysql_con);
    } else if ($action == 'addWorkExp') {
        $resumeID = $_POST['resumeID'];
        $workArray = json_decode($_POST['workArray'], true);
        $countWork = count($workArray);
        if ($countWork > 0) {
            foreach ($workArray as $workData) {
                $jobTitle = $workData['workTitle'];
                $workDescript = $workData['workDescript'];
                $companyName = $workData['companyName'];
                $year = $workData['yearDuration'];

                // check first if there's a value first 
                if ($jobTitle != '')
                    insertWorkExperience($resumeID, $jobTitle, $companyName, $workDescript, $year, $mysql_con);
                else continue;
            }
        }
    }
} else echo 'ayaw';

function insertionOfData($con)
{
    session_start();
    $personID = $_SESSION['personID'];
    $timestamp = time();
    $uniqID = substr(md5(uniqid()), 0, 10);
    $resumeID = $timestamp . '-' . $uniqID;
    $fullname = $_POST['firstname'] . ' ' . $_POST['lastname'];
    $contactNo = $_POST['contactNo'];
    $address = $_POST['address'];
    $objective = $_POST['objective'];
    $emailAdd = $_POST['emailAdd'];
    $educArray = json_decode($_POST['educationalData'], true);
    $workArray = json_decode($_POST['workExpData'], true);
    $skillArray = json_decode($_POST['skillData'], true);
    $referenceArray = json_decode($_POST['referenceData'], true);


    //perform insertion
    $query = "INSERT INTO `resume`(`resumeID`, `personID`, `objective`, `fullname`, `contactNo`, `address`, `emailAdd`) 
    VALUES (? ,? ,? ,? ,? ,? ,? )";
    $stmt = mysqli_prepare($con, $query);
    $stmt->bind_param('sssssss', $resumeID, $personID, $objective, $fullname, $contactNo, $address, $emailAdd);
    $result = $stmt->execute();

    if ($result) {
        $educationalLevel = ['primary education', 'secondary education', 'tertiary education']; //enum choices
        $index = 0;

        // insert data for educational background
        foreach ($educArray as $data) {
            $educationLevel = $educationalLevel[$index];
            $schoolName = $data['school'];
            echo $educationalLevel[$index] . '-' . $schoolName;
            $year = $data['year'];
            educationResume($resumeID, $schoolName, $year, $educationLevel, $con);
            $index++;
        }

        $response = "Unsuccess";
        // add work experience (if any)
        // check first if there's a work inserted
        $countWork = count($workArray);

        // insert experience
        if ($countWork > 0) {

            foreach ($workArray as $workData) {
                $jobTitle = $workData['workTitle'];
                $workDescript = $workData['workDescript'];
                $companyName = $workData['companyName'];
                $year = $workData['yearDuration'];

                // check first if there's a value first 
                if ($jobTitle != '')
                    insertWorkExperience($resumeID, $jobTitle, $companyName, $workDescript, $year, $con);
                else continue;
            }

            $result = skillandRef($skillArray, $resumeID, $referenceArray, $con);
            if ($result) $response = "Success";
            else $response = "Unsuccess";
        } else {
            $result = skillandRef($skillArray, $resumeID, $referenceArray, $con);
            if ($result) $response = "Success";
            else $response = "Unsuccess";
        }
    } else echo 'Unsuccess';


    echo $response;
}

function skillandRef($skillArray, $resumeID, $referenceArray, $con)
{
    $isAdded = true;
    //insert the rest of the data
    // skill insertion
    foreach ($skillArray as $skill) {
        if ($skill != "")
            $isAdded = insertSkill($resumeID, $skill, $con);
    }

    // reference insertion
    foreach ($referenceArray as $reference) {
        $fullname = $reference['fullname'];
        $jobTitle = $reference['jobTitle'];
        $contactNo = $reference['contactNo'];
        $emailAdd = $reference['emailAdd'];
        $isAdded = insertReference($resumeID, $fullname, $jobTitle, $contactNo, $emailAdd, $con);
    }

    return $isAdded;
}

function insertSkill($resumeID, $skill, $con)
{
    $timestamp = time();
    $uniqID = substr(md5(uniqid()), 0, 10);
    $skillID = $timestamp . '-' . $uniqID;

    $query = "INSERT INTO `resume_skill`(`skillID`, `resumeID`, `skill`) VALUES (? ,? ,? )";
    $stmt = mysqli_prepare($con, $query);
    $stmt->bind_param('sss', $skillID, $resumeID, $skill);
    $result = $stmt->execute();

    if ($result) return true;
    else return false;
}

function insertReference($resumeID, $fullname, $jobTitle, $contactNo, $emailAdd, $con)
{
    $timestamp = time();
    $uniqID = substr(md5(uniqid()), 0, 10);
    $referenceID = $timestamp . '-' . $uniqID;

    $query = "INSERT INTO `reference_resume`(`referenceID`, `resumeID`, `reference_name`, `job_title`, 
    `contactNo`, `emailAddress`) VALUES (? ,? ,? ,? ,? ,? )";
    $stmt = mysqli_prepare($con, $query);
    $stmt->bind_param('ssssss', $referenceID, $resumeID, $fullname, $jobTitle, $contactNo, $emailAdd);
    $result = $stmt->execute();

    if ($result) return true;
    else return false;
}
function educationResume($resumeID, $schoolName, $year, $educationLevel, $con)
{
    $random = rand(0, 5000);
    $uniqID = substr(md5(uniqid()), 0, 10);
    $educID = $random . '-' . $uniqID;

    $query = "INSERT INTO `education`(`educationID`, `resumeID`, `education_level`, `degree`, `year`) 
    VALUES (? ,? ,? ,? ,? )";
    $stmt = mysqli_prepare($con, $query);
    $stmt->bind_param('sssss', $educID, $resumeID, $educationLevel, $schoolName, $year);
    $result = $stmt->execute();

    if ($result) return true;
    else return false;
}

function insertWorkExperience($resumeID, $jobTitle, $companyName, $workDescript, $year, $con)
{
    $random = rand(0, 5000);
    $uniqID = substr(md5(uniqid()), 0, 10);
    $workID = $random . '-' . $uniqID;

    $query = "INSERT INTO `work_exp`(`workID`, `resumeID`, `job_title`, `companyName`, 
    `work_description`, `year`) VALUES (? ,? ,? ,? ,? ,? )";
    $stmt = mysqli_prepare($con, $query);
    $stmt->bind_param('ssssss', $workID, $resumeID, $jobTitle, $companyName, $workDescript, $year);
    $result = $stmt->execute();

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
    $resumeID = "";
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
        "resumeID" => $resumeID,
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

function updateResumeDetail($column, $value, $resumeID, $con)
{
    $query = "UPDATE `resume` SET ? = ? WHERE `resumeID` = ?";
    $stmt = mysqli_prepare($con, $query);
    $stmt->bind_param('ss', $column, $value, $resumeID);
    $result = $stmt->execute();

    if ($result) echo 'Success';
    else echo 'Unsuccess';
}

function updateResumeData($table, $column, $value, $resumeID, $recentVal, $con)
{
    $query = "UPDATE `$table` SET $column = ? WHERE `resumeID` = ?  AND $column = ? ";
    $stmt = mysqli_prepare($con, $query);
    $stmt->bind_param('sss', $value, $resumeID, $recentVal);
    $result = $stmt->execute();


    if ($result) echo 'Success';
    else echo 'Unsuccess';
}
