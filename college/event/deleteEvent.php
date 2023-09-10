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
    // get the Event ID 


    // Get the form data
    $eventID = $_POST['eventID'];

    // Create an instance of the Event class
    $event = new Event($mysql_con);

    // make array of event information
    $eventInformation = array(
        'eventID' => $eventID
    );

    $colCode = $_SESSION['colCode'];

    // delete event
    $result = $event->deleteEventByID($eventInformation, $colCode);
    // send json result
    if ($result) {
        logDeleteActivity($mysql_con, $_SESSION['adminID'], $colCode);

        echo json_encode(
            array(
                'response' => 'Successful',
                'message' => 'Event entry deleted'
            )
        );
    } else {
        echo json_encode(
            array(
                'response' => 'Unsuccessful',
                'message' => 'Event entry not deleted'
            )
        );
    }



    // if ($result  == TRUE) {
    // } 
    // else {
    //     // redirect to event page
    //     echo json_encode(
    //         array(
    //             'response' => 'Unsuccessful',
    //             'message' => 'Event entry not deleted'
    //         )
    //     );
    // };
} else if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // TODO redirect to error page.
    echo "GET request not allowed";
    // time for 5 seconds and redirect to index page
    header("refresh:5;url=../index.php");
    exit();
}
