<?php

require_once 'connection.php';
require_once 'userTable.php';

if (isset($_POST['action'])) {
    $data = $_POST['action'];
    $actionArray = json_decode($data, true);
    $action = $actionArray['action'];

    //check what are to be perform
    switch ($action) {
        case 'create':

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


function getAge($bday)
{
    // Current date
    $currentDate = date('Y-m-d');

    // Create DateTime objects for the birthdate and current date
    $birthdateObj = (int)substr($bday, 0, 4);
    $currentDateObj = (int)substr($currentDate, 0, 4);

    // Calculate the difference between the two dates
    $ageInterval = $birthdateObj - $currentDateObj;

    return $ageInterval;
}
