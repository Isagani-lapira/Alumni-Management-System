<?php

// echo 'hello';
// var_dump($_POST);
// die();

require_once 'connection.php';
require_once 'userTable.php';
require_once 'personDB.php';
require_once 'userTable.php';
require_once 'univAdmin.php';
require_once 'studentTB.php';
require_once 'alumniTB.php';

if (isset($_POST['action'])) {
    $data = $_POST['action'];
    $actionArray = json_decode($data, true);
    $action = $actionArray['action'];

    //check what are to be perform
    switch ($action) {
        case 'create':
            $accountType = $actionArray['account'];
            //check if account is user to identify if it is alumni or student
            $userType = ($accountType == "User") ? $_POST['status'] : null;
            insertionPerson($accountType, $userType, $mysql_con);
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
        case 'checkPersonEmail':
            $emailAdd = $_POST['email'];
            $column = $_POST['column'];
            $personObj = new personDB();
            $personObj->checkPersonEmailAddress($emailAdd, $column, $mysql_con);
            break;
        case 'updatePass':
            $newPassword = $_POST['newPass'];
            $username = $_POST['username'];
            $userObj = new User_Table();
            $userObj->updatePassword($newPassword, $username, $mysql_con);
            break;
        case 'checkStudNo':
            $studNo = $_POST['studNo'];
            $personObj = new personDB();
            $isExist = $personObj->checkStudentNo($studNo, $mysql_con);

            if ($isExist) echo 'Exist';
            else echo 'Available';
            break;
        default:
            echo 'not pumasok sa kahit saan';
            break;
    }
} else echo 'not pumasok';

function insertionPerson($accountType, $userType, $con)
{

    //datas to be stored
    $fname = trim($_POST['fname']);
    $lname = trim($_POST['lname']);
    $bday = trim($_POST['bday']);
    $age = getAge($bday);
    $contactNo = trim($_POST['contactNo']);
    $address = trim($_POST['address']);
    $personalEmail = trim($_POST['personalEmail']);
    $bulsuEmail = trim($_POST['bulsuEmail']);
    $gender = trim($_POST['gender']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $courseID = $_POST['course'];

    $randomNo = rand(1, 2000);
    $currentDateTime = date('y/m/d h:i:s');
    $personID = $accountType . '-' . $currentDateTime . '-' . $randomNo;

    $person = new personDB();
    $insert_person = $person->insertPerson(
        $personID,
        $fname,
        $lname,
        $age,
        $bday,
        $contactNo,
        $address,
        $personalEmail,
        $bulsuEmail,
        $gender,
        null,
        $con
    );

    if ($insert_person) {
        $userTable = new User_Table();
        $userAcc = $userTable->addUser($username, $password, $accountType, $con);

        if ($userAcc) {
            $currentYr = date('Y');
            $adminID = 'ADM' . '-' . $currentYr . '-' . $randomNo;

            $insertAcc = false;
            switch ($accountType) {
                case 'UnivAdmin':
                    $univAdmin = new UniversityAdmin();
                    $insertAcc = $univAdmin->insertUnivAdmin($adminID, $personID, $username, $con);
                    break;
                case 'User':
                    $studentNo = $_POST['studNo'];
                    $college = $_POST['college'];
                    $batch = $_POST['batch'];
                    if ($userType == "Student") {
                        $studentUser = new Student();
                        $insertAcc = $studentUser->insertStudent($studentNo, $college, $personID, $username, $courseID, $batch, $con);
                    } else {
                        $alumniUser = new Alumni();
                        $employmentStat = $_POST['empStatus'];
                        $insertAcc = $alumniUser->insertAlumni($studentNo, $personID, $college, $username, $batch, $courseID, $employmentStat, $con);
                    }
                    break;
                case 'ColAdmin':
                    echo 'colAdmin';
                    break;
                default:
                    echo 'ayaw gumana';
                    break;
            }


            if ($insertAcc) echo 'Success';
            else echo 'Unsuccess';
        }
    }
}

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
