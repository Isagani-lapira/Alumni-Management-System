<?php
session_start();
require_once 'connection.php';
require 'personDB.php';

if (isset($_POST['action'])) {
    $data = $_POST['action'];
    $actionArray = json_decode($data, true);
    $action = $actionArray['action'];
    $personID = $_SESSION['personID'];
    $personObj = new personDB();

    switch ($action) {
        case 'read':
            $email =  $_POST['personalEmail'];
            checkEmail($email, $mysql_con);
            break;
        case 'updateProfile':
            $imgSrc = $_FILES['imgSrc'];
            $query = $actionArray['dataUpdate'];
            $personObj->updateImage($personID, $query, $imgSrc, $mysql_con);
            break;
        case 'updateCover':
            $imgSrc = $_FILES['imgSrc'];
            $query = $actionArray['dataUpdate'];
            $personObj->updateCover($personID, $query, $imgSrc, $mysql_con);
            break;
        case 'updatePersonDetails':
            $val = $_POST['value'];
            $query = $actionArray['dataUpdate'];
            $personObj->updateInfo($personID, $query, $val, $mysql_con);
            break;
        case 'searchPerson':
            $personName = $_POST['personName'];
            $personObj->searchPerson($personName, $mysql_con);
            break;
    }
} else echo 'ayaw pumasok';


function checkEmail($email, $con)
{
    $query = 'SELECT * FROM `person` WHERE `personal_email`="' . $email . '"';
    $result = mysqli_query($con, $query);
    $row = mysqli_num_rows($result);

    if ($row > 0) echo 'Exist';
    else echo 'Available';
}
