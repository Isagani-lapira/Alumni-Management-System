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
            $offset = $actionJSON['offset'];
            $colCode = $actionJSON['colCode'];
            $search = $actionJSON['search'];
            $student = new Student();
            $student->getAllStudent($offset, $currentYear, $colCode, $search, $mysql_con);
            break;
    }
} else {
    echo 'cant Enter';
}
