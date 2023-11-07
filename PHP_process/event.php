<?php

require_once 'connection.php';
session_start();
if (isset($_POST['action'])) {
    $action = $_POST['action'];
    if ($action == 'readEvent') {
        $colCode = $_SESSION['colCode'];
        reatrieveEvent($colCode, $mysql_con);
    } else if ($action == 'readUpcomingEvent') {
        $colCode = $_SESSION['colCode'];
        $currentDate = $_POST['currentDate'];
        getUpcomingEvent($colCode, $currentDate, $mysql_con);
    } else if ($action == "retrieveSpecificEvent") {
        $eventID = $_POST['eventID'];
        retrieveSpecificEvent($eventID, $mysql_con);
    } else if ($action == 'nextEvents') {
        $colCode = $_POST['colCode'];
        $category = $_POST['category'];
        getNextCollegeEvent($colCode, $category, $mysql_con);
    }
} else echo 'ayaw';

function reatrieveEvent($colCode, $con)
{
    $currentDate = $_POST['currentDate'];
    $query = "SELECT `eventID`,`date_posted` ,`eventName`, `eventDate`, 
    `about_event`, `eventPlace`, `eventStartTime` FROM `event` WHERE 
    `eventDate`>= ? AND `colCode` = ?
    ORDER by `eventDate` ASC LIMIT 1";
    $stmt = mysqli_prepare($con, $query);
    $stmt->bind_param('ss', $currentDate, $colCode);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = mysqli_num_rows($result);

    if ($result && $row > 0) getDetail($result);
    else echo 'Failed';
}

function retrieveSpecificEvent($eventID, $con)
{
    $query = "SELECT `eventID`,`date_posted` ,`eventName`, `eventDate`, 
    `about_event`, `eventPlace`, `eventStartTime` FROM `event` WHERE `eventID` = '$eventID'";
    $result = mysqli_query($con, $query);
    $row = mysqli_num_rows($result);

    if ($result && $row > 0) getDetail($result);
    else echo 'nothing';
}

function getDetail($result)
{
    $response = "Success";
    $data = mysqli_fetch_assoc($result);
    $eventID  = $data['eventID'];
    $eventName = $data['eventName'];
    $eventDate = $data['eventDate'];
    $date_posted  = $data['date_posted'];
    $about_event = $data['about_event'];
    $eventPlace = $data['eventPlace'];
    $eventStartTime = $data['eventStartTime'];

    $data = array(
        "response" => $response,
        "eventID" => $eventID,
        "eventName" => $eventName,
        "eventDate" => $eventDate,
        "date_posted" => $date_posted,
        "about_event" => $about_event,
        "eventPlace" => $eventPlace,
        "eventStartTime" => $eventStartTime,
    );

    echo json_encode($data);
}

function getUpcomingEvent($colCode, $currentDate, $con)
{
    $offset = 0;
    $maxLimit = 5;
    $query = "SELECT `eventID`, `eventName`,`eventDate` FROM `event` WHERE `colCode`= '$colCode' AND 
    `eventDate`>='$currentDate' ORDER BY `eventDate` ASC LIMIT $offset, $maxLimit";
    $result = mysqli_query($con, $query);
    $row = mysqli_num_rows($result);

    $response = "";
    $eventID = array();
    $eventName = array();
    $eventDate = array();

    //retrieve the events
    if ($result && $row > 0) {
        $response = "Success";
        while ($data = mysqli_fetch_assoc($result)) {
            $eventID[] = $data['eventID'];
            $eventName[] = $data['eventName'];
            $eventDate[] = dateInText($data['eventDate']);
        }
    } else $response = "Failed";

    $data = array(
        "result" => $response,
        "eventID" => $eventID,
        "eventName" => $eventName,
        "eventDate" => $eventDate
    );
    echo json_encode($data);
}

function dateInText($date)
{
    $year = substr($date, 0, 4);
    $month = intval(substr($date, 5, 2));
    $day = substr($date, 8, 2);
    $months = [
        '', 'January', 'February', 'March', 'April', 'May', 'June',
        'July', 'August', 'September', 'October', 'November', 'December'
    ];

    //convert date month to text format
    $month = $months[$month];
    //return in a formatted date
    return $month . ' ' . $day . ', ' . $year;
}

function getNextCollegeEvent($colCode, $category, $con)
{
    $query = "";
    // filter by college and alumni
    if ($colCode != "") { //for next college event
        $query = "SELECT `eventID`, `eventName`, `eventDate`, `date_posted`, `about_event`,
        `eventPlace`, `eventStartTime` FROM `event` WHERE `colCode` = ? 
        AND `eventDate`>= CURRENT_DATE()
        ORDER BY `eventDate` ASC LIMIT 3";
        $stmt = mysqli_prepare($con, $query);
        $stmt->bind_param('s', $colCode);
    } else { //for next alumni event
        $query = "SELECT * FROM `event` WHERE `event_category` = ? 
        AND `eventDate`>= CURRENT_DATE()
        ORDER BY `eventDate` ASC LIMIT 3";
        $stmt = mysqli_prepare($con, $query);
        $stmt->bind_param('s', $category);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $row = mysqli_num_rows($result);

    $response = "Unsuccess";
    $eventID  = array();
    $eventName = array();
    $eventDate = array();
    $date_posted  = array();
    $about_event = array();
    $eventPlace = array();
    $eventStartTime = array();


    if ($result && $row > 0) {
        $response = "Success";
        while ($data = $result->fetch_assoc()) {
            $eventID[]  = $data['eventID'];
            $eventName[] = $data['eventName'];
            $eventDate[] = $data['eventDate'];
            $date_posted[]  = $data['date_posted'];
            $about_event[] = $data['about_event'];
            $eventPlace[] = $data['eventPlace'];
            $eventStartTime[] = $data['eventStartTime'];
        }
    }


    $data = array(
        "response" => $response,
        "eventID" => $eventID,
        "eventName" => $eventName,
        "eventDate" => $eventDate,
        "date_posted" => $date_posted,
        "about_event" => $about_event,
        "eventPlace" => $eventPlace,
        "eventStartTime" => $eventStartTime,
    );

    echo json_encode($data);
}
