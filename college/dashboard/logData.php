<?php
session_start();
require_once '../php/connection.php';
require_once '../php/checkLogin.php';

// if (isset($_POST['action'])) {
//     $action = $_POST['action'];

//     if ($action == "RetrieveData")
//         getCollegeAction($mysql_con);
//     else if ($action == "retrieveByDate") {
//         $startDate = $_POST['startDate'];
//         $endDate = $_POST['endDate'];
//         $college = $_POST['colCode'];
//         getActionByDate($startDate, $endDate, $college, $mysql_con);
//     }
// }
header("Content-Type: application/json");

if (isset($_POST['action'])) {

    $id = $_SESSION['adminID'];

    if ($_POST['action'] === 'filterDate') {

        // match based from the date
        $sql_statement = match ($_POST['date']) {

            'today' => "SELECT * FROM collegeadmin_log WHERE DATE(timestamp) = CURDATE() AND colAdmin = '$id';",
            'week' => "SELECT * FROM collegeadmin_log WHERE timestamp >= DATE_SUB(CURDATE(), INTERVAL 1 WEEK) AND timestamp < CURDATE() AND colAdmin = '$id';",
            'month' => "SELECT * FROM collegeadmin_log WHERE timestamp >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH) AND timestamp < CURDATE() AND colAdmin = '$id';",
            'year' => "SELECT * FROM collegeadmin_log WHERE timestamp >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR) AND timestamp < CURDATE() AND colAdmin = '$id';",
            'all' => "SELECT * FROM collegeadmin_log;",
            default => "SELECT * FROM collegeadmin_log WHERE DATE(timestamp) = CURDATE() AND colAdmin = '$id';",
        };

        try {
            $stmt = mysqli_prepare($mysql_con, $sql_statement);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = mysqli_num_rows($result);

            // check if the records are more than 1

            // Get all the details from the result
            $resultArray = array();

            $data = array();
            if ($result && $row > 0) {
                $response = "Success";
                while ($data = $result->fetch_assoc()) {
                    $resultArray[] = $data;
                }
                $data = array(
                    "response" => "Success",
                    "result" => $resultArray,
                );
            } else {
                $data = array(
                    "response" => "No Data",
                    "result" => null,
                );
            }

            echo json_encode($data);
        } catch (\Throwable $th) {
            echo json_encode(
                array(
                    "response" => "Request Failed"
                )
            );
        }
    }
} else {

    echo json_encode(
        array(
            "response" => "Request Failed"
        )
    );
}

function getCollegeAction($con)
{
    $query = "SELECT * FROM `collegeadmin_log` ORDER BY `timestamp` DESC LIMIT 50"; //get all the log for today
    $stmt = mysqli_prepare($con, $query);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = mysqli_num_rows($result);

    getDetails($result, $row, $con); //get details
}

function getDetails($result, $row, $con)
{
    $response = 'Unsuccess';
    $action = array();
    $timestamp = array();
    $details = array();
    $colCode = array();
    $colLogo = array();
    $colname = array();
    $colAdminName = array();
    if ($result && $row > 0) {
        $response = "Success";
        while ($data = $result->fetch_assoc()) {
            $action[] = $data['action'];
            $colAdmin = $data['colAdmin'];
            $timestamp[] = $data['timestamp'];
            $details[] = $data['details'];

            // get college data
            $queryCollege = "SELECT college.colCode, college.colLogo,colname,
            CONCAT(person.fname,' ',person.lname) AS 'fullname'
            FROM coladmin JOIN college ON coladmin.colCode = college.colCode
            JOIN person ON coladmin.personID = person.personID
            WHERE coladmin.adminID = ? ";

            $stmtCollege = mysqli_prepare($con, $queryCollege);
            $stmtCollege->bind_param('s', $colAdmin);
            $stmtCollege->execute();
            $resultCol = $stmtCollege->get_result();

            if ($resultCol) {
                while ($data = $resultCol->fetch_assoc()) {
                    $colCode[] = $data['colCode'];
                    $colname[] = $data['colname'];
                    $colLogo[] = base64_encode($data['colLogo']);
                    $colAdminName[] = $data['fullname'];
                }
            }
        }
    }

    $data = array(
        "response" => $response,
        "action" => $action,
        "timestamp" => $timestamp,
        "details" => $details,
        "colCode" => $colCode,
        "colname" => $colname,
        "colLogo" => $colLogo,
        "colAdminName" => $colAdminName,
    );

    echo json_encode($data);
}


function getActionByDate($startDate, $endDate, $college, $con)
{

    if ($startDate != "" && $college == "") {
        // filter by date
        $query = "SELECT * FROM `collegeadmin_log` WHERE DATE(timestamp) >= ? 
        AND DATE(timestamp) <= ? ORDER BY `timestamp` DESC";
        $stmt = mysqli_prepare($con, $query);
        $stmt->bind_param('ss', $startDate, $endDate);
    } else if ($startDate == "" && $college != "") {
        // filter by college
        $query = "SELECT `collegeadmin_log`.*
        FROM `collegeadmin_log`
        JOIN `coladmin` ON `collegeadmin_log`.`colAdmin` = `coladmin`.`adminID`
        WHERE `coladmin`.colCode = ? ";
        $stmt = mysqli_prepare($con, $query);
        $stmt->bind_param('s', $college);
    } else if ($startDate != "" && $college != "") {

        // filter by both college and date
        $query = "SELECT `collegeadmin_log`.*
        FROM `collegeadmin_log`
        JOIN `coladmin` ON `collegeadmin_log`.`colAdmin` = `coladmin`.`adminID`
        WHERE `coladmin`.`colCode` = ? AND DATE(`collegeadmin_log`.`timestamp`) >= ? 
        AND DATE(`collegeadmin_log`.`timestamp`) <= ? ORDER BY `collegeadmin_log`.`timestamp` DESC";

        $stmt = mysqli_prepare($con, $query);
        $stmt->bind_param('sss', $college, $startDate, $endDate);
    } else {
        // no filter
        getCollegeAction($con);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $row = mysqli_num_rows($result);

    getDetails($result, $row, $con); //get details
}
