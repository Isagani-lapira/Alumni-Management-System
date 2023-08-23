<?php

require '../../PHP_process/connection.php';

// class that fetches from student information
class Student
{


    function insertStudent($studNo, $colCode, $personID, $username, $currentYear, $con)
    {
        $query = "INSERT INTO `student`(`studNo`, `colCode`, `personID`, `username`, `currentYear`)
         VALUES ('$studNo','$colCode','$personID','$username','$currentYear')";

        $result = mysqli_query($con, $query);

        if ($result) return true;
        else return false;
    }

    function getStudents($currentYear = '', mysqli|null $con = null)
    {

        header("Content-Type: application/json; charset=UTF-8");

        if (is_null($con)) {
            $con = $GLOBALS['mysql_con'];
        }

        // Initialize the statement
        $stmt = $con->stmt_init();
        if ($currentYear != "") {

            $stmt = $con->prepare('SELECT studNo,person.personID,contactNo, 
            CONCAT(fname," ", lname) AS fullName
               FROM `student`
                INNER JOIN  `person` ON student.personID = person.personID
                  WHERE `currentYear` = ?');
            // *  Binds the variable to the '?', prevents sql injection
            $stmt->bind_param('s', $currentYear);
        } else {
            $stmt = $con->prepare('SELECT studNo,person.personID,contactNo, CONCAT(fname," ", lname) AS fullName
               FROM `student`
                INNER JOIN  `person` ON student.personID = person.personID');
        }

        // execute the query
        $stmt->execute();
        // gets the myql_result. Similar result to mysqli_query
        $result = $stmt->get_result();
        $num_row = mysqli_num_rows($result);

        // the main assoc array to be return
        $json_result = array();
        // holds every row in the query
        $resultArray = array();

        if ($result && $num_row > 0) {
            $json_result['response'] = 'Successful';
            // Gets every row in the query
            while ($record = mysqli_fetch_assoc($result)) {
                $resultArray[] = $record;
            }
            $json_result['result'] = $resultArray;
        } else {
            $json_result['response'] = 'Unsuccesful';
        }

        echo json_encode($json_result);

        $stmt->close();
        $con->close();
    }
}
