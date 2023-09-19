<?php
require 'connection.php';
require 'deploymentTracer.php';

session_start();
if (isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action == "addNewAnswer")
        addAnswer($mysql_con);
}


function addAnswer($con)
{
    $personID = $_SESSION['personID'];
    // check first if there's already an answer
    $queryChecked = "SELECT `answerID` FROM `answer` WHERE `personID`= ?";
    $stmtChecked = mysqli_prepare($con, $queryChecked);
    $stmtChecked->bind_param('s', $personID);
    $stmtChecked->execute();
    $result = $stmtChecked->get_result();
    $row = mysqli_num_rows($result);

    // if none then create entry
    if ($result && $row == 0) {
        $tracerID = retrievedDeployment($con);
        $answerID = substr(md5(uniqid()), 0, 29);
        $queryInsertion = "INSERT INTO `answer`(`answerID`, `personID`, 
        `tracer_deployID`) VALUES (?, ?, ?)";

        $stmt = mysqli_prepare($con, $queryInsertion);
        $stmt->bind_param('sss', $answerID, $personID, $tracerID);
        $result = $stmt->execute();

        if ($result) echo $answerID;
        else echo 'Unsuccess';
    } else {
        // the user already click the proceed before return only the answerID for references
        $data = $result->fetch_assoc();
        $answerID = $data['answerID'];
        echo $answerID;
    }
}
