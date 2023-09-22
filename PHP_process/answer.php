<?php
require 'connection.php';
require 'deploymentTracer.php';

session_start();
if (isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action == "addNewAnswer")
        addAnswer($mysql_con);
    else if ($action == "addAnswer") {
        $answerID = $_POST['answerID'];
        $questionID = $_POST['questionID'];
        $choiceID = ($_POST['choiceID'] == "") ? null : $_POST['choiceID'];
        $answerTxt = $_POST['answerTxt'];
        addAnswerData($answerID, $questionID, $answerTxt, $choiceID, $mysql_con);
    } else if ($action == "getAnswer") {
        $questionID = $_POST['questionID'];
        $answerID = $_POST['answerID'];
        getAnswer($questionID, $answerID, $mysql_con);
    } else if ($action == 'addCheckboxAnswer') {
        $answerID = $_POST['answerID'];
        $questionID = $_POST['questionID'];
        $choiceID = $_POST['choiceID'];
        addCheckBoxAnswer($answerID, $questionID, $choiceID, $mysql_con);
    } else if ($action == 'removeCheckBoxAnswer') {
        $questionID = $_POST['questionID'];
        $answerID = $_POST['answerID'];
        $choiceID = $_POST['choiceID'];
        removeCheckedAnswer($questionID, $answerID, $choiceID, $mysql_con);
    }
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

function haveAnswer($answerID, $questionID, $con)
{
    // check if the question already have an answer
    $query = "SELECT `answerID` FROM `answer_data` WHERE `questionID` = ? AND `answerID` = ?";
    $stmt = mysqli_prepare($con, $query);
    $stmt->bind_param('ss', $questionID, $answerID);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = mysqli_num_rows($result);

    if ($result && $row > 0) return true;
    else return false;
}

// insert / update answer
function addAnswerData($answerID, $questionID, $answerTxt, $choiceID, $con)
{
    // haveAnswer($questionID, $con)
    $haveAnswer = haveAnswer($answerID, $questionID, $con);
    $result = "";
    if ($haveAnswer) {
        // update the answer
        $query = "UPDATE `answer_data` SET `choiceID`= ? , `answer_txt`= ? WHERE `questionID` = ? AND `answerID` = ?";
        $stmt = $stmt = mysqli_prepare($con, $query);
        $stmt->bind_param('ssss', $choiceID, $answerTxt, $questionID, $answerID);
        $result = $stmt->execute();
    } else {

        // create new answer
        $answerDataID = substr(md5(uniqid()), 0, 29);
        $query = "INSERT INTO `answer_data`(`answerDataID`, `answerID`, `questionID`,`choiceID`, `answer_txt`) 
        VALUES (? ,? ,? ,? ,?)";
        $stmt = mysqli_prepare($con, $query);
        $stmt->bind_param('sssss', $answerDataID, $answerID, $questionID, $choiceID, $answerTxt);
        $result = $stmt->execute();
    }

    if ($result) echo 'Success';
    else echo 'Unsuccess';
}

function getAnswer($questionID, $answerID, $con)
{
    $query = "SELECT `choiceID`,`answer_txt` FROM `answer_data` WHERE `questionID`= ? AND`answerID` = ?";
    $stmt = mysqli_prepare($con, $query);
    $stmt->bind_param('ss', $questionID, $answerID);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = mysqli_num_rows($result);

    $response = "Unsuccess";
    $choiceID = array();
    $answer_txt = array();
    if ($result && $row > 0) {
        while ($data = $result->fetch_assoc()) {
            $response = "Success";
            $choiceID[] = $data['choiceID'];
            $answer_txt[] = $data['answer_txt'];
        }
    }

    $array = array(
        "response" => $response,
        "choiceID" => $choiceID,
        "answerTxt" => $answer_txt
    );

    echo json_encode($array);
}


function addCheckBoxAnswer($answerID, $questionID, $choiceID, $con)
{
    // create new answer
    $answerDataID = substr(md5(uniqid()), 0, 29);
    $query = "INSERT INTO `answer_data`(`answerDataID`, `answerID`, `questionID`,`choiceID`) 
     VALUES (? ,? ,? ,?)";
    $stmt = mysqli_prepare($con, $query);
    $stmt->bind_param('ssss', $answerDataID, $answerID, $questionID, $choiceID);
    $result = $stmt->execute();

    if ($result) echo 'Success';
    else echo 'Unsuccess';
}

function removeCheckedAnswer($questionID, $answerID, $choiceID, $con)
{
    $query = "DELETE FROM `answer_data` WHERE `questionID` = ? AND `answerID`= ? AND `choiceID` = ?";
    $stmt = mysqli_prepare($con, $query);
    $stmt->bind_param('sss', $questionID, $answerID, $choiceID);
    $result = $stmt->execute();

    if ($result) echo 'Deleted';
    else echo 'Unsuccess';
}
