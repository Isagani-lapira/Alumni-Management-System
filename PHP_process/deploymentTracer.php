<?php

require_once 'connection.php';
if (isset($_POST['action'])) {
    $action = $_POST['action'];
    if ($action == "deployNewTracer") {
        insertNewTracerDeployment($mysql_con);
    } else if ($action == "retrieveNewlyDeploy") {
        insertNewTracerDeployment($con);
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
    $endDate = date('Y-m-d', strtotime('+4 months'));
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
    $query = "SELECT `tracer_deployID` FROM `tracer_deployment` WHERE CURRENT_DATE<=`end_date` ORDER BY `end_date` DESC LIMIT 1";
    $stmt = mysqli_prepare($con, $query);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = mysqli_num_rows($result);

    if ($result && $row > 0) {
        $data = $result->fetch_assoc();
        return $data['tracer_deployID'];
    } else return 'None';
}
