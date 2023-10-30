<?php

require_once 'connection.php';
if (isset($_POST['action'])) {
    $action = $_POST['action'];

    switch ($action) {
        case 'retrieveCountReportFiltered':
            $filter = $_POST['filter'];
            $colCode = $_POST['colCode'];

            retrieveFilterReport($filter, $colCode, $mysql_con);
            break;
    }
}

function retrieveFilterReport($filter, $colCode, $con)
{
    // Base query without filtering by colCode
    $query = "SELECT DATE_FORMAT(rp.`timestamp`, '%m') AS `Month`, COUNT(rp.`reportID`) AS `ReportCount`
    FROM `report_post` rp
    JOIN `post` p ON rp.`postID` = p.`postID`
    WHERE rp.`report_category` = ? 
      AND YEAR(rp.`timestamp`) = YEAR(CURDATE())
      AND p.`status` = ?";

    // If colCode is not empty, add it to the query
    if (!empty($colCode)) {
        $query .= " AND p.`colCode` = ?";
    }

    $query .= " GROUP BY `Month`"; // Add the GROUP BY clause here

    $stmt = mysqli_prepare($con, $query);

    $response = "Unsuccess";
    $month = array();
    $reportCount = array();

    if ($stmt) {
        $STATUS = 'available';

        // If colCode is not empty, bind it as a parameter
        if (!empty($colCode)) {
            $stmt->bind_param('sss', $filter, $STATUS, $colCode);
        } else {
            $stmt->bind_param('ss', $filter, $STATUS);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $row = mysqli_num_rows($result);

        if ($result && $row > 0) {
            $response = "Success";
            while ($data = $result->fetch_assoc()) {
                $month[] = $data['Month'];
                $reportCount[] = $data['ReportCount'];
            }
        }
    }

    $data = array(
        "response" => $response,
        "month" => $month,
        "reportCount" => $reportCount
    );

    echo json_encode($data);
}
