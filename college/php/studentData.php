<?php
require_once '../../PHP_process/connection.php';
require '../model/Student.php';


$student = new Student($mysql_con);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
} else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo $student->getStudentsByYear('');
} else {
    echo 'No accesss to student data';
    echo $_GET['test'];
}
