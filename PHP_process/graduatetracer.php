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
    }
}


function insertDataTracer($datajson, $con)
{
    $formTitle = $datajson['title'];
    $query = "INSERT INTO `tracer_form`(`formID`, `formName`, `year_created`) VALUES (?,?,?)"; //query for insertion
    $stmt = mysqli_prepare($con, $query);

    $formID = substr(md5(uniqid()), 0, 29);
    if ($stmt) {
        $year = date('Y-m-d');
        //bind data
        $stmt->bind_param('sss', $formID, $formTitle, $year);
        $result = $stmt->execute();

        if ($result) {
            //adding category to the table
            $categoryCollection = $datajson['category'];
            $dataArr = $datajson['data'];
            $checker = false;
            $count = 0;
            foreach ($categoryCollection as $category) {
                $categoryInsertion = insertCategory($count, $dataArr, $category, $formID, $con);
                $count++;
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

function insertCategory($count, $data, $categoryName, $formID, $con)
{
    $query = "INSERT INTO `question_category`(`categoryID`, `category_name`, 
    `formID`) VALUES (?, ?, ?)";

    $stmt = mysqli_prepare($con, $query);
    if ($stmt) {
        $categoryID = substr(md5(uniqid()), 0, 29);
        $stmt->bind_param("sss", $categoryID, $categoryName, $formID);
        $result = $stmt->execute();

        if ($result) {
            // //add the questions
            foreach ($data[$count] as $questionData) {
                $question = $questionData['Question'];
                $inputType = $questionData['InputType'];
                $choices = $questionData['choices'];
                // $choices = implode(', ', $questionData['choices']); // Convert choices array to a string if needed

                $result = insertQuestion($categoryID, $question, $inputType, $formID, $choices, $con);
                if (!$result) return false; // Uncomment this line if you want to handle question insertion failures
            }

            return true;
        } else return false;
    }
}

function insertQuestion($categoryID, $question, $inputType, $formID, $choices, $con)
{
    $query = "INSERT INTO `tracer_question`(`questionID`, `categoryID`, `formID`, 
    `question_text`, `inputType`) VALUES (?,?,?,?,?)";

    $stmt = mysqli_prepare($con, $query);

    if ($stmt) {
        $questionID = substr(md5(uniqid()), 0, 29);
        $stmt->bind_param('sssss', $questionID, $categoryID, $formID, $question, $inputType);
        $result = $stmt->execute();

        if ($result) {
            //insert choices
            foreach ($choices as $choice) {
                insertChoices($questionID, $choice, $con);
            }
            return true;
        } else return false;
    }
}

function insertChoices($questionID, $choiceText, $con)
{
    $query = "INSERT INTO `questionnaire_choice`(`choiceID`, `questionID`, `choice_text`) VALUES (?,?,?)";
    $stmt = mysqli_prepare($con, $query);

    if ($stmt) {
        $choiceID = substr(md5(uniqid()), 0, 29);
        $stmt->bind_param('sss', $choiceID, $questionID, $choiceText);
        $result = $stmt->execute();

        if ($result) return true;
        else return false;
    }
}
