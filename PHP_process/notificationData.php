<?php

session_start();
require 'notificationTable.php';
require_once 'connection.php';

if (isset($_POST['action'])) {
    $actionArray = $_POST['action'];
    $actionJSON = json_decode($actionArray, true);
    $action = $actionJSON['action'];

    $notificationObj = new Notification();
    switch ($action) {
        case 'readNotif':
            $date = $actionJSON['retrievalDate'];
            $username = $_SESSION['username'];
            $notificationObj->ReadNotification($username, $date, $mysql_con);
            break;
        default:
            echo 'nothing';
            break;
    }
} else echo 'ayaw';
