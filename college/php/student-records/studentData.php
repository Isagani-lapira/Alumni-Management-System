<?php
require_once 'connection.php';
require 'studentTB.php';

if (isset($_POST['action'])) {
    $actionArray = $_POST['action'];
    $actionJSON = json_decode($actionArray, true);
    $action = $actionJSON['action'];

    switch ($action) {
        case 'read':
            $currentYear = $actionJSON['currentYear'];
            $student = new Student();
            $student->getStudenData($currentYear, $mysql_con);
            break;
    }
} else {
    echo 'cant Enter';
}
