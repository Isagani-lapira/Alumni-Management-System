<?php
require '../vendor/autoload.php';
require 'connection.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['file'])) {
        $uploadedFile = $_FILES['file']['tmp_name'];

        $spreadsheet = IOFactory::load($uploadedFile);

        // access the data from the spreadsheet
        $sheet = $spreadsheet->getActiveSheet();
        $highestRow = $sheet->getHighestRow();
        $result = true;
        //iterate all the content of the file
        for ($row = 2; $row <= $highestRow; $row++) { //row
            //get the data for every row in each column
            $studentNo = $sheet->getCellByColumnAndRow(1, $row)->getValue(); //studentNo column (1)
            $lastname = $sheet->getCellByColumnAndRow(2, $row)->getValue(); //lastname column (2)
            $firstname = $sheet->getCellByColumnAndRow(3, $row)->getValue(); //first column (2)
            $batchYear = $sheet->getCellByColumnAndRow(4, $row)->getValue(); //batch year column (2)
            $resultQuery = insertStudentNo($studentNo, $firstname, $lastname, $batchYear, $mysql_con); //add every value of each row in the database

            if (!$resultQuery) $result = false;
        }

        if ($result) $result = "Success";
        else $result = "Failed";

        // Return the highest row in a JSON response
        header('Content-Type: application/json');
        echo json_encode(['success' => true, "result" => $result]);
    } else if (isset($_POST['offset'])) {
        $offset = $_POST['offset'];
        header('Content-Type: application/json');
        getStudentRecord($offset, $mysql_con);
    } else  http_response_code(404);
} else {
    // Handle non-POST requests
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}


// adding data to the table student_record
function insertStudentNo($studentNo, $fname, $lname, $batchYear, $con)
{
    $STATUS = 'not activated'; //default
    $query = "INSERT INTO `student_record`(`studNo`, `fname`, 
    `lname`, `batchyear`,`status`) VALUES (? ,? ,? ,? ,? )";

    $stmt = mysqli_prepare($con, $query);

    if ($stmt) {
        $stmt->bind_param('sssss', $studentNo, $fname, $lname, $batchYear, $STATUS);
        $result = $stmt->execute();
        $stmt->close();

        if ($result) return true;
        else return false;
    }
}


function getStudentRecord($offset, $con)
{
    $MAXLIMIT = 10;
    $query = "SELECT `studNo`, `fname`, `lname`, `batchyear`, `status`, DATE(`timestamp`) as day_added FROM `student_record` LIMIT ?,?";
    $stmt = mysqli_prepare($con, $query);

    $response = "Failed";
    $studentNo = array();
    $fullname = array();
    $batchYear = array();
    $addedTime = array();
    $status = array();

    if ($stmt) {
        $stmt->bind_param('ii', $offset, $MAXLIMIT);
        $stmt->execute();
        $result = $stmt->get_result();
        $response = "Success";
        while ($data = $result->fetch_assoc()) {
            $studentNo[] = $data['studNo'];
            $fullname[] = $data['fname'] . ' ' . $data['lname'];
            $batchYear[] = $data['batchyear'];
            $status[] = $data['status'];
            $addedTime[] = $data['day_added'];
        }

        $stmt->close(); // Close the statement to free up resources
    }
    $data = array(
        "response" => $response,
        "studentNo" => $studentNo,
        "fullname" => $fullname,
        "batchYear" => $batchYear,
        "status" => $status,
        "addedTime" => $addedTime
    );

    echo json_encode($data);
}
