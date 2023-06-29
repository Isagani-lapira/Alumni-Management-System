<?php

    if(isset($_POST['action'])){
        $data = $_POST['action'];
        $actionArray = json_decode($data, true);
        $action = $actionArray['action'];

        //check what are to be perform
        switch($action){
            case 'create':
                echo 'rar';
                break;
            case 'read':
                echo 'pumasok sa read';
                break;
            default:
                echo 'not pumasok sa kahit saan';
                break;
        }
    }
    else echo 'not pumasok';
?>