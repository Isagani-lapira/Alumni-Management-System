<?php

require_once 'connection.php';
if (isset($_POST['action'])) {
    $action = $_POST['action'];

    switch ($action) {
        case 'insertAOY':
            $aomID = $_POST['aomID'];
            $personID = $_POST['personID'];
            $colCode = $_POST['colCode'];
            $reason = $_POST['reason'];
            insertAOY($aomID, $personID, $colCode, $reason, $mysql_con);
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


function insertAOY($aomID, $personID, $colCode, $reason, $con)
{
    $query = "INSERT INTO `alumni_of_the_year`( `AOMID`, `personID`, `colCode`, `reason`) 
    VALUES (?,?,?,?)";
    $stmt = mysqli_prepare($con, $query);
    $stmt->bind_param('ssss', $aomID, $personID, $colCode, $reason);
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
        "personID"=>$personID,
        "aomID" => $AOMID,
        "fullname" => $fullname,
        "reason" => $reason,
        "year" => $year,
        "colCode"=>$colCode
    );

    echo json_encode($data);
}
