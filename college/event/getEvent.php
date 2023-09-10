<?php
session_start();


require '../php/connection.php';
require '../model/Event.php';



//    check if college admin is logged in
if ($_SESSION['accountType'] !== 'ColAdmin') {
    // TODO redirect to error page.
    header("Location: ../index.php");
    exit();
}

// check if session admin is set
if (!isset($_SESSION['college_admin']) && !isset($_SESSION['adminID'])) {
    // TODO redirect to error page.
    header("Location: ../index.php");
    exit();
}


if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    $result = null;
    $event = new Event($mysql_con);






    if (isset($_GET['partial']) &&   $_GET['partial'] === 'true') {
        // get the offset from the url
        $offset = $_GET['offset'];

        $category = $_GET['category'];

        if ($category === 'all') {
            $category = null;
        }

        //convert to int
        $offset = (int) $offset;

        $results = $event->getNewPartialEventsByOffset($offset,  $_SESSION['colCode'], $category,);
        header("Content-Type: application/json; charset=UTF-8");
        echo json_encode($results);
    } else {
        // todo validate event id
        $results = $event->getEventById($eventID = $_GET['eventID'],  $colCode = $_SESSION['colCode']);
        header("Content-Type: application/json; charset=UTF-8");
        echo json_encode($results);
    }
} else {
    echo "You are not supposed to be here.";
    header("refresh:5; url=../index.php");
    exit();
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
    $eventPlace = $data['eventPlace'];
    $eventStartTime = $data['eventStartTime'];
    $aboutImg = $data['aboutImg'];
    $category = $data['event_category'];
    // Event ID for the event editing
    $eventID = $data['eventID'];
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
        "eventPlace" => $eventPlace,
        "event_category" => $category,
        "eventStartTime" => $eventStartTime,
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
