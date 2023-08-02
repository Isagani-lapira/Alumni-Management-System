<?php

require_once 'connection.php';
session_start();
if (isset($_POST['action'])) {
    $action = $_POST['action'];
    if ($action == 'readEvent') {
        $colCode = $_SESSION['colCode'];
        reatrieveEvent($colCode, $mysql_con);
    }
} else echo 'ayaw';

function reatrieveEvent($colCode, $con)
{
    $currentDate = $_POST['currentDate'];
    $query = "SELECT * FROM `event` WHERE `eventDate`>= '$currentDate' AND `colCode` = '$colCode'
    ORDER by `eventDate` ASC LIMIT 0, 1";
    $result = mysqli_query($con, $query);
    $row = mysqli_num_rows($result);

    if ($result && $row > 0) getDetail($result, $con);
    else echo 'ayaw';
}

function getDetail($result, $con)
{
    $data = mysqli_fetch_assoc($result);
    $eventID  = $data['eventID'];
    $headerPhrase  = $data['headerPhrase'];
    $eventName = $data['eventName'];
    $eventDate = $data['eventDate'];
    $date_posted  = $data['date_posted'];
    $about_event = $data['about_event'];
    $contactLink = $data['contactLink'];
    $when_where = $data['when_where'];
    $aboutImg = $data['aboutImg'];
    $images = array();

    //retrieve images for carousel
    $query = "SELECT * FROM `eventimg` WHERE `eventID`= '$eventID'";
    $resultImg = mysqli_query($con, $query);

    if ($resultImg) {
        while ($data = mysqli_fetch_assoc($resultImg)) {
            $imgSrc = $data['event_img'];
            $images[] = base64_encode($imgSrc);
        }
    }

    // get expectation
    $expectationJSON = getEventExpectation($eventID, $con);
    $data = array(
        "headerPhrase" => $headerPhrase,
        "eventName" => $eventName,
        "eventDate" => $eventDate,
        "date_posted" => $date_posted,
        "about_event" => $about_event,
        "contactLink" => $contactLink,
        "when_where" => $when_where,
        "aboutImg" => base64_encode($aboutImg),
        "images" => $images,
        "expectation" => $expectationJSON
    );

    echo json_encode($data);
}

function getEventExpectation($eventID, $con)
{
    $query = "SELECT * FROM `event_expectation` WHERE `eventID` = '$eventID'";
    $result = mysqli_query($con, $query);

    $expectation = array();
    $sampleImg = array();
    if ($result) {
        //get event expectation 
        while ($data = mysqli_fetch_assoc($result)) {
            $expectation[] = $data['imgDescription'];
            $sampleImg[] = base64_encode($data['sampleImg']);
        }
    }
    $data = array(
        "sampleImg" => $sampleImg,
        "expectation" => $expectation
    );

    return $data;
}
