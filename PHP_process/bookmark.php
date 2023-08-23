<?php

session_start();
require_once 'connection.php';
require 'career.php';

if (isset($_POST['action'])) {
    $action = $_POST['action'];
    $username = $_SESSION['username'];
    switch ($action) {
        case 'saveCareer':
            $careerID = $_POST['careerID'];
            saveCareer($username, $careerID, $mysql_con);
            break;
        case 'removeBookmark':
            $careerID = $_POST['careerID'];
            removeBookmark($username, $careerID, $mysql_con);
            break;
        case 'checkBookmark':
            $careerID = $_POST['careerID'];
            $offset = $_POST['offset'];
            readBookmark($username, $careerID, $offset, $mysql_con);
            break;
        case 'readBookmark':
            $username;
            readAllBookmark($username, $mysql_con);
            break;
        default:
            echo 'error';
            break;
    }
}


function saveCareer($username, $careerID, $con)
{
    $random = rand(0, 5000);
    $uniqueId = substr(md5(uniqid()), 0, 7); //unique id with length of 7
    $saveID =  $uniqueId . '-' . $random;
    $date = date('y-m-d');

    //query for inserting data to bookmark table
    $query = "INSERT INTO `saved_career`(`saved_career`, `careerID`, `username`, `date_mark`) 
    VALUES ('$saveID','$careerID','$username','$date')";
    $result = mysqli_query($con, $query);

    if ($result) echo 'Success';
    else echo 'Unsuccess';
}

//removal of data
function removeBookmark($username, $careerID, $con)
{
    $query = "DELETE FROM `saved_career` WHERE `username` ='$username' AND `careerID` ='$careerID' ";
    $result = mysqli_query($con, $query);

    if ($result) echo "Success Deletion";
    else echo 'Unsuccess Deletion';
}

//read bookmark
function readBookmark($username, $careerID, $offset, $con)
{
    $maxLimit = 10;
    $query = "SELECT * FROM `saved_career` WHERE `username`= '$username' AND `careerID`='$careerID'
    ORDER BY`date_posted` LIMIT $offset,$maxLimit";
    $result = mysqli_query($con, $query);
    $row = mysqli_num_rows($result);

    if ($result && $row > 0) echo "Exist";
    else echo 'Not exist';
}

function readAllBookmark($username, $con)
{
    $query = "SELECT * FROM `saved_career` WHERE 
    `username`= '$username' ORDER BY `date_mark`DESC";

    $result = mysqli_query($con, $query);
    $row = mysqli_num_rows($result);

    $career = array();
    if ($result && $row > 0) {
        while ($data = mysqli_fetch_assoc($result)) {
            $careerID = $data['careerID'];

            $career[] = getCareer($careerID, $con);
        }
    } else return 'none';

    echo json_encode($career);
}

function getCareer($careerID, $con)
{
    $query = "SELECT * FROM `career` WHERE `careerID` = '$careerID'";
    $result = mysqli_query($con, $query);

    $response = "";
    $careerIDs = "";
    $jobTitle = "";
    $companyName = "";
    $jobDescript = "";
    $jobQuali = "";
    $companyLogo = "";
    $minSalary = "";
    $maxSalary = "";
    $colCode = "";
    $author = "";
    $date_posted = "";
    $skills = array();
    $location = "";
    $personIDEncrypted = "";

    if ($result) {
        $data = mysqli_fetch_assoc($result);
        $response = "Success";
        $careerIDs = $data['careerID'];
        $jobTitle = $data['jobTitle'];
        $companyName = $data['companyName'];
        $jobDescript = $data['jobDescript'];
        $jobQuali = $data['jobqualification'];
        $companyLogo = base64_encode($data['companyLogo']);
        $minSalary = $data['minSalary'];
        $maxSalary = $data['maxSalary'];
        $colCode = $data['colCode'];
        $author = $data['author'];
        $date_posted = $data['date_posted'];
        $location = $data['location'];
        $personIDEncrypted = generatePseudonym($data['personID']);

        //retrieve skills from the database
        $skillQuery = 'SELECT * FROM `skill` WHERE careerID = "' . $data['careerID'] . '"';
        $skillResult = mysqli_query($con, $skillQuery);
        $skillNames = array();

        if ($skillResult && mysqli_num_rows($skillResult) > 0) {
            while ($skill_data = mysqli_fetch_assoc($skillResult)) {
                $skillNames[] = $skill_data['skill'];
            }
        }

        $skills[] = $skillNames;
    }

    //data to be sent
    $data =  array(
        'result' => $response,
        'careerID' => $careerIDs,
        'jobTitle' => $jobTitle,
        'companyName' => $companyName,
        'companyLogo' => $companyLogo,
        'jobDescript' => $jobDescript,
        'jobQuali' => $jobQuali,
        'minSalary' => $minSalary,
        'maxSalary' => $maxSalary,
        'skills' => $skills,
        'colCode' => $colCode,
        'author' => $author,
        'date_posted' => $date_posted,
        'personID' => $personIDEncrypted,
        'location' => $location
    );

    return $data; //return data as json
}
function dateInText($date)
{
    $year = substr($date, 0, 4);
    $month = intval(substr($date, 5, 2));
    $day = substr($date, 8, 2);
    $months = [
        '', 'January', 'February', 'March', 'April', 'May', 'June',
        'July', 'August', 'September', 'October', 'November', 'December'
    ];

    //2023-07-17
    //convert date month to text format
    $month = $months[$month];

    //return in a formatted date
    return $month . ' ' . $day . ', ' . $year;
}

function generatePseudonym($personID)
{
    $pseudonym = md5($personID);

    return $pseudonym;
}
