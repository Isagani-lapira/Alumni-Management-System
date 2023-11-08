<?php

require_once 'connection.php';
if (isset($_POST['action'])) {
    $action = $_POST['action'];
    if ($action == "deployNewTracer") {
        insertNewTracerDeployment($mysql_con);
    } else if ($action == "retrieveNewlyDeploy") {
        insertNewTracerDeployment($mysql_con);
    } else if ($action == "retrieveRespondent") {
        retrieveLast5YearResponse($mysql_con);
    } else if ($action == 'retrieveList') {
        retrieveListTracer($mysql_con);
    }
}


function insertNewTracerDeployment($con)
{
    // get tracer ID first
    $queryForm = "SELECT `formID` FROM `tracer_form`";
    $stmtForm = mysqli_prepare($con, $queryForm);
    $stmtForm->execute();
    $form = $stmtForm->get_result();
    $tracerID = $form->fetch_assoc();
    $tracerID = $tracerID['formID'];

    $deploymentID = substr(md5(uniqid()), 0, 29);
    $endDate = date('Y-12-31');
    // insert deployment data
    $query = "INSERT INTO `tracer_deployment`(`tracer_deployID`, `formID`,
    `end_date`) VALUES (? ,? ,?)";
    $stmt = mysqli_prepare($con, $query);
    $stmt->bind_param('sss', $deploymentID, $tracerID, $endDate);
    $result = $stmt->execute();

    if ($result) echo 'Success';
    else echo 'Unsuccess';
}

function retrievedDeployment($con)
{
    // get if there's tracer form available based on the current date
    $query = "SELECT `tracer_deployID` FROM `tracer_deployment` WHERE CURRENT_DATE<=`end_date` ORDER BY `timstamp` DESC LIMIT 1";
    $stmt = mysqli_prepare($con, $query);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = mysqli_num_rows($result);

    if ($result && $row > 0) {
        $data = $result->fetch_assoc();
        return $data['tracer_deployID'];
    } else return 'None';
}


function retrieveLast5YearResponse($con)
{
    // get all the total count of response for each year in last 5 years
    $query = "SELECT YEAR(t.timstamp) AS deployment_year, COUNT(a.tracer_deployID) AS answer_count
    FROM tracer_deployment t
    LEFT JOIN answer a ON t.tracer_deployID = a.tracer_deployID
    WHERE t.timstamp >= DATE_SUB(NOW(), INTERVAL 5 YEAR) AND a.status = 'done'
    GROUP BY deployment_year
    ORDER BY deployment_year DESC";
    $stmt = mysqli_prepare($con, $query);
    $stmt->execute();
    $result = $stmt->get_result();

    $response = "Unsuccess";
    $year = array();
    $respondentCount = array();

    if ($result) {
        $response = "Success";
        while ($data = $result->fetch_assoc()) {
            $year[] = $data['deployment_year'];
            $respondentCount[] = $data['answer_count'];
        }
    }

    $data = array(
        "response" => $response,
        "year" => $year,
        "respondent" => $respondentCount
    );

    echo json_encode($data);
}

// get list of deployed tracer
function retrieveListTracer($con)
{
    $query = "SELECT `tracer_deployID`, YEAR(`timstamp`) AS 'year' FROM 
    `tracer_deployment` ORDER BY YEAR(`timstamp`) DESC";
    $stmt = mysqli_prepare($con, $query);

    $response = "Failed";
    $tracerDeployID = array();
    $year = array();

    if ($stmt) {
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result) {
            $response = "Success";
            while ($data = $result->fetch_assoc()) {
                $tracerDeployID[] = $data['tracer_deployID'];
                $year[] = $data['year'];
            }
        }
    }

    $data = array(
        "response" => $response,
        "tracerDeploymentID" => $tracerDeployID,
        "year" => $year
    );

    echo json_encode($data);
}
