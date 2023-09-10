<?php
session_start();

require "../model/Event.php";
require "../php/connection.php";
require "../php/logging.php";



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
    // Get the form data
    $eventName = $_POST['eventName'];
    $eventDate = $_POST['eventDate'];
    $about_event = $_POST['about_event'];
    $contactLink = $_POST['contactLink'];
    $headerPhrase = $_POST['headerPhrase'];
    $eventPlace = $_POST['eventPlace'];
    $eventStartTime = $_POST['eventStartTime'];
    $event_category = $_POST['category'];
    // Event ID for the event editing
    $eventID = $_POST['eventID'];



    // image data
    $aboutImg = isset($_FILES['aboutImg']) ? $_FILES['aboutImg'] : null;

    // Create an instance of the Event class
    $event = new Event($mysql_con);

    $aboutImgTmpName = null;
    if ($aboutImg === null) {
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
    }




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
        'event_category' => $event_category,
        'eventID' => $eventID
    );


    // get session id
    $adminID = $_SESSION['adminID'];
    $colCode = $_SESSION['colCode'];


    // set new event
    $result = $event->setEditEvent($eventInformation, $colCode, $adminID);
    header("Content-Type: application/json; charset=UTF-8");
    if ($result === TRUE) {
        logUpdateActivity($mysql_con, $adminID, $colCode);
        echo json_encode(
            array(
                'response' => 'Successful',
                'message' => 'Event updated successfully',
                'eventID' => $eventID
            )
        );

        // redirect to event page
        // header("Location: ../event/event.php");
    } else {
        echo json_encode(
            array(
                'response' => 'Unsuccessful',
                'message' => 'Event not added'
            )
        );
    };
} else if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // TODO redirect to error page.
    echo "GET request not allowed";
    // time for 5 seconds and redirect to index page
    header("refresh:5;url=../index.php");
    exit();
}
