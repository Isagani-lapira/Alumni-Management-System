<?php

require 'commentTB.php';
require_once 'connection.php';
session_start();

if (isset($_POST['action'])) {
    $actionArray = $_POST['action'];
    $actionJSON = json_decode($actionArray, true);
    $action = $actionJSON['action'];
    $commentObj = new Comment();
    //change what action to be perform
    switch ($action) {
        case 'read':
            $postID = $actionJSON['postID'];
            $commentObj->getPostComments($postID, $mysql_con);
            break;
        case 'insertComment':
            $postID = $_POST['postID'];
            $username = $_SESSION['username'];
            $comment = $_POST['comment'];
            $commentObj->insertComment($username, $postID, $comment, $mysql_con);
    }
} else echo 'not pumasok';
