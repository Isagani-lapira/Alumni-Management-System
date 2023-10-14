<?php

session_start();
require 'notificationTable.php';
require_once 'connection.php';

if (isset($_POST['action'])) {
    $actionArray = $_POST['action'];
    $actionJSON = json_decode($actionArray, true);
    $action = $actionJSON['action'];
    $username = $_SESSION['username'];

    $notificationObj = new Notification();
    switch ($action) {
        case 'readNotif':
            $offset = $_POST['offset'];
            $notificationObj->ReadNotification($username, $offset, $mysql_con);
            break;
        case 'readUnreadNotif':
            $notificationObj->totalUnreadNotif($username, $mysql_con);
            break;
        case 'updateNotifStat':
            $notifID = $_POST['notifID'];
            $notificationObj->updateStat($notifID, $mysql_con);
            break;
        case 'unreadNotif':
            $offset = $_POST['offset'];
            $notificationObj->unreadNotification($username, $offset, $mysql_con);
            break;
        default:
            echo 'nothing';
            break;
    }
} else echo 'ayaw';
