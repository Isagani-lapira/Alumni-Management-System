<?php
session_start();


require '../php/connection.php';
require '../model/AlumniModel.php';

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
    $alumniModel = new AlumniModel($mysql_con, $_SESSION['colCode']);

    // &&   $_GET['partial'] === 'true'
    if (isset($_GET['search'])) {
        $qName = $_GET['qName'];


        $results = $alumniModel->getSearch($qName);
        header("Content-Type: application/json; charset=UTF-8");
        if ($results) {

            echo json_encode(array('response' => 'Successful', 'data' => $results));
        } else {
            echo json_encode(array('response' => 'Unsuccessful', 'data' => []));
        }
    } else {
        // // Return the full detail of the alumni of the month
        // $results = $alumniModel->getFullDetailById($_GET['studentNo']);
        // // header("Content-Type: application/json; charset=UTF-8");
        // echo json_encode($results);
    }
} else {
    echo "You are not supposed to be here.";
    header("refresh:5; url=../index.php");
    exit();
}
