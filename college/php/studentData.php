<?php
require_once '../../PHP_process/connection.php';
require 'studentTB.php';


$student = new Student();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
} else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $student->getStudenData();
} else {
    echo 'No accesss to student data';
    echo $_GET['test'];
}


// if (isset($_POST['action'])) {
//     $actionArray = $_POST['action'];
//     $actionJSON = json_decode($actionArray, true);
//     $action = $actionJSON['action'];

//     switch ($action) {
//         case 'read':
//             $currentYear = $actionJSON['currentYear'];
//             $student = new Student();
//             $student->getStudenData($currentYear, $mysql_con);
//             break;
//     }
// } else if ($_SERVER('REQUEST_METHOD') === 'GET') {
//     $student = new Student();
//     $student->getStudenData();
// } else {
//     echo 'cant Enter';
//     echo $_GET['test'];
// }
