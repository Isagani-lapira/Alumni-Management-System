<?php

require_once 'connection.php';
if (isset($_POST['action'])) {
    $data = $_POST['action'];
    $actionArray = json_decode($data, true);
    $action = $actionArray['action'];

    switch ($action) {
        case 'read':
            $email =  $_POST['personalEmail'];
            checkEmail($email, $mysql_con);
            break;
    }
} else echo 'ayaw pumasok';


function checkEmail($email, $con)
{
    $query = 'SELECT * FROM `person` WHERE `personal_email`="' . $email . '"';
    $result = mysqli_query($con, $query);
    $row = mysqli_num_rows($result);

    if ($row > 0) echo 'Exist';
    else echo 'Available';
}
