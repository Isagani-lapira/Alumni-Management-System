<?php

require_once 'connection.php';
require 'emailTable.php';

if (isset($_POST['action'])) {
    $actionArray = $_POST['action'];
    $data = json_decode($actionArray, true);
    $action = $data['action'];

    $emailData = new EmailTable();
    switch ($action) {
            //read email with the use of range of date
        case 'readFromDate':
            if (isset($_POST['dateStart']) && isset($_POST['dateEnd'])) {
                $startDate = $_POST['dateStart'];
                $endDate = $_POST['dateEnd'];
                $personID = 'admin23/06/29 11:23:12-932'; // to be change
                $emailData->queryDate($startDate, $endDate, $personID, $mysql_con);
            } else echo 'no Date inserted';
            break;
    }
} else echo 'ayaw pumasok';
