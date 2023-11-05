<?php

require_once  '../config.php';

require_once SITE_ROOT . '/PHP_process/connection.php';

// check server method if it is get

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    // send a response to the client
    http_response_code(405);
    exit();
}



// check if media is set
if (!isset($_GET['media'])) {
    // send a response to the client
    http_response_code(400);

    exit();
}


// if media === profile_pic, get the profile picture of the user using it's personID
if ($_GET['media'] === 'profile_pic') {


    // check if personID is set
    if (!isset($_GET['personID'])) {
        // send a response to the client
        http_response_code(400);
        exit();
    }


    // get the personID
    $personID = $_GET['personID'];

    try {
        // get the profile picture of the user
        $query = 'SELECT profilepicture FROM person WHERE personID = ?';
        $stmt = $mysql_con->prepare($query);
        $stmt->bind_param('s', $personID);
        $stmt->execute();

        $result = $stmt->get_result();
    } catch (\Throwable $th) {
        // throw $th;
        echo $th->getMessage();
    }


    if (
        $result->num_rows
    ) {

        $row =  $result->fetch_assoc();
        header("Content-Type: image/jpeg");

        echo $row['profilepicture'];
    } else {
        // send a response to the client
        http_response_code(404);
        exit();
    }
} else if ($_GET['media'] === 'cover_photo') {

    // check if personID is set
    if (!isset($_GET['personID'])) {
        // send a response to the client
        http_response_code(400);
        exit();
    }


    try {
        // get the personID
        $personID = $_GET['personID'];

        // get the profile picture of the user
        $query = 'SELECT cover_photo FROM person WHERE personID = ?';
        $stmt = $mysql_con->prepare($query);
        $stmt->bind_param('s', $personID);
        $stmt->execute();


        $result = $stmt->get_result();
    } catch (\Throwable $th) {
        //throw $th;

        echo $th->getMessage();
    }

    if (
        $result->num_rows
    ) {


        $row =  $result->fetch_assoc();
        // check if there is a cover photo
        if ($row['cover_photo'] === null) {
            // send a response to the client
            http_response_code(404);
            exit();
        }
        header("Content-Type: image/jpeg");

        echo $row['cover_photo'];
    } else {
        // send a response to the client
        http_response_code(404);
        exit();
    }
}
