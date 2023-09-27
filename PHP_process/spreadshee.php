<?php
require '../vendor/autoload.php';

// use PhpOffice\PhpSpreadsheet\Spreadsheet;
// use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// $spreadsheet = new Spreadsheet();
// $activeWorksheet = $spreadsheet->getActiveSheet();
// $activeWorksheet->setCellValue('A1', 'Hello World !');

// $writer = new Xlsx($spreadsheet);

// // Set response headers to indicate a downloadable Excel file
// header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
// header('Content-Disposition: attachment; filename="tracer.xlsx"');
// // Output the Excel content to the browser
// echo $writer->save('php://output');

function getData()
{
    $query = "SELECT `answerID` FROM `answer` WHERE `tracer_deployID` = 'e6cb4908e273c7867a3e88ee55278' ";
    $stmt = mysqli_prepare($con, $query);
}


exit;
