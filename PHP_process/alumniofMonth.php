<?php

require_once 'connection.php';
require 'personDB.php';

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
            break;
        case 'thisYearAOM':
            listOfAOMThisYear($mysql_con);
            break;
        case 'searchAlumniOfTheMonth':
            $aomID = $_POST['aomID'];
            getSelectedAOM($aomID, $mysql_con);
            break;
        case 'getTestimonials':
            $aomID = $_POST['aomID'];
            getTestimony($aomID, $mysql_con);
            break;
        case 'getAchievements':
            $aomID = $_POST['aomID'];
            getAchievements($aomID, $mysql_con);
            break;
        case 'getSkills':
            $aomID = $_POST['aomID'];
            getSkills($aomID, $mysql_con);
            break;
        case 'getAOMSocMed':
            $personID = $_POST['personID'];
            $person = new personDB();
            $person->getSocialMedia($personID, $mysql_con);
            break;
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
        $stmt->bind_param('sss', $month, $year, $colCode);
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

function listOfAOMThisYear($con)
{
    // query to get all the alumni of the month list on assigned this year
    $query = "SELECT a.`AOMID`, a.`colCode`, p.personID, p.fname, p.lname
    FROM alumni_of_the_month AS a
    INNER JOIN person AS p ON a.personID = p.personID
    WHERE YEAR(a.`date_assigned`) = YEAR(CURDATE())";

    $stmt = mysqli_prepare($con, $query);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = mysqli_num_rows($result);

    $response = "Unsuccess";
    $names = array();
    $aomID = array();
    $personID = array();
    $colCode = array();

    if ($result && $row > 0) {
        // data about the alumni of the month
        $response = "Success";
        while ($data = $result->fetch_assoc()) {
            $names[] = $data['fname'] . ' ' . $data['lname'];
            $aomID[] = $data['AOMID'];
            $personID[] = $data['personID'];
            $colCode[] = $data['colCode'];
        }
    }

    $data = array(
        "response" => $response,
        "names" => $names,
        "aomID" => $aomID,
        "personID" => $personID,
        "colCode" => $colCode,
    );

    echo json_encode($data);
}

function getSelectedAOM($aomID, $con)
{
    $query = "SELECT a.`quote`,a.`cover_img`, p.fname, p.lname
    FROM alumni_of_the_month AS a
    INNER JOIN person AS p ON a.personID = p.personID
    WHERE a.`AOMID` = ?";

    $stmt = mysqli_prepare($con, $query);
    $stmt->bind_param('s', $aomID);
    $stmt->execute();
    $result = $stmt->get_result();

    $response = "Unsuccess";

    $fullname = "";
    $quote = "";
    $cover_img = "";

    if ($result) {
        $response = "Success";
        $data = $result->fetch_assoc();
        $fullname = $data['fname'] . ' ' . $data['lname'];
        $quote = $data['quote'];
        $cover_img = base64_encode($data['cover_img']);
    }

    $data = array(
        "response" => $response,
        "fullname" => $fullname,
        "quote" => $quote,
        "cover" => $cover_img,
    );

    echo json_encode($data);
}

function getTestimony($aomID, $con)
{
    // get testimonials
    $testimony = "SELECT `message`,`person_name`,`position`,`profile_img` FROM `testimonials` WHERE `AOMID` = ?";
    $stmtTestimony = mysqli_prepare($con, $testimony);
    $stmtTestimony->bind_param('s', $aomID);
    $stmtTestimony->execute();
    $resultTestimony = $stmtTestimony->get_result();
    $row = mysqli_num_rows($resultTestimony);

    $response = "Unsuccess";
    $message = array();
    $personName = array();
    $position = array();
    $profile = array();
    if ($resultTestimony && $row > 0) {
        while ($data = $resultTestimony->fetch_assoc()) {
            $response = "Success";
            $message[] = $data['message'];
            $personName[] = $data['person_name'];
            $position[] = $data['position'];
            $profile[] = base64_encode($data['profile_img']);
        }
    }

    $data = array(
        "response" => $response,
        "message" => $message,
        "personName" => $personName,
        "position" => $position,
        "profile" => $profile
    );

    echo json_encode($data);
}


function getAchievements($aomID, $con)
{
    // get achievements
    $query = "SELECT `achievement`,`description`, `date` FROM `achievement` WHERE `AOMID` = ?";
    $stmt = mysqli_prepare($con, $query);
    $stmt->bind_param('s', $aomID);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = mysqli_num_rows($result);

    $response = "Unsuccess";
    $achievements = array();
    $description = array();
    $date = array();

    if ($result && $row) {
        $response = "Success";
        while ($data = $result->fetch_assoc()) {
            $achievements[] = $data['achievement'];
            $description[] = $data['description'];
            $date[] = $data['date'];
        }
    }

    $data = array(
        "response" => $response,
        "achievements" => $achievements,
        "description" => $description,
        "date" => $date
    );

    echo json_encode($data);
}

function getSkills($aomID, $con)
{
    $query = "SELECT `skill` FROM `aomskill` WHERE `AOMID` = ?";
    $stmt = mysqli_prepare($con, $query);
    $stmt->bind_param('s', $aomID);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = mysqli_num_rows($result);

    $response = "Unsuccess";
    $skills = array();
    if ($result && $row > 0) {
        $response = "Success";
        while ($data = $result->fetch_assoc()) $skills[] = $data['skill'];
    }

    $data = array(
        "response" => $response,
        "skills" => $skills,
    );

    echo json_encode($data);
}
