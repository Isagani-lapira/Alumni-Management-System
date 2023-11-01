<?php
require '../vendor/autoload.php';
require_once '../PHP_process/connection.php';
require 'answer.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$activeWorksheet = $spreadsheet->getActiveSheet();

// Function to retrieve data and set column names
function getData($con, $worksheet)
{
    $query = "SELECT a.answerID FROM answer a
    WHERE a.tracer_deployID = (
        SELECT td.tracer_deployID
        FROM tracer_deployment td
        ORDER BY td.timstamp
        LIMIT 1) AND a.status = 'done'";

    $stmt = mysqli_prepare($con, $query);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result) {
        // Get question texts using the getQuestions function
        $questionTexts = getQuestions($con);
        $column = 'A';

        $worksheet->setTitle('General Questions');
        // add every question in row 1
        foreach ($questionTexts as $questionText) {
            $worksheet->setCellValue($column . '1', $questionText);
            $worksheet->getStyle($column . '1')->getFont()->setBold(true); //make the font bold
            $worksheet->getColumnDimension($column)->setAutoSize(true); //adjust the cell depending on the text
            $column++;
        }

        $row = 2; //start from second row
        //add every answer on every question
        while ($data = $result->fetch_assoc()) {
            $answerID = $data['answerID'];

            // get all questions
            $queryQuestion = "SELECT tq.`questionID`, tq.`question_text`
            FROM `tracer_question` tq
            INNER JOIN `question_category` qc ON tq.`categoryID` = qc.`categoryID`
            WHERE qc.`status` = 'available' AND tq.`isSectionQuestion` = 0 AND
            tq.`status` = 'available'
            ORDER BY qc.`sequence` ASC, tq.`sequence` ASC";
            $stmtQuestion = mysqli_prepare($con, $queryQuestion);
            $stmtQuestion->execute();
            $questionResult = $stmtQuestion->get_result();

            if ($questionResult) {
                $column = 'A';
                while ($questionData = $questionResult->fetch_assoc()) {
                    $questionID = $questionData['questionID'];

                    // get the  answer for every question
                    $queryAnswer = "SELECT 
                                CASE
                                    WHEN qc.choice_text IS NOT NULL THEN qc.choice_text
                                    ELSE ad.answer_txt
                                END AS answer_val
                                FROM answer_data ad
                                LEFT JOIN questionnaire_choice qc ON ad.choiceID = qc.choiceID
                                WHERE ad.answerID = ?
                                AND ad.questionID = ?";

                    $stmtAnswer = mysqli_prepare($con, $queryAnswer);
                    $stmtAnswer->bind_param('ss', $answerID, $questionID);
                    $stmtAnswer->execute();
                    $resultAnswer = $stmtAnswer->get_result();
                    $rowQuery = mysqli_num_rows($resultAnswer);

                    if ($rowQuery > 0) {
                        $answer = $resultAnswer->fetch_assoc()['answer_val'];

                        if ($answer != null) {
                            $worksheet->setCellValue($column . $row, $answer);
                            $column++;
                        }
                    }
                }
                $row++; // Move to the next row for the next set of answers
            }
        }
    }
}

// function to retrieve all the question
function getQuestions($con)
{
    $questionTexts = array();
    // get all the question
    $queryQuestion = "SELECT tq.`questionID`, tq.`question_text`
    FROM `tracer_question` tq
    INNER JOIN `question_category` qc ON tq.`categoryID` = qc.`categoryID`
    WHERE qc.`status` = 'available' AND tq.`isSectionQuestion` = 0 AND tq.`status` = 'available'
    ORDER BY qc.`sequence` ASC, tq.`sequence` ASC";

    $stmtQuestion = mysqli_prepare($con, $queryQuestion);
    $stmtQuestion->execute();
    $questionResult = $stmtQuestion->get_result();

    if ($questionResult) {
        while ($questionData = $questionResult->fetch_assoc()) {
            $questionTexts[] = $questionData['question_text'];
        }
    }

    return $questionTexts;
}

function createSectionSheet($spreadsheet, $con)
{
    $categories = getSectionWithCategory($con); //retrieve all the section question

    foreach ($categories as $categoryName) {
        addDataWorkSheet($categoryName, $spreadsheet, $con);
    }
}

function addDataWorkSheet($categoryName, $spreadsheet, $con)
{
    // add worksheet
    $newWorkSheet = $spreadsheet->createSheet();
    $newWorkSheet->setTitle($categoryName);

    // fetch all the section question for that category
    $queryCatQuestion = "SELECT DISTINCT tq.`question_text`
    FROM `tracer_question` tq
    JOIN `question_category` cat ON tq.`categoryID` = cat.`categoryID`
    WHERE cat.`category_name` = ?
    AND cat.status= ? AND tq.status = ?
    AND tq.`isSectionQuestion` = ? ";

    $stmt = mysqli_prepare($con, $queryCatQuestion);
    if ($stmt) {
        $STATUS = 'available';
        $ISSECTIONQUESTION = 1;
        $stmt->bind_param('sssd', $categoryName, $STATUS, $STATUS, $ISSECTIONQUESTION);
        $stmt->execute();
        $result = $stmt->get_result();

        $ROWHEADER = 1; //header row
        $column = 'A';
        $NAMEQUESTION = "Name";
        $newWorkSheet->setCellValue($column . $ROWHEADER, $NAMEQUESTION);
        $newWorkSheet->getStyle($column . $ROWHEADER)->getFont()->setBold(true);
        $newWorkSheet->getColumnDimension($column)->setAutoSize(true);
        $column++; //To start on B

        $questions = array();
        // add the question as header in excel
        while ($data = $result->fetch_assoc()) {
            $question = $data['question_text'];
            $newWorkSheet->setCellValue($column . $ROWHEADER, $question); //header
            $newWorkSheet->getStyle($column . $ROWHEADER)->getFont()->setBold(true);
            $newWorkSheet->getColumnDimension($column)->setAutoSize(true);
            $column++;
            $questions[] = $question;
        }
        $stmt->close();

        // retrieve all the answer
        $queryAnswer = "SELECT a.answerID, a.personID, CONCAT(p.lname,' ',p.fname) as 'fullname'
        FROM answer a
        JOIN person p ON a.personID = p.personID
        WHERE a.tracer_deployID = ? AND a.status = ? AND a.status = 'done'";
        $stmtAnswer = mysqli_prepare($con, $queryAnswer);

        $STATUS = 'done';
        $tracer_deployID = 'e6cb4908e273c7867a3e88ee55278';
        if ($stmtAnswer) {
            $stmtAnswer->bind_param('ss', $tracer_deployID, $STATUS);
            $stmtAnswer->execute();
            $resultAnswer = $stmtAnswer->get_result();
            $row = mysqli_num_rows($resultAnswer);

            if ($resultAnswer && $row > 0) {
                $columnAnswer = 'A';
                $rowval = 2;
                $newWorkSheet->setCellValue($columnAnswer . $rowval, 'isagani');
                // echo $resultAnswer->fetch_assoc();
                while ($data = $resultAnswer->fetch_assoc()) {
                    $fullname = $data['fullname'];
                    $answerID = $data['answerID'];

                    $newWorkSheet->setCellValue($columnAnswer . $rowval, $fullname);
                    // get other answer per question
                    foreach ($questions as $question) {
                        $columnAnswer++;
                        getSectionAnswerPerQ($answerID, $question, $newWorkSheet, $rowval, $columnAnswer, $con);
                    }
                    $rowval++;
                    $columnAnswer = 'A';
                }
            }
        }
    }
}

function getSectionAnswerPerQ($answerID, $questionText, $spreadSheet, $rowval, $columnAnswer, $con)
{
    $query = "SELECT
            tq.`inputType`,
            tq.`questionID`,
            tq.`question_text`,
            CASE
                WHEN qc.`choice_text` IS NOT NULL THEN qc.`choice_text`
                ELSE ad.`answer_txt`
            END AS `selected_text`
            FROM `answer_data` AS ad
            INNER JOIN `tracer_question` AS tq ON ad.`questionID` = tq.`questionID`
            LEFT JOIN `questionnaire_choice` AS qc ON ad.`choiceID` = qc.`choiceID`
            WHERE ad.`answerID` = ?
            AND tq.`question_text` = ? ";

    $stmtAnswerPerQ = mysqli_prepare($con, $query);

    if ($stmtAnswerPerQ) {
        $stmtAnswerPerQ->bind_param('ss', $answerID, $questionText);
        $stmtAnswerPerQ->execute();
        $result = $stmtAnswerPerQ->get_result();
        $row = mysqli_num_rows($result);

        $data = 'N/A';
        if ($result && $row > 0) {
            $rowData = $result->fetch_assoc(); // Fetch the row data once

            $inputType = $rowData['inputType'];
            $questionID = $rowData['questionID'];

            if ($inputType === 'Checkbox') {
                $selectedCB = getCheckBoxSelectAnswer($answerID, $questionID, $con);
                $data = "";
                foreach ($selectedCB as $answer) {
                    $data .= '/' . $answer;
                }
            } else $data = $rowData['selected_text'];
        }

        $spreadSheet->setCellValue($columnAnswer . $rowval, $data);
    }
}

// retrieve all the category that have section choice
function getSectionWithCategory($con)
{
    $categoryName = array();
    $query = "SELECT DISTINCT qc.`category_name`
    FROM `question_category` qc
    JOIN `tracer_question` tq ON qc.`categoryID` = tq.`categoryID`
    WHERE tq.`isSectionQuestion` = 1
    ORDER BY qc.`sequence` ASC";

    $stmtQuestion = mysqli_prepare($con, $query);
    $stmtQuestion->execute();
    $categoryResult = $stmtQuestion->get_result();

    if ($categoryResult) {
        while ($categoryData = $categoryResult->fetch_assoc()) {
            $categoryName[] = $categoryData['category_name'];
        }
    }

    return $categoryName;
}

getData($mysql_con, $activeWorksheet);
createSectionSheet($spreadsheet, $mysql_con);
$writer = new Xlsx($spreadsheet); //object of spreadsheet

// Set response headers to indicate a downloadable Excel file
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="Graduate TracerForm.xlsx"');
// Output the Excel content to the browser
echo $writer->save('php://output');
