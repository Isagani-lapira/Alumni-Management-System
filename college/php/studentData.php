<?php
require_once '../../PHP_process/connection.php';
require 'studentTB.php';


$student = new Student();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
} else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $student->getStudents();
} else {
    echo 'No accesss to student data';
    echo $_GET['test'];
}
