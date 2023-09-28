<?php

require_once 'connection.php';

if (isset($_POST['action'])) {
    $action = $_POST['action'];

    switch ($action) {
        case 'thisMonthAOM':
            getAOTM($mysql_con);
            break;
        case 'filterAOM':
            $month = ($_POST['month'] == '') ? '' : $_POST['month'];
            $colCode = ($_POST['colCode'] == '') ? '' : $_POST['colCode'];
            $year = ($_POST['year'] == '') ? '' : $_POST['year'];

            getAOTMByFilter($month, $colCode, $year, $mysql_con);
    }
}


function getAOTM($con)
{
    // query to get all the alumni of the month on this month
    $query = "SELECT a.studentNo, a.cover_img, a.colCode, 
    p.personal_email, CONCAT(p.fname,' ' ,p.lname) AS 'fullname'
    FROM alumni_of_the_month AS a
    INNER JOIN person AS p ON a.personID = p.personID
    WHERE MONTH(a.date_assigned) = MONTH(CURRENT_DATE())
    AND YEAR(a.date_assigned) = YEAR(CURRENT_DATE()) ";

    $stmt = mysqli_prepare($con, $query);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result) alumniOfMonthDetails($result);
}

function getAOTMByFilter($month, $colCode, $year, $con)
{
    $stmt = null;
    $query = "SELECT a.studentNo, a.cover_img, a.colCode, 
    p.personal_email, CONCAT(p.fname,' ' ,p.lname) AS 'fullname'
    FROM alumni_of_the_month AS a
    INNER JOIN person AS p ON a.personID = p.personID ";

    if ($month != "" && $colCode != "" && $year != "") { //all filter included
        $query .= "WHERE MONTH(a.date_assigned) = ? AND YEAR(a.date_assigned) = ? AND `colCode` = ?";
        $stmt = mysqli_prepare($con, $query);
        $stmt->bind_param('ss', $month, $year, $colCode);
    } else if ($month != "" && $colCode == "" && $year == "") { //only month filtered
        $query .= "WHERE MONTH(a.date_assigned) = ?";
        $stmt = mysqli_prepare($con, $query);
        $stmt->bind_param('s', $month);
    } else if ($month == '' && $colCode != "" && $year == "") { //only colcode available
        $query .= "WHERE `colCode`= ? ";
        $stmt = mysqli_prepare($con, $query);
        $stmt->bind_param('s', $colCode);
    } else if ($month == '' && $colCode == "" && $year != "") { //only year
        $query .= "WHERE MONTH(a.date_assigned) = MONTH(CURRENT_DATE())
        AND YEAR(a.date_assigned) = ? ";
        $stmt = mysqli_prepare($con, $query);
        $stmt->bind_param('s', $year);
    } else if ($month != '' && $colCode == "" && $year != "") { //month and year filter
        $query .= "WHERE MONTH(a.date_assigned) = ? AND 
        YEAR(a.date_assigned) = ? ";
        $stmt = mysqli_prepare($con, $query);
        $stmt->bind_param('ss', $month, $year);
    } else if ($month == '' && $colCode != "" && $year != "") { //colcode and year filter
        $query .= "WHERE `colCode` = ? AND 
        YEAR(a.date_assigned) = ? ";
        $stmt = mysqli_prepare($con, $query);
        $stmt->bind_param('ss', $colCode, $year);
    } else if ($month != '' && $colCode != "" && $year == "") { //month and colcode
        $query .= "WHERE MONTH(a.date_assigned) = ? AND `colCode` = ?";
        $stmt = mysqli_prepare($con, $query);
        $stmt->bind_param('ss', $month, $colCode);
    }

    // get the result
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result) alumniOfMonthDetails($result);
}

function alumniOfMonthDetails($result)
{
    $row = mysqli_num_rows($result);
    $response = "Unsuccessful";
    $studentNo = array();
    $img = array();
    $colCode = array();
    $personalEmail = array();
    $fullname = array();

    if ($row > 0) {
        $response = 'Success';
        while ($data = $result->fetch_assoc()) {
            //data to be store
            $studentNo[] = $data['studentNo'];
            $img[] = base64_encode($data['cover_img']);
            $colCode[] = $data['colCode'];
            $personalEmail[] = $data['personal_email'];
            $fullname[] = $data['fullname'];
        }
    }

    // send data as json
    $data = array(
        "response" => $response,
        "profile" => $img,
        "studentNo" => $studentNo,
        "colCode" => $colCode,
        "personalEmail" => $personalEmail,
        "fullname" => $fullname,
    );

    echo json_encode($data);
}
