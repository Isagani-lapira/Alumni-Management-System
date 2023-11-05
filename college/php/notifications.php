<?php

require_once '../php/connection.php';
require '../../PHP_process/PostTB.php';
require_once '../../PHP_process/notificationTable.php';



/***
 * Set new notification
 * 
 * @param mysqli $mysql_con
 * @param string $postID
 * @param string $username
 * @param string $typeOfNotif
 * @param string $details
 * @return bool
 * 
 * 
 * 
 */
function setNewNotification(mysqli $mysql_con, string $postID, string $username, string $typeOfNotif,  $details = null): bool
{
    // $postID, $username, $typeOfNotif, $con, $details = NULL
    // 

    try {
        $notification = new Notification();

        $resultNotif = $notification->insertNotif($postID, $username, $typeOfNotif, $mysql_con, $details);

        if ($resultNotif) return true;
        else return false;
    } catch (\Throwable $th) {
        throw $th;
    }
}
