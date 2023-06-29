<?php

    require_once 'connection.php';
    require_once 'userTable.php';

    if(isset($_POST['action'])){
        $data = $_POST['action'];
        $actionArray = json_decode($data, true);
        $action = $actionArray['action'];
        $username = $_POST['username'];
        $password = $_POST['password'];

        //check what are to be perform
        switch($action){
            case 'create':
                echo 'rar';
                break;
            case 'read':
                //check first if it has query
                if(isset($actionArray['query'])){
                    $query = $actionArray['query'];
                    $checkCreds = new User_Table();

                    //if there's a query go to searching with query
                    if($query == 1)$checkCreds->checkUser($username,$password,$mysql_con);

                    //else go to select all searching
                }
                break;
            default:
                echo 'not pumasok sa kahit saan';
                break;
        }
    }
    else echo 'not pumasok';

?>