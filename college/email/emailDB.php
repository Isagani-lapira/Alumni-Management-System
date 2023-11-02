<?php
session_start();
require_once '../php/connection.php';
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
                $personID = $_SESSION['personID'];
                $emailData->queryDate($startDate, $endDate, $personID, $mysql_con);
            } else echo 'no Date inserted';
            break;
        case 'suggestionEmail':
            if (isset($_POST['email'])) {
                $email = $_POST['email'];
                $emailData->getEmail($email, $mysql_con);
            }
            break;
        case 'retrieveEmails':
            $personID = $_SESSION['personID'];
            $offset = $_POST['offset'];
            $emailData->retrieveEmailsSent($personID, $offset, $mysql_con);
            break;
        case 'retrieveByFilter':
            $personID = $_SESSION['personID'];
            $offset = $_POST['offset'];
            $colCode = $_POST['colCode'];
            $emailData->filterRetrieval($personID, $offset, $colCode, $mysql_con);
            break;
    }
} else echo 'ayaw pumasok';
