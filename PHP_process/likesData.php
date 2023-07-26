<?php

require_once 'connection.php';
require 'likesTB.php';
session_start();

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
        case 'addLike':
            $postID = $_POST['postID'];
            $username = $_SESSION['username'];
            $likeObj->addLikes($username, $postID, $mysql_con);
            break;
        case 'removeLike':
            $postID = $_POST['postID'];
            $username = $_SESSION['username'];
            $likeObj->removeLike($username, $postID, $mysql_con);
            break;
        default:
            echo 'rar';
            break;
    }
} else echo 'not pumasok';
