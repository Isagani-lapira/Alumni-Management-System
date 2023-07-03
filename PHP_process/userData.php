<?php

require_once 'connection.php';
require_once 'userTable.php';
require_once 'personDB.php';

if (isset($_POST['action'])) {
    $data = $_POST['action'];
    $actionArray = json_decode($data, true);
    $action = $actionArray['action'];

    //check what are to be perform
    switch ($action) {
        case 'create':
            insertionCollege($mysql_con);
            break;
        case 'read':
            //check first if it has query
            if (isset($actionArray['query'])) {
                $query = $actionArray['query'];
                $checkCreds = new User_Table();

                //if there's a query go to searching with query
                if ($query == 1) {
                    $username = $_POST['username'];
                    $password = $_POST['password'];
                    $checkCreds->checkUser($username, $password, $mysql_con);
                }
                //else go to select all searching
                else {
                    $username = $_POST['username'];
                    $checkCreds->checkUsername($username, $mysql_con);
                }
            }
            break;
        default:
            echo 'not pumasok sa kahit saan';
            break;
    }
} else echo 'not pumasok';

function insertionCollege($con)
{

    // ($personID,$FName,$LName,$age,$bday,
    //     $contactNo,$address,$personalEmail,$bulsuEmail,$gender,$profilePic ,$con)

    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $age = $_POST['age'];
    $bday = $_POST['bday'];
    $contactNo = $_POST['contactNo'];
    $address = $_POST['address'];
    $personalEmail = $_POST['personalEmail'];
    $bulsuEmail = $_POST['bulsuEmail'];
    $gender = $_POST['gender'];

    $randomNo = rand(1, 2000);
    $currentDateTime = date('y/m/d h:i:s');
    $personID = 'admin' . $currentDateTime . '-' . $randomNo;

    echo $fname . $lname . $age . $bday . $contactNo . $address . $personalEmail . $bulsuEmail . $gender;
}
