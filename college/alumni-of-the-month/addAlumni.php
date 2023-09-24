<?php
session_start();

require "../model/AlumniOfTheMonth.php";
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


    // get session id
    $adminID = $_SESSION['adminID'];
    $colCode = $_SESSION['colCode'];



    // Get the form data;
    $id = $_POST['studentNo'];
    $quote = $_POST['quote'];
    $emailAdd = $_POST['email'];
    $facebookUN = $_POST['facebookUN'];
    $linkedINUN = $_POST['linkedINUN'];
    $instagramUN = $_POST['instagramUN'];
    // image data
    $profile_img = $_FILES['profile-image'];
    $cover_img = $_FILES['cover-image'];

    // Create an instance of the model class
    $alumni = new AlumniOfTheMonth($mysql_con, $colCode);

    // Check if image file is larger than 2MB
    if ($profile_img["size"] > 2000000 && $cover_img["size"] > 2000000) {
        echo "Sorry, your file is too large. Make it 2mb or less";
    }


    // check if image file is jpg
    $imageMimeType = $profile_img["type"];
    $image2MimeType = $cover_img["type"];
    if ($imageMimeType != "image/jpg" && $imageMimeType != "image/jpeg") {

        echo "Sorry, only JPG files are allowed.";
        die();
    }


    // Image data
    $profileImgTmpName =  $_FILES['profile-image']['tmp_name'];
    $profileImg = file_get_contents($profileImgTmpName);

    $cover_imgTmpName =  $_FILES['cover-image']['tmp_name'];
    $cover_img = file_get_contents($cover_imgTmpName);



    // make array of alumni information
    $alumniInformation = array(
        'studentNo' => $id,
        'quote' => $quote,
        'emailAdd' => $emailAdd,
        'facebookUN' => $facebookUN,
        'linkedINUN' => $linkedINUN,
        'instagramUN' => $instagramUN,
        'profile-img' => $profileImg,
        'cover-img' => $cover_img
    );




    // set new alumni
    // TODO add new logging event
    $result = $alumni->setNewAlumniOfTheMonth($id, $alumniInformation,);
    header("Content-Type: application/json; charset=UTF-8");
    if ($result === TRUE) {
        logEventActivity($mysql_con, $_SESSION['adminID'], $_SESSION['colCode']);
        echo json_encode(
            array(
                'response' => 'Successful',
                'message' => 'Alumni added successfully'
            )
        );
    } else {
        echo json_encode(
            array(
                'response' => 'Unsuccessful',
                'message' => 'Alumni not added'
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
