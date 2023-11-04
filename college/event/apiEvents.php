
<?php
session_start();

require "../model/Event.php";
require "../php/connection.php";
require "../php/logging.php";



// //    check if college admin is logged in
// if ($_SESSION['accountType'] !== 'ColAdmin') {
//     // TODO redirect to error page.
//     // header("Location: ../index.php");
//     echo 'account type not colAdmin';
//     exit();
// }

// // check if session admin is set
// if (!isset($_SESSION['college_admin']) && !isset($_SESSION['adminID'])) {
//     // TODO redirect to error page.
//     // header("Location: ../index.php");

//     echo 'account type not colAdmin';
//     exit();
// }



// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        if ($action == 'addEvent') {
            try {

                // Get the form data
                $eventName = $_POST['eventName'];
                $eventDate = $_POST['eventDate'];
                $about_event = $_POST['about_event'];
                // $contactLink = $_POST['contactLink'];
                // $headerPhrase = $_POST['headerPhrase'];
                $eventPlace = $_POST['eventPlace'];
                $eventStartTime = $_POST['eventStartTime'];
                $event_category = $_POST['category'];
                // image data
                $aboutImg = $_FILES['aboutImg'];

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
                    // 'contactLink' => $contactLink,
                    'aboutImg' => $aboutImgTmpName,
                    // 'headerPhrase' => $headerPhrase,
                    'eventPlace' => $eventPlace,
                    'eventStartTime' => $eventStartTime,
                    'event_category' => $event_category
                );


                // get session id
                $adminID = $_SESSION['adminID'];
                $colCode = $_SESSION['colCode'];


                // set new event


                $result = $event->setNewEvent($eventInformation, $colCode, $adminID);

                header("Content-Type: application/json; charset=UTF-8");
                if ($result === TRUE) {
                    $action = "event";
                    $details = " created an event";
                    setNewActivity($mysql_con, $_SESSION['adminID'], $action, $details);
                    echo json_encode(
                        array(
                            'response' => 'Successful',
                            'message' => 'Event added successfully',
                            'status' => 'success'
                        )
                    );
                } else {
                    echo json_encode(
                        array(
                            'response' => 'Unsuccessful',
                            'message' => 'Event not added',
                            'status' => 'error'
                        )
                    );
                };
            } catch (\Throwable $th) {
                //throw $th;

                echo json_encode(
                    array(
                        'response' => 'Unsuccessful',
                        'message' => 'Event not added',
                        'status' => 'error',
                        'error' => $th->getMessage()
                    )
                );
            }
        }
    } else {
        echo json_encode(
            array(
                'response' => 'Unsuccessful',
                'message' => 'No action on GET',
                'status' => 'error',
                'error' => 'No action set'
            )
        );
    }
} else if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // // TODO redirect to error page.
    // echo "GET request not allowed";
    // // time for 5 seconds and redirect to index page
    // header("refresh:5;url=../index.php");
    // exit();

    $result = null;
    $event = new Event($mysql_con);

    $colCode = $_SESSION['colCode'];

    if (isset($_GET['action'])) {
        $action = $_GET['action'];

        if ($action === 'getOneEventDetails') {

            $results = $event->getEventById($eventID = $_GET['eventID'],  $colCode = $_SESSION['colCode']);
            header("Content-Type: application/json; charset=UTF-8");
            echo json_encode($results);
        } else if (
            $action === 'partial'
        ) {

            // get the offset from the url
            $offset = $_GET['offset'];

            $category = $_GET['category'];

            if ($category === 'all') {
                $category = null;
            }

            //convert to int
            $offset = (int) $offset;

            try {

                // $results = $event->getNewPartialEventsByOffset($offset,  $_SESSION['colCode'], $category,);
                // fetch all events regardless of category  


                if ($category === null) {
                    // Initialize the statement
                    $stmt = $mysql_con->prepare('SELECT * 
                    FROM `event`
                      WHERE `colCode` = ? 
                      ORDER BY date_posted DESC
                      LIMIT  5  OFFSET  ? 
                      ');
                    $stmt->bind_param('si', $colCode, $offset);
                } else {
                    // Initialize the statement
                    $stmt = $mysql_con->prepare('SELECT *
                    FROM `event`
                      WHERE `colCode` = ? AND `event_category` = ?
                      LIMIT  5  OFFSET  ? 
                      ');
                    $stmt->bind_param('ssi', $colCode, $category, $offset);
                }

                // execute the query
                $stmt->execute();
                // gets the myql_result. Similar result to mysqli_query
                $result = $stmt->get_result();
                $num_row = mysqli_num_rows($result);


                // the main assoc array to be return
                $json_result = array();
                // holds every row in the query
                $resultArray = array();

                if ($result && $num_row > 0) {
                    $json_result['response'] = 'Successful';
                    // Gets every row in the query
                    while ($record = mysqli_fetch_assoc($result)) {
                        // ! README ALWAYS USE base64_encode() when sending image to client. 2 Hours wasted because of this. 
                        $record['aboutImg'] = base64_encode($record['aboutImg']);
                        $resultArray[] = $record;
                    }
                    $json_result['result'] = $resultArray;
                } else {
                    $json_result['response'] = 'Unsuccesful';
                }

                $json_result['offset'] = $offset;
                // return $json_result;


                header("Content-Type: application/json; charset=UTF-8");
                echo json_encode($json_result);
            } catch (\Throwable $th) {
                //throw $th;
                echo json_encode(
                    array(
                        'response' => 'Unsuccessful',
                        'message' => 'Event not added',
                        'status' => 'error',
                        'error' => $th->getMessage()
                    )
                );
            }
        }
    }

    // if (isset($_GET['partial'])  &&   $_GET['partial'] === 'true') {

    //     // get the offset from the url
    //     $offset = $_GET['offset'];

    //     $category = $_GET['category'];

    //     if ($category === 'all') {
    //         $category = null;
    //     }

    //     //convert to int
    //     $offset = (int) $offset;

    //     try {

    //         // $results = $event->getNewPartialEventsByOffset($offset,  $_SESSION['colCode'], $category,);
    //         // fetch all events regardless of category  


    //         if ($category === null) {
    //             // Initialize the statement
    //             $stmt = $mysql_con->prepare('SELECT * 
    //         FROM `event`
    //           WHERE `colCode` = ? 
    //           ORDER BY date_posted DESC
    //           LIMIT  5  OFFSET  ? 
    //           ');
    //             $stmt->bind_param('si', $colCode, $offset);
    //         } else {
    //             // Initialize the statement
    //             $stmt = $mysql_con->prepare('SELECT *
    //         FROM `event`
    //           WHERE `colCode` = ? AND `event_category` = ?
    //           LIMIT  5  OFFSET  ? 
    //           ');
    //             $stmt->bind_param('ssi', $colCode, $category, $offset);
    //         }

    //         // execute the query
    //         $stmt->execute();
    //         // gets the myql_result. Similar result to mysqli_query
    //         $result = $stmt->get_result();
    //         $num_row = mysqli_num_rows($result);


    //         // the main assoc array to be return
    //         $json_result = array();
    //         // holds every row in the query
    //         $resultArray = array();

    //         if ($result && $num_row > 0) {
    //             $json_result['response'] = 'Successful';
    //             // Gets every row in the query
    //             while ($record = mysqli_fetch_assoc($result)) {
    //                 // ! README ALWAYS USE base64_encode() when sending image to client. 2 Hours wasted because of this. 
    //                 $record['aboutImg'] = base64_encode($record['aboutImg']);
    //                 $resultArray[] = $record;
    //             }
    //             $json_result['result'] = $resultArray;
    //         } else {
    //             $json_result['response'] = 'Unsuccesful';
    //         }

    //         $json_result['offset'] = $offset;
    //         // return $json_result;


    //         header("Content-Type: application/json; charset=UTF-8");
    //         echo json_encode($json_result);
    //     } catch (\Throwable $th) {
    //         //throw $th;
    //         echo json_encode(
    //             array(
    //                 'response' => 'Unsuccessful',
    //                 'message' => 'Event not added',
    //                 'status' => 'error',
    //                 'error' => $th->getMessage()
    //             )
    //         );
    //     }
    // }
}
