<?php

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
        default:
            echo 'not pumasok sa kahit saan';
            break;
    }
} else echo 'not pumasok';

function insertionPerson($accountType, $userType, $con)
{

    //datas to be stored
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $bday = $_POST['bday'];
    $age = getAge($bday);
    $contactNo = $_POST['contactNo'];
    $address = $_POST['address'];
    $personalEmail = $_POST['personalEmail'];
    $bulsuEmail = $_POST['bulsuEmail'];
    $gender = $_POST['gender'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    $randomNo = rand(1, 2000);
    $currentDateTime = date('y/m/d h:i:s');
    $personID = $accountType . $currentDateTime . '-' . $randomNo;

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
                        $insertAcc = $studentUser->insertStudent($studentNo, $college, $personID, $username, $batch, $con);
                    } else {
                        $alumniUser = new Alumni();
                        $insertAcc = $alumniUser->insertAlumni($studentNo, $personID, $college, $username, $batch, $con);
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
