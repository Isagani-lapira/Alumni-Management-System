<?php
session_start();


require '../php/connection.php';
require '../model/AlumniOfTheMonth.php';
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

    $results = null;
    $model = new AlumniOfTheMonth($mysql_con, $_SESSION['colCode']);

    header("Content-Type: application/json; charset=UTF-8");

    if (isset($_GET['partial']) &&   $_GET['partial'] === 'true') {
        // get the offset from the url
        $offset = $_GET['offset'];
        //convert to int
        $offset = (int) $offset;

        $results = $model->getAllLatest($offset);
        echo json_encode(['data' => $results, 'response' => 'Successful']);
    } else if (isset($_GET['getPersonId']) && isset($_GET['personId'])) {
        $model = new AlumniModel($mysql_con, $_SESSION['colCode']);
        $results = $model->getFullAlumniDetailById($_GET['personId'], true);
        echo json_encode(['data' => [$results], 'response' => 'Successful']);
    } else {
        // Return the full detail of the alumni of the month
        $results = $model->getFullDetailById($_GET['studentNo']);
        // header("Content-Type: application/json; charset=UTF-8");
        echo json_encode(['data' => [$results], 'response' => 'Successful']);
    }
} else {
    echo "You are not supposed to be here.";
    header("refresh:5; url=../index.php");
    exit();
}
