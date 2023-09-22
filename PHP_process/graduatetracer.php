<?php

require_once 'connection.php';

if (isset($_POST['action'])) {
    $action = $_POST['action'];
    //perform necessary action
    if ($action == 'insertionForm') {
        //creating new graduate tracer form
        $data = $_POST['data'];
        $datajson = json_decode($data, true);
        insertDataTracer($datajson, $mysql_con);
    } else if ($action == 'retrievedCategory') {
        retrieveCategory($mysql_con);
    } else if ($action == 'updateCategory') {
        $catStatus = $_POST['categoryStatus'];
        $catID = $_POST['categoryID'];
        updateCategoryStatus($catStatus, $catID, $mysql_con);
    } else if ($action == 'updateCategoryName') {
        $categoryName = $_POST['categoryName'];
        $categoryID = $_POST['categoryID'];
        updateCategoryName($categoryID, $categoryName, $mysql_con);
    } else if ($action == 'updateTitleForm') {
        $formID = $_POST['formID'];
        $formTitle = $_POST['formName'];
        updateTitleForm($formID, $formTitle, $mysql_con);
    } else if ($action == 'insertNewCategory') {
        $categoryName = $_POST['categoryName'];
        $formID = $_POST['formID'];
        addNewCategory($categoryName, $formID, $mysql_con);
    } else if ($action == "readQuestions") {
        $categoryID = $_POST['categoryID'];
        retrievedQuestions($categoryID, $mysql_con);
    } else if ($action == 'removeChoice') {
        $choiceID = $_POST['choiceID'];
        removeChoice($choiceID, $mysql_con);
    } else if ($action == 'changeChoiceText') {
        $choiceText = $_POST['choiceText'];
        $choiceID = $_POST['choiceID'];
        changeChoicetext($choiceText, $choiceID, $mysql_con);
    } else if ($action == "removeQuestion") {
        $questionID = $_POST['questionID'];
        removeQuestion($questionID, $mysql_con);
    } else if ($action == 'changeTypeOfInput') {
        $inputType = $_POST['inputType'];
        $questionID = $_POST['questionID'];
        changeInputType($inputType, $questionID, $mysql_con);
    } else if ($action == 'addSectionData') {
        $sectionData = json_decode($_POST['sectionData'], true);
        insertSection($sectionData, $mysql_con);
    } else if ($action == 'getSectionData') {
        $choiceID = $_POST['choiceID'];
        retrieveSection($choiceID, $mysql_con);
    } else if ($action == "addChoicesSection") {
        $questionID = $_POST['questionID'];
        $choiceText = $_POST['choiceText'];
        $isSectionQuestion = $_POST['isSectionQuestion'];
        $querySequence = "SELECT `sequence` FROM `questionnaire_choice` WHERE `questionID`='$questionID'";
        $nextSequence = checkSequence($querySequence, $mysql_con) + 1;
        insertChoices($questionID, $choiceText, $isSectionQuestion, $nextSequence, $mysql_con);
    } else if ($action == "addNewQuestionForCategory") {
        $data = json_decode($_POST['data'], true);
        $categoryID = $data['CategoryID'];
        $questionID = substr(md5(uniqid()), 0, 29);
        $question = $data['Question'];
        $inputType = $data['InputType'];
        $formID = $data['FormID'];
        $choices = $data['choices'];

        $querySequence = "SELECT `sequence` FROM `tracer_question` WHERE `categoryID` = '$categoryID'";
        $nextSequence = checkSequence($querySequence, $mysql_con) + 1; //get the next sequence (if there one)
        $questionInsertion = insertQuestion($categoryID, $questionID, $question, $inputType, $formID, $choices, $nextSequence, $mysql_con);
        if ($questionInsertion) echo 'Success';
        else echo 'Unsuccess';
    } else if ($action == "addNewSectionQuestion") {
        $newQuestionObj = json_decode($_POST['newQuestion'], true);
        $categoryID = $newQuestionObj['CategoryID'];
        $question = $newQuestionObj['QuestionSet']['Question'];
        $inputType = $newQuestionObj['QuestionSet']['InputType'];
        $formID = $newQuestionObj['FormID'];
        $choices = $newQuestionObj['QuestionSet']['choice'];
        $choiceID = $newQuestionObj['ChoiceID'];

        $querySequence = "SELECT `sequence` FROM `section_question` WHERE `choicesSectionID`= '$choiceID'";
        $nextSequence = checkSequence($querySequence, $mysql_con) + 1; //get the next sequence (if there one)
        $result = insertSectionData($categoryID, $question, $inputType, $formID, $choices, $choiceID, $nextSequence, $mysql_con);

        if ($result) echo 'Success';
        else echo 'Unsuccess';
    } else if ($action == "retrieveAllTracerForm") {
        retrieveTracerData($mysql_con);
    } else if ($action == "havesectionQuestion") {
        $choiceID = $_POST['choiceID'];
        $result = haveSection($choiceID, $mysql_con);
        echo $result;
    }
}

function checkSequence($query, $con)
{
    $stmt = mysqli_prepare($con, $query);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = mysqli_num_rows($result);
    if ($result && $row) return $row;
    else return 0;
}
function insertDataTracer($datajson, $con)
{
    $formTitle = $datajson['title'];
    $query = "INSERT INTO `tracer_form`(`formID`, `formName`, `year_created`,`status`) VALUES (?,?,?,?)"; //query for insertion
    $stmt = mysqli_prepare($con, $query);

    $formID = substr(md5(uniqid()), 0, 29);
    if ($stmt) {
        $year = date('Y-m-d');
        $status = 'available';
        //bind data
        $stmt->bind_param('ssss', $formID, $formTitle, $year, $status);
        $result = $stmt->execute();

        if ($result) {
            //adding category to the table
            $categoryCollection = $datajson['category'];
            $dataArr = $datajson['data'];
            $checker = false;
            $count = 0;
            $sequence = 1;
            foreach ($categoryCollection as $category) {
                $categoryInsertion = insertCategory($count, $dataArr, $category, $formID, $sequence, $con);
                $count++;
                $sequence++;
                if ($categoryInsertion) $checker = true;
                else {
                    $checker = false;
                    break;
                }
            }

            //check if the insertion of category is successful
            if ($checker) echo 'Successful';
            else echo 'Unsuccessful';
        }
    }
}

function insertCategory($count, $data, $categoryName, $formID, $sequence, $con)
{
    $query = "INSERT INTO `question_category`(`categoryID`, `category_name`, 
    `formID`,`status`,`sequence`) VALUES (?, ?, ?, ?,?)";

    $stmt = mysqli_prepare($con, $query);
    if ($stmt) {
        $categoryID = substr(md5(uniqid()), 0, 29);
        $status = 'available';
        $stmt->bind_param("sssss", $categoryID, $categoryName, $formID, $status, $sequence);
        $result = $stmt->execute();

        if ($result) {
            // //add the questions
            $sequence = 0;
            foreach ($data[$count] as $questionData) {
                $question = $questionData['Question'];
                $inputType = $questionData['InputType'];
                $choices = $questionData['choices'];

                $questionID = substr(md5(uniqid()), 0, 29);
                $sequence++;
                $result = insertQuestion($categoryID, $questionID, $question, $inputType, $formID, $choices, $sequence, $con);
                if (!$result) return false;
            }

            return true;
        } else return false;
    }
}

function insertQuestion($categoryID, $questionID, $question, $inputType, $formID, $choices, $sequence, $con, $isSectionQuestion = 0)
{
    $query = "INSERT INTO `tracer_question`(`questionID`, `categoryID`, `formID`, 
    `question_text`, `inputType`,`status`,`isSectionQuestion`,`sequence`) VALUES (?,?,?,?,?,?,?,?)";

    $stmt = mysqli_prepare($con, $query);

    if ($stmt) {
        $status = 'available';
        $stmt->bind_param('ssssssdd', $questionID, $categoryID, $formID, $question, $inputType, $status, $isSectionQuestion, $sequence);
        $result = $stmt->execute();

        if ($result) {
            //insert choices
            $nextTerm = 0;
            foreach ($choices as $choice) {
                $nextTerm++;
                insertChoices($questionID, $choice, $isSectionQuestion, $nextTerm, $con);
            }
            return true;
        } else return false;
    }
}

function insertChoices($questionID, $choiceText, $isSectionQuestion, $nextTerm, $con)
{
    $query = "INSERT INTO `questionnaire_choice`(`choiceID`, `questionID`, `choice_text`,
    `status`,`sectionQuestion`,`sequence`) VALUES (?,?,?,?,?,?)";
    $stmt = mysqli_prepare($con, $query);

    if ($stmt) {
        $choiceID = substr(md5(uniqid()), 0, 29);
        $status = "available";
        $stmt->bind_param('ssssdd', $choiceID, $questionID, $choiceText, $status, $isSectionQuestion, $nextTerm);
        $result = $stmt->execute();

        if ($result) return true;
        else return false;
    }
}


function retrieveCategory($con)
{
    $queryForm = "SELECT `formID` FROM `tracer_form`";
    $stmtForm = mysqli_prepare($con, $queryForm);
    $stmtForm->execute();
    $form = $stmtForm->get_result();
    $tracerID = $form->fetch_assoc();
    $tracerID = $tracerID['formID'];

    $queryCategory = "SELECT `categoryID`,`category_name` FROM `question_category` WHERE 
    `formID` = ? AND `status` = 'available' ORDER by `sequence` ASC";
    $stmt = mysqli_prepare($con, $queryCategory);

    $result = "Unsuccess";
    $categoryID = array();
    $categoryName = array();
    $tracerTitle = "";
    if ($stmt) {

        $stmt->bind_param('s', $tracerID);
        $stmt->execute();
        $stmt->bind_result($catID, $catName);

        while ($stmt->fetch()) {
            $result = "Success";
            $categoryID[] = $catID;
            $categoryName[] = $catName;
        }

        $tracerTitle = getFormTitle($tracerID, $con);
        // Close the statement
        $stmt->close();
    }

    $data = array(
        "result" => $result,
        "tracerID" => $tracerID,
        "tracerTitle" => $tracerTitle,
        "categoryID" => $categoryID,
        "categoryName" => $categoryName
    );

    echo json_encode($data);
}

function getFormTitle($formID, $con)
{
    $query = "SELECT `formName` FROM `tracer_form` WHERE `formID` = '$formID'";
    $result = mysqli_query($con, $query);

    if ($result) {
        $tracerTitle = mysqli_fetch_assoc($result);
        return $tracerTitle['formName'];
    }
}
function updateCategoryStatus($catStatus, $categoryID, $con)
{
    $query = "UPDATE `question_category` SET `status`=? WHERE `categoryID` = ?";
    $stmt = mysqli_prepare($con, $query);

    if ($stmt) {
        $stmt->bind_param('ss', $catStatus, $categoryID); //bind data
        $result = $stmt->execute();

        if ($result) echo 'Success'; //succesfully update
        else echo 'Unsuccess';
    }
}

function updateCategoryName($categoryID, $categoryName, $con)
{
    $query = "UPDATE `question_category` SET `category_name`= ? WHERE `categoryID` = ?";
    $stmt =  mysqli_prepare($con, $query);

    if ($stmt) {
        $stmt->bind_param('ss', $categoryName, $categoryID);
        $result = $stmt->execute();

        if ($result) echo 'Success';
        else echo 'Unsuccess';
    }
}

function updateTitleForm($formID, $formTitle, $con)
{
    $query = "UPDATE `tracer_form` SET `formName`= ? WHERE `formID` = ?";
    $stmt = mysqli_prepare($con, $query);

    if ($stmt) {
        $stmt->bind_param('ss', $formTitle, $formID);
        $result = $stmt->execute();

        if ($result) echo 'Success';
        else echo 'Unsucess';
    }
}

function addNewCategory($categoryName, $formID, $con)
{
    $querySequence = "SELECT `sequence` FROM `question_category` WHERE `formID` = '$formID' ";
    $nextSequence = checkSequence($querySequence, $con);
    $query = "INSERT INTO `question_category`(`categoryID`, `category_name`, 
    `formID`,`status`,`sequence`) VALUES (?, ?, ?, ?,?)";

    $stmt = mysqli_prepare($con, $query);

    $response = "";
    $categID = "";
    if ($stmt) {
        $categoryID = substr(md5(uniqid()), 0, 29);
        $status = 'available';
        $stmt->bind_param("sssss", $categoryID, $categoryName, $formID, $status, $nextSequence);
        $result = $stmt->execute();

        if ($result) {
            $response =  'Success';
            $categID = $categoryID;
        } else $response =  'Unsuccess';
    }

    $data = array(
        "result" => $response,
        "categoryID" => $categID
    );

    echo json_encode($data);
}

function retrievedQuestions($categoryID, $con)
{
    //retrieve category first
    $queryCat = "SELECT `categoryID`,`category_name` FROM `question_category` 
    WHERE `categoryID` = ? AND `status` = 'available' ORDER by `sequence` ASC";
    $stmtCat = mysqli_prepare($con, $queryCat);
    $response = "Unsuccess";

    if ($stmtCat) {
        $stmtCat->bind_param('s', $categoryID);
        $stmtCat->execute();
        $result = $stmtCat->get_result();

        $data = array();
        while ($row = $result->fetch_assoc()) {
            $response = "Success";
            $categoryID = $row['categoryID'];
            $categoryName =  $row['category_name'];

            //retrieve questions
            $questionSet = "SELECT * FROM `tracer_question` WHERE `categoryID` = ?
             AND `status` = 'available' AND `isSectionQuestion`=0  ORDER by `sequence` ASC";

            $stmtQuestion = mysqli_prepare($con, $questionSet);
            $stmtQuestion->bind_param('s', $categoryID);

            $questions = questionData($stmtQuestion, $con);
            $categorySet = array(
                "categoryID" => $categoryID,
                "categoryName" => $categoryName,
                "questionSet" => $questions
            );
            $data[] = $categorySet;
        }

        $questionSet = array(
            "response" => $response,
            "dataSet" => $data,
        );

        echo json_encode($questionSet);
    }
}

function questionData($stmtQuestion, $con)
{

    $questions = array();
    $choices = array();
    if ($stmtQuestion) {
        $stmtQuestion->execute();
        $resultQuestion = $stmtQuestion->get_result();

        while ($rowQuestion = $resultQuestion->fetch_assoc()) {
            $questionID = $rowQuestion['questionID'];
            $categoryID = $rowQuestion['categoryID'];
            $status = $rowQuestion['status'];

            //retrieve choices
            $queryChoices = "SELECT `choiceID`,`questionID`,`choice_text`, `sectionQuestion` FROM `questionnaire_choice` 
                    WHERE `status` = 'available' AND `questionID` = ? ORDER by `sequence` ASC";

            $stmtChoices = mysqli_prepare($con, $queryChoices);

            if ($stmtChoices) {
                $stmtChoices->bind_param('s', $questionID);
                $stmtChoices->execute();
                $resultChoices = $stmtChoices->get_result();

                while ($rowChoices = $resultChoices->fetch_assoc()) {
                    $choiceID = $rowChoices['choiceID'];
                    $isSectionChoice = haveSection($choiceID, $con);
                    $choices[] = array(
                        "choiceID" => $rowChoices['choiceID'],
                        "questionID" => $rowChoices['questionID'],
                        "choice_text" => $rowChoices['choice_text'],
                        "sectionQuestion" => $rowChoices['sectionQuestion'],
                        "isSectionChoice" => $isSectionChoice
                    );
                }
            }
            $questions[] = array(
                "questionID" => $rowQuestion['questionID'],
                "categoryID" => $categoryID,
                "questionTxt" => $rowQuestion['question_text'],
                "inputType" => $rowQuestion['inputType'],
                "choices" => $choices,
                "status" => $status,
            );
            $choices = array();
        }
    }
    return $questions;
}
function haveSection($choiceID, $con)
{
    $query = "SELECT `choicesSectionID` FROM `section_question` WHERE `choicesSectionID` = ? ";
    $stmt = mysqli_prepare($con, $query);
    $stmt->bind_param('s', $choiceID);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = mysqli_num_rows($result);

    if ($row > 0) return true;

    return false;
}
function removeChoice($choiceID, $con)
{
    $query = "UPDATE `questionnaire_choice` SET `status`= 'archived' WHERE `choiceID`= ? ";
    $stmt = mysqli_prepare($con, $query);

    if ($stmt) {
        $stmt->bind_param('s', $choiceID);
        $result = $stmt->execute();

        if ($result) echo 'Success';
        else echo 'Unsuccess';
    }
}


function changeChoicetext($choiceText, $choiceID, $con)
{
    $query = "UPDATE `questionnaire_choice` SET `choice_text`= ? WHERE `choiceID` = ?";
    $stmt = mysqli_prepare($con, $query);

    if ($stmt) {
        $stmt->bind_param('ss', $choiceText, $choiceID);
        $result = $stmt->execute();

        if ($result) echo 'Success';
        else echo 'Unsuccess';
    }
}

function removeQuestion($questionID, $con)
{
    $query = "UPDATE `tracer_question` SET `status`='archived' WHERE `questionID` = ?";
    $stmt = mysqli_prepare($con, $query);

    if ($stmt) {
        $stmt->bind_param('s', $questionID);
        $result = $stmt->execute();

        if ($result) echo 'Success';
        else echo 'Unsuccess';
    }
}

function changeInputType($inputType, $questionID, $con)
{
    $query = "UPDATE `tracer_question` SET `inputType`= ? WHERE `questionID` = ?";
    $stmt = mysqli_prepare($con, $query);

    if ($stmt) {
        $stmt->bind_param('ss', $inputType, $questionID);
        $result = $stmt->execute();

        if ($result) echo 'Success';
        else echo 'Unsuccess';
    }
}

function insertSection($sectionData, $con)
{
    $isComplete = false;
    //parts out the data
    foreach ($sectionData as $data) {
        $categoryID = $data['CategoryID'];
        $choiceID = $data['ChoiceID'];
        $formID = $data['FormID'];
        $questionSet = $data['QuestionSet'];
        $nextSequence = 0;
        //add question to the database
        foreach ($questionSet as $set) {
            $question = $set['Question'];
            $inputType = $set['InputType'];
            $choices = $set['choice'];
            $nextSequence++;
            $isComplete = insertSectionData($categoryID, $question, $inputType, $formID, $choices, $choiceID, $nextSequence, $con);
        }
    }

    if ($isComplete) echo 'Success';
    else echo 'Unsuccess';
}

function insertSectionData($categoryID, $question, $inputType, $formID, $choices, $choiceID, $nextSequence, $con)
{
    $questionID = substr(md5(uniqid()), 0, 29);

    $questionPosition = 0;
    //get the next sequence (if there one)
    insertQuestion($categoryID, $questionID, $question, $inputType, $formID, $choices, $questionPosition, $con, 1); // perform insertion of question first 

    //insert a section info
    $query = "INSERT INTO `section_question`(`sectionID`, `choicesSectionID`, `questionID`,`sequence`) VALUES (?,?,?,?)";
    $stmt = mysqli_prepare($con, $query);

    $sectionID = substr(md5(uniqid()), 0, 29);
    if ($stmt) {
        $stmt->bind_param('sssd', $sectionID, $choiceID, $questionID, $nextSequence);
        $result = $stmt->execute();

        if ($result) return true;
    }
}

function retrieveSection($choiceID, $con)
{
    $query = "SELECT `questionID` FROM `section_question` WHERE `choicesSectionID`= ? ORDER by `sequence` ASC";
    $stmt = mysqli_prepare($con, $query);
    $stmt->bind_param('s', $choiceID);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = array();
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $questionID = $row['questionID'];

            $questionQuery = "SELECT * FROM `tracer_question` WHERE `questionID`= ? AND status = 'available'";
            $stmtQuestion = mysqli_prepare($con, $questionQuery);
            $stmtQuestion->bind_param('s', $questionID);
            $questions = questionData($stmtQuestion, $con);

            $data[] = $questions;
        }
    }
    echo json_encode($data);
}

function retrieveTracerData($con)
{
    $query = "SELECT * FROM `tracer_form` WHERE `status`='available'";
    $stmt = mysqli_prepare($con, $query);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = mysqli_num_rows($result);

    $response = "Unsuccess";
    $formID = array();
    $formName = array();
    $year_created = array();
    if ($result && $row > 0) {
        $response = 'Success';

        //get all the data in tracer_form table
        while ($data = $result->fetch_assoc()) {
            $formID[] = $data['formID'];
            $formName[] = $data['formName'];
            $year_created[] = $data['year_created'];
        }
    }

    //make the data into object for easy acces of properties
    $data = array(
        'response' => $response,
        'formID' => $formID,
        'formName' => $formName,
        'year_created' => $year_created,
    );

    echo json_encode($data);
}
