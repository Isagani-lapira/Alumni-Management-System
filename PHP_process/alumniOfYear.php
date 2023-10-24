<?php

require_once 'connection.php';
if (isset($_POST['action'])) {
    $action = $_POST['action'];

    switch ($action) {
        case 'insertAOY':
            $aomID = $_POST['aomID'];
            $reason = $_POST['reason'];
            insertAOY($aomID, $reason, $mysql_con);
            break;
        case 'checkForThisYearAOY':
            echo checkForThisYearAOY($mysql_con);
            break;
    }
}


function insertAOY($aomID, $reason, $con)
{
    $query = "INSERT INTO `alumni_of_the_year`( `AOMID`, `reason`) 
    VALUES (?,?)";
    $stmt = mysqli_prepare($con, $query);
    $stmt->bind_param('ss', $aomID, $reason);
    $result = $stmt->execute();

    if ($result) echo 'Success';
    else echo 'Failed';
}

function checkForThisYearAOY($con)
{
    $query = "SELECT COUNT(*) FROM `alumni_of_the_year` WHERE `year` = YEAR(CURRENT_DATE)";
    $stmt = mysqli_prepare($con, $query);

    if ($stmt) {
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        if ($count == 0) return true;

        return false;
    }
}
