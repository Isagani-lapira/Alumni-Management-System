<?php
session_start();

require "../model/Event.php";
require "../php/connection.php";



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



// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {




    // ty copilot :)
    // Get the form data
    $eventName = $_POST['eventName'];
    $eventDate = $_POST['eventDate'];
    $about_event = $_POST['about_event'];
    $contactLink = $_POST['contactLink'];
    $headerPhrase = $_POST['headerPhrase'];
    $eventPlace = $_POST['eventPlace'];
    $eventStartTime = $_POST['eventStartTime'];
    // image data
    $aboutImg = $_FILES['aboutImg'];

    // TODO validate the form data


    // Create an instance of the Event class
    $event = new Event($mysql_con);

    // Check if image file is larger than 2MB
    if ($_FILES["aboutImg"]["size"] > 2000000) {
        echo "Sorry, your file is too large. Make it 2mb or less";
    }
    // check if image file is jpg
    $imageMimeType = $_FILES["aboutImg"]["type"];


    if ($imageMimeType != "image/jpg" && $imageMimeType != "image/jpeg") {
        echo "Sorry, only JPG files are allowed.";
        die();
    }


    // Image data
    $aboutImgTmpName = $_FILES['aboutImg']['tmp_name'];

    // make array of event information
    // TODO make the id auto increment
    $eventInformation = array(
        'eventName' => $eventName,
        'eventDate' => $eventDate,
        'about_event' => $about_event,
        'contactLink' => $contactLink,
        'aboutImg' => $aboutImgTmpName,
        'headerPhrase' => $headerPhrase,
        'eventPlace' => $eventPlace,
        'eventStartTime' => $eventStartTime,
    );

    // get session id
    $adminID = $_SESSION['adminID'];
    $colCode = $_SESSION['colCode'];


    // set new event
    $result = $event->setNewEvent($eventInformation, $colCode, $adminID);
    header("Content-Type: application/json; charset=UTF-8");
    if ($result === TRUE) {
        echo json_encode(
            array(
                'response' => 'Successful',
                'message' => 'Event added successfully'
            )
        );
    } else {
        echo json_encode(
            array(
                'response' => 'Unsuccessful',
                'message' => 'Event not added'
            )
        );
    };
}
