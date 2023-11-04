<?php

require_once 'connection.php';
require 'PostTB.php';
require_once 'notificationTable.php';
session_start();
if (isset($_POST['action'])) {
    $action = $_POST['action'];

    switch ($action) {
        case 'insertAOY':
            $aomID = $_POST['aomID'];
            $personID = $_POST['personID'];
            $colCode = $_POST['colCode'];
            $reason = $_POST['reason'];
            $colleges = json_decode($_POST['colleges'], true);
            $aoy = $_POST['aoy'];
            $adminUN = $_POST['adminUN'];
            insertAOY($aomID, $adminUN, $personID, $colCode, $reason, $colleges, $aoy, $mysql_con);
            break;
        case 'checkForThisYearAOY':
            echo checkForThisYearAOY($mysql_con);
            break;
        case 'retrieveAOY':
            $offset = $_POST['offset'];
            retrieveAOY($offset, $mysql_con);
            break;
    }
}


function insertAOY($aomID, $adminUN, $personID, $colCode, $reason, $colleges, $aoy, $con)
{
    // adding new AOY in the database
    $query = "INSERT INTO `alumni_of_the_year`( `AOMID`, `personID`, `colCode`, `reason`) 
    VALUES (?,?,?,?)";
    $stmt = mysqli_prepare($con, $query);
    $stmt->bind_param('ssss', $aomID, $personID, $colCode, $reason);
    $result = $stmt->execute();

    if ($result) {
        // post the alumni of the year in the community
        $id = "";
        $post = new PostData();
        $colleges = array_slice($colleges, 1);

        // post the alumni of the  year to all colleges in the system
        foreach ($colleges as $college) {
            $random = rand(0, 4000);
            $postID = uniqid() . '-' . $random;
            $post->createAOYPost($aomID, $adminUN, $postID, $colCode, $con);

            if ($colCode === $college) $id = $postID;
        }

        //notify the alumni
        $notification = new Notification();
        $typeOfNotif = 'aoy';
        $resultNotif = $notification->insertNotif($id, $aoy, $typeOfNotif, $con);

        if ($resultNotif) echo 'Success';
        else echo 'Failed';
    } else echo 'Failed';
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


function retrieveAOY($offset, $con)
{
    $maxLimit = 10;

    // query for retrieving data about alumni of the year
    $query = "SELECT ay.*, p.fname, p.lname,ay.`colCode`
    FROM alumni_of_the_year AS ay
    JOIN alumni_of_the_month AS am ON ay.aomid = am.aomid
    JOIN person AS p ON am.personID = p.personID
    ORDER BY ay.`year` DESC
    LIMIT ?, ?";


    $stmt = mysqli_prepare($con, $query);

    $response = "Unsuccess";
    $AOYID = array();
    $AOMID = array();
    $personID = array();
    $fullname = array();
    $reason = array();
    $year = array();
    $colCode = array();

    if ($stmt) {
        $stmt->bind_param('dd', $offset, $maxLimit);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = mysqli_num_rows($result);

        if ($result && $row > 0) {
            $response = "Success";
            while ($data = $result->fetch_assoc()) {
                $AOMID[] = $data['AOMID'];
                $personID[] = $data['personID'];
                $fullname[] = $data['fname'] . ' ' . $data['lname'];
                $reason[] = $data['reason'];
                $year[] = $data['year'];
                $colCode[] = $data['colCode'];
            }
        }
    }

    $data = array(
        "response" => $response,
        "aoyID" => $AOYID,
        "personID" => $personID,
        "aomID" => $AOMID,
        "fullname" => $fullname,
        "reason" => $reason,
        "year" => $year,
        "colCode" => $colCode
    );

    echo json_encode($data);
}
