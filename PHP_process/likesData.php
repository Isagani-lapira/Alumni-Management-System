<?php

require_once 'connection.php';
require 'likesTB.php';

if (isset($_POST['action'])) {
    //get the data sent 
    $actionArray = $_POST['action'];
    $actionJSON = json_decode($actionArray, true);
    $action = $actionJSON['action'];

    $likeObj = new Likes();
    switch ($action) {
        case 'readLikes':
            $postID =  $actionJSON['postID'];
            $likeObj->getLikes($postID, $mysql_con);
            break;
        default:
            echo 'rar';
            break;
    }
} else echo 'not pumasok';
