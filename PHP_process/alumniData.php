<?php

require 'alumniTB.php';
require_once 'connection.php';

if (isset($_POST['action'])) {
    $actionArray = $_POST['action'];
    $actionJSON = json_decode($actionArray, true);
    $action = $actionJSON['action'];

    $alumni = new Alumni();
    switch ($action) {
        case 'readAll':
            $offset = $_POST['offset'];
            $alumni->getAlumniRecord($offset, $mysql_con);
            break;
        case 'filterRecord':
            $offset = $_POST['offset'];
            $batchYr = $_POST['batchYr'];
            $college = $_POST['college'];
            $status = $_POST['status'];
            $alumni->alumniByFilter($offset, $batchYr, $college, $status, $mysql_con);
            break;
    }
} else {
    echo 'not pumasok';
}
