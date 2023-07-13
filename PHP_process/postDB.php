<?php

require_once 'connection.php';
require 'PostTB.php';

if (isset($_POST['action'])) {
    $actionArray = $_POST['action'];
    $data = json_decode($actionArray, true);
    $action = $data['action'];

    switch ($action) {
        case 'insert':
            insertData($mysql_con);
            break;
        case 'read':
            $username = 'isagani@@'; //to be change
            $startgDate = $_POST['startDate'];
            $endDate = $_POST['endDate'];
            $post = new PostData();
            $post->getPostAdmin($username, $startgDate, $endDate, $mysql_con);
            break;
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
    $username = 'isagani@@'; // to be changed
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
