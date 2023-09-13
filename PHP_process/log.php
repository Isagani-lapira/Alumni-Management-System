<?php
require_once 'connection.php';

if (isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action == "RetrieveData") {
        $offset = $_POST['offset'];
        getCollegeAction($offset, $mysql_con);
    }
}
function getCollegeAction($offset, $con)
{
    $max = 10;
    $query = "SELECT * FROM `collegeadmin_log` ORDER by `timestamp`DESC LIMIT $offset,$max";
    $stmt = mysqli_prepare($con, $query);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = mysqli_num_rows($result);

    $response = 'Unsuccess';
    $action = array();
    $timestamp = array();
    $details = array();
    $colCode = array();
    $colLogo = array();
    if ($result && $row > 0) {
        $response = "Success";
        while ($data = $result->fetch_assoc()) {
            $action[] = $data['action'];
            $colAdmin = $data['colAdmin'];
            $timestamp[] = $data['timestamp'];
            $details[] = $data['details'];

            // get college data
            $queryCollege = "SELECT college.colCode,college.colLogo
            FROM coladmin JOIN college ON coladmin.colCode = college.colCode
            WHERE coladmin.adminID = ? ";
            $stmtCollege = mysqli_prepare($con, $queryCollege);
            $stmtCollege->bind_param('s', $colAdmin);
            $stmtCollege->execute();
            $resultCol = $stmtCollege->get_result();

            if ($resultCol) {
                while ($data = $resultCol->fetch_assoc()) {
                    $colCode[] = $data['colCode'];
                    $colLogo[] = base64_encode($data['colLogo']);
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
        "colLogo" => $colLogo,
    );

    echo json_encode($data);
}
