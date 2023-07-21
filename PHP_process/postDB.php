<?php

session_start();
require_once 'connection.php';
require 'PostTB.php';

if (isset($_POST['action'])) {
    $actionArray = $_POST['action'];
    $data = json_decode($actionArray, true);
    $action = $data['action'];
    $post = new PostData();

    switch ($action) {
        case 'insert':
            insertData($mysql_con);
            break;
        case 'read':
            $username = $_SESSION['username'];
            $startgDate = $_POST['startDate'];
            $endDate = $_POST['endDate'];
            $post->getPostAdmin($username, $startgDate, $endDate, $mysql_con);
            break;
        case 'readColPost':
            $username = $_SESSION['username'];
            $college = $_SESSION['colCode'];
            $date = $data['todayDate'];
            $post->getCollegePost($username, $college, $date, $mysql_con);
    }
}

function insertData($con)
{
    //data that will be sending
    $caption = $_POST['caption'];
    $college = $_POST['college'];
    $uploadedFiles = $_FILES['files'];

    $random = rand(0, 4000);
    $postID = uniqid() . '-' . $random;
    $username = $_SESSION['username']; // to be changed
    $date = date('y/m/d');
    $post = new PostData();

    //process of insertion
    $insertion = $post->insertPostData(
        $postID,
        $username,
        $college,
        $caption,
        $date,
        $uploadedFiles,
        $con
    );

    if ($insertion) echo 'successful';
    else echo 'unsuccessful';
}
