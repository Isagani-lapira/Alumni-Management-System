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
            readBookmark($username, $careerID, $mysql_con);
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
function readBookmark($username, $careerID, $con)
{
    $query = "SELECT * FROM `saved_career` WHERE `username`= '$username' AND `careerID`='$careerID'";
    $result = mysqli_query($con, $query);
    $row = mysqli_num_rows($result);

    if ($result && $row > 0) echo "Exist";
    else echo 'Not exist';
}

function readAllBookmark($username, $con)
{
    $query = "SELECT * FROM `saved_career` WHERE `username`= '$username'";
    $result = mysqli_query($con, $query);
    $row = mysqli_num_rows($result);

    $careerData = array();
    if ($result && $row > 0) {
        while ($data = mysqli_fetch_assoc($result)) {
            $careerID = $data['careerID'];

            $careerObj = new Career();
            $careerData[] = $careerObj->selectWithCareerID($con, $careerID);
        }
    } else return 'none';

    return $careerData;
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
