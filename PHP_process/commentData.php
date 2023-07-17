<?php

require 'commentTB.php';
require_once 'connection.php';

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
    }
} else echo 'not pumasok';
