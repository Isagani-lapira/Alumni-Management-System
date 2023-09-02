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

            $checker = false;
            foreach ($categoryCollection as $category) {
                $categoryInsertion = insertCategory($category, $formID, $con);
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

function insertCategory($categoryName, $formID, $con)
{
    $query = "INSERT INTO `question_category`(`category_name`, `formID`) VALUES (?,?)";
    $stmt = mysqli_prepare($con, $query);

    if ($stmt) {
        $stmt->bind_param("ss", $categoryName, $formID);
        $result = $stmt->execute();

        if ($result) return true;
        else return false;
    }
}
