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
        $formID = $_POST['formID'];
        retrieveCategory($formID, $mysql_con);
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
        $formID = $_POST['formID'];
        retrievedQuestions($formID, $mysql_con);
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
    }
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
            foreach ($data[$count] as $questionData) {
                $question = $questionData['Question'];
                $inputType = $questionData['InputType'];
                $choices = $questionData['choices'];

                $questionID = substr(md5(uniqid()), 0, 29);
                $result = insertQuestion($categoryID, $questionID, $question, $inputType, $formID, $choices, $con);
                if (!$result) return false;
            }

            return true;
        } else return false;
    }
}

function insertQuestion($categoryID, $questionID, $question, $inputType, $formID, $choices, $con, $isSectionQuestion = 0)
{
    $query = "INSERT INTO `tracer_question`(`questionID`, `categoryID`, `formID`, 
    `question_text`, `inputType`,`status`) VALUES (?,?,?,?,?,?)";

    $stmt = mysqli_prepare($con, $query);

    if ($stmt) {
        $status = 'available';
        $stmt->bind_param('ssssss', $questionID, $categoryID, $formID, $question, $inputType, $status);
        $result = $stmt->execute();

        if ($result) {
            //insert choices
            foreach ($choices as $choice) {
                insertChoices($questionID, $choice, $isSectionQuestion, $con);
            }
            return true;
        } else return false;
    }
}

function insertChoices($questionID, $choiceText, $isSectionQuestion, $con)
{
    $query = "INSERT INTO `questionnaire_choice`(`choiceID`, `questionID`, `choice_text`,`status`,`sectionQuestion`) VALUES (?,?,?,?,?)";
    $stmt = mysqli_prepare($con, $query);

    if ($stmt) {
        $choiceID = substr(md5(uniqid()), 0, 29);
        $status = "available";
        $stmt->bind_param('ssssd', $choiceID, $questionID, $choiceText, $status, $isSectionQuestion);
        $result = $stmt->execute();

        if ($result) return true;
        else return false;
    }
}


function retrieveCategory($tracerID, $con)
{
    $queryCategory = "SELECT `categoryID`,`category_name` FROM `question_category` WHERE `formID` = ? AND `status` = 'available' ORDER by `sequence` ASC";
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
    $query = "INSERT INTO `question_category`(`categoryID`, `category_name`, 
    `formID`,`status`) VALUES (?, ?, ?, ?)";

    $stmt = mysqli_prepare($con, $query);

    $response = "";
    $categID = "";
    if ($stmt) {
        $categoryID = substr(md5(uniqid()), 0, 29);
        $status = 'available';
        $stmt->bind_param("ssss", $categoryID, $categoryName, $formID, $status);
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

function retrievedQuestions($formID, $con)
{
    //retrieve category first
    $queryCat = "SELECT `categoryID`,`category_name` FROM `question_category` 
    WHERE `formID` = ? AND `status` = 'available' ORDER by `sequence` ASC";
    $stmtCat = mysqli_prepare($con, $queryCat);
    $response = "Unsuccess";

    if ($stmtCat) {
        $stmtCat->bind_param('s', $formID);
        $stmtCat->execute();
        $result = $stmtCat->get_result();

        $data = array();
        while ($row = $result->fetch_assoc()) {
            $response = "Success";
            $categoryID = $row['categoryID'];
            $categoryName =  $row['category_name'];

            //retrieve questions
            $questionSet = "SELECT `questionID`, `question_text`,`inputType`
            FROM `tracer_question` WHERE `categoryID` = ? AND `formID`= ? AND `status` = 'available' ";

            $stmtQuestion = mysqli_prepare($con, $questionSet);

            $questions = array();
            $choices = array();
            if ($stmtQuestion) {
                $stmtQuestion->bind_param('ss', $categoryID, $formID);
                $stmtQuestion->execute();
                $resultQuestion = $stmtQuestion->get_result();

                while ($rowQuestion = $resultQuestion->fetch_assoc()) {
                    $questionID = $rowQuestion['questionID'];

                    //retrieve choices
                    $queryChoices = "SELECT `choiceID`,`questionID`,`choice_text` FROM `questionnaire_choice` 
                    WHERE `status` = 'available' AND `questionID` = ? AND `sectionQuestion`= 0  ";

                    $stmtChoices = mysqli_prepare($con, $queryChoices);

                    if ($stmtChoices) {
                        $stmtChoices->bind_param('s', $questionID);
                        $stmtChoices->execute();
                        $resultChoices = $stmtChoices->get_result();

                        while ($rowChoices = $resultChoices->fetch_assoc()) {
                            $choices[] = array(
                                "choiceID" => $rowChoices['choiceID'],
                                "questionID" => $rowChoices['questionID'],
                                "choice_text" => $rowChoices['choice_text']
                            );
                        }
                    }
                    $questions[] = array(
                        "questionID" => $rowQuestion['questionID'],
                        "questionTxt" => $rowQuestion['question_text'],
                        "inputType" => $rowQuestion['inputType'],
                        "choices" => $choices
                    );
                    $choices = array();
                }
            }
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

        //add question to the database
        foreach ($questionSet as $set) {
            $question = $set['Question'];
            $inputType = $set['InputType'];
            $choices = $set['choice'];

            $questionID = substr(md5(uniqid()), 0, 29);
            insertQuestion($categoryID, $questionID, $question, $inputType, $formID, $choices, $con, 1); // perform insertion of question first 

            //insert a section info
            $query = "INSERT INTO `section_question`(`sectionID`, `choicesSectionID`, `questionID`) VALUES (?,?,?)";
            $stmt = mysqli_prepare($con, $query);

            $sectionID = substr(md5(uniqid()), 0, 29);
            if ($stmt) {
                $stmt->bind_param('sss', $sectionID, $choiceID, $questionID);
                $result = $stmt->execute();

                if ($result) $isComplete = true;
            }
        }
    }

    if ($isComplete) echo 'Success';
    else echo 'Unsuccess';
}
