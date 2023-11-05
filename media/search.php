<?php

require_once  '../config.php';

require_once SITE_ROOT . '/college/php/connection.php';
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

try {
    $media = $_GET['media'];

    // sanitize the media
    $media = htmlspecialchars($media, ENT_QUOTES, 'UTF-8');

    switch ($media) {
        case 'profile_pic':
            // check if personID is set
            if (!isset($_GET['personID'])) {
                // send a response to the client
                http_response_code(400);
                exit();
            }


            // get the personID
            $personID = $_GET['personID'];

            // get the profile picture of the user
            $query = 'SELECT profilepicture FROM person WHERE personID = ?';
            $stmt = $mysql_con->prepare($query);
            $stmt->bind_param('s', $personID);
            $stmt->execute();

            $result = $stmt->get_result();
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

            break;
        case 'cover_photo':
            // check if personID is set
            if (!isset($_GET['personID'])) {
                // send a response to the client
                http_response_code(400);
                exit();
            }
            // get the personID
            $personID = $_GET['personID'];

            // get the profile picture of the user
            $query = 'SELECT cover_photo FROM person WHERE personID = ?';
            $stmt = $mysql_con->prepare($query);
            $stmt->bind_param('s', $personID);
            $stmt->execute();


            $result = $stmt->get_result();
            if (
                $result->num_rows === 0
            ) {
                http_response_code(404);
                exit();
            }
            $row =  $result->fetch_assoc();
            // check if there is a cover photo
            if ($row['cover_photo'] === null) {
                // send a response to the client
                http_response_code(404);
                exit();
            }
            header("Content-Type: image/jpeg");
            echo $row['cover_photo'];
            break;

        case 'aom':
            if (!isset($_GET['AOMID'])) {
                // send a response to the client
                http_response_code(400);
                exit();
            }
            $AOMID = $_GET['AOMID'];

            $query = 'SELECT cover_img FROM alumni_of_the_month WHERE AOMID = ?';
            $stmt = $mysql_con->prepare($query);
            $stmt->bind_param('s', $AOMID);
            $stmt->execute();

            $result = $stmt->get_result();
            if (
                $result->num_rows === 0
            ) {
                http_response_code(404);
                exit();
            }

            $row =  $result->fetch_assoc();
            // check if there is a cover photo
            if ($row['cover_img'] === null) {
                // send a response to the client
                http_response_code(404);
                exit();
            }

            header("Content-Type: image/jpeg");
            echo $row['cover_img'];
            break;

        default:
            // send a response to the client
            http_response_code(400);
            exit();
    }
} catch (\Throwable $th) {
    echo $th->getMessage();
}
