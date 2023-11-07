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
    } else if ($action == "updateStatusToDone") {
        $answerID = $_POST['answerID'];
        updateStatusToDone($answerID, $mysql_con);
    } else if ($action == 'checkUserTracerStatus') {
    } else if ($action == 'completionStatus') {
        countAnswer($mysql_con);
    } else if ($action == 'countDonePerCollege') {
        countCollegeParticipation($mysql_con);
    } else if ($action == 'countDonePerCourse') {
        countCollegeCourseParticipation($mysql_con, $_POST['colCode']);
    }
}


function addAnswer($con)
{
    $personID = $_SESSION['personID'];
    $tracerID = retrievedDeployment($con); //latest tracer form

    // check first if there's already an answer
    $queryChecked = "SELECT `answerID` FROM `answer` WHERE `personID`= ? AND `tracer_deployID`= ?";
    $stmtChecked = mysqli_prepare($con, $queryChecked);
    $stmtChecked->bind_param('ss', $personID, $tracerID);
    $stmtChecked->execute();
    $result = $stmtChecked->get_result();
    $row = mysqli_num_rows($result);

    // if none then create entry
    if ($result && $row == 0) {
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
        $personStatTracer = checkUserTracerStatus($answerID, $con);

        if ($personStatTracer) echo 'finished';
        else echo $answerID;
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

function updateStatusToDone($answerID, $con)
{
    $personID = $_SESSION['personID'];
    $status = 'done';
    $query = "UPDATE `answer` SET `status`= ? WHERE `answerID` = ? AND `personID` = ?";
    $stmt = mysqli_prepare($con, $query);
    $stmt->bind_param('sss', $status, $answerID, $personID);
    $result = $stmt->execute();

    if ($result) echo 'Success';
    else echo 'Unsuccess';
}

function checkUserTracerStatus($answerID, $con)
{
    $query = "SELECT `status` FROM `answer` WHERE `answerID` = ?";
    $stmt = mysqli_prepare($con, $query);
    $stmt->bind_param('s', $answerID);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = mysqli_num_rows($result);

    $isDone = false;
    if ($result && $row > 0) {
        $answerID = $result->fetch_assoc()['status'];
        if ($answerID == 'done') return $isDone = true;
    }

    return $isDone;
}

function countAnswer($con)
{
    $tracerID = retrievedDeployment($con); //latest graduate tracer form deployment

    // get the count of who already finish answering the form
    $query = "SELECT COUNT(*) FROM `answer` WHERE `tracer_deployID` = ? AND status = 'done'";
    $stmt = mysqli_prepare($con, $query);
    $stmt->bind_param('s', $tracerID);
    $stmt->execute();
    $stmt->bind_result($completed);
    $stmt->fetch();
    $stmt->close();

    // Second, retrieve the total count from the alumni table
    $queryAlumni = "SELECT COUNT(*) FROM `alumni`";
    $stmtAlumni = mysqli_prepare($con, $queryAlumni);
    $stmtAlumni->execute();
    $stmtAlumni->bind_result($totalAlumniCount);
    $stmtAlumni->fetch();
    $stmtAlumni->close();

    $waiting = $totalAlumniCount - $completed;
    // Calculate the percentage completed and percentage waiting
    $percentageCompleted = ($completed / $totalAlumniCount) * 100;
    $percentageWaiting = ($waiting / $totalAlumniCount) * 100;

    $data = array(
        "completed" => $percentageCompleted,
        "waiting" => $percentageWaiting,
    );
    echo json_encode($data);
}
function countCollegeCourseParticipation($con, $colCode)
{
    $tracerID = retrievedDeployment($con); //latest graduate tracer form deployment
    $queryCollege = "SELECT `colCode` FROM `college`"; //get all the colleges in the system
    $stmt = mysqli_prepare($con, $queryCollege);
    $stmt->execute();
    $result = $stmt->get_result();
    $collegesCount = array();

    if ($result) {
        while ($data = $result->fetch_assoc()) {
            $colCode = $data['colCode']; //retrieve every college in the database

            // count the alumni of every colleges that finish answering latest tracer
            $queryCount = "SELECT COUNT(a.colCode)
            FROM alumni a
            JOIN (
                SELECT personID
                FROM answer
                WHERE tracer_deployID = ? AND status = 'done'
            ) b ON a.personID = b.personID
            WHERE a.colCode = ? ";

            $stmtCount = mysqli_prepare($con, $queryCount);
            $stmtCount->bind_param('ss', $tracerID, $colCode);
            $stmtCount->execute();
            $stmtCount->bind_result($count);
            $stmtCount->fetch();

            $participant = array(
                "colCode" => $colCode,
                "alumniCountFinished" => $count
            );

            $collegesCount[] = $participant;
            $stmtCount->close();
        }
        $result->close();
    }

    echo json_encode($collegesCount);
}


function countCollegeParticipation($con)
{
    $tracerID = retrievedDeployment($con); //latest graduate tracer form deployment
    $queryCollege = "SELECT `colCode` FROM `college`"; //get all the colleges in the system
    $stmt = mysqli_prepare($con, $queryCollege);
    $stmt->execute();
    $result = $stmt->get_result();
    $collegesCount = array();

    if ($result) {
        while ($data = $result->fetch_assoc()) {
            $colCode = $data['colCode']; //retrieve every college in the database

            // Count the total alumni count for the college
            $queryTotalAlumni = "SELECT COUNT(*) FROM alumni WHERE colCode = ?";
            $stmtTotalAlumni = mysqli_prepare($con, $queryTotalAlumni);
            $stmtTotalAlumni->bind_param('s', $colCode);
            $stmtTotalAlumni->execute();
            $stmtTotalAlumni->bind_result($totalAlumniCount);
            $stmtTotalAlumni->fetch();
            $stmtTotalAlumni->close();

            // count the alumni of every colleges that finish answering latest tracer
            $queryCount = "SELECT COUNT(a.colCode)
            FROM alumni a
            JOIN (
                SELECT personID
                FROM answer
                WHERE tracer_deployID = ? AND status = 'done'
            ) b ON a.personID = b.personID
            WHERE a.colCode = ? ";

            $stmtCount = mysqli_prepare($con, $queryCount);
            $stmtCount->bind_param('ss', $tracerID, $colCode);
            $stmtCount->execute();
            $stmtCount->bind_result($count);
            $stmtCount->fetch();

            // Calculate the percentage of alumni count finished for the college
            $percentageFinished = ($count / $totalAlumniCount) * 100;

            $participant = array(
                "colCode" => $colCode,
                "alumniCountFinished" => $percentageFinished
            );

            $collegesCount[] = $participant;
            $stmtCount->close();
        }
        $result->close();
    }

    echo json_encode($collegesCount);
}

// retrieving all selected choices in particular checkbox question
function getCheckBoxSelectAnswer($answerID, $questionID, $con)
{
    $query = "SELECT qc.`choice_text`
    FROM `answer_data` AS ad
    INNER JOIN `questionnaire_choice` AS qc ON ad.`choiceID` = qc.`choiceID`
    WHERE ad.`questionID` = ? AND ad.`answerID` = ? ";

    $stmt = mysqli_prepare($con, $query);

    $selectedChoice = array();

    if ($stmt) {
        $stmt->bind_param('ss', $questionID, $answerID);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($data = $result->fetch_assoc()) {
            $selectedChoice[] = $data['choice_text'];
        }
        $stmt->close();
    }

    return $selectedChoice;
}
