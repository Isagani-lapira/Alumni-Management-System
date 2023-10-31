<?php
require '../vendor/autoload.php';
require_once '../PHP_process/connection.php';

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

getData($mysql_con, $activeWorksheet);

$writer = new Xlsx($spreadsheet);

// Set response headers to indicate a downloadable Excel file
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="tracer.xlsx"');
// Output the Excel content to the browser
echo $writer->save('php://output');
