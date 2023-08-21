<?php

class Student
{
    private $MAX_LIMIT = 10;
    function insertStudent($studNo, $colCode, $personID, $username, $currentYear, $con)
    {
        $query = "INSERT INTO `student`(`studNo`, `colCode`, `personID`, `username`, `currentYear`)
         VALUES ('$studNo','$colCode','$personID','$username','$currentYear')";

        $result = mysqli_query($con, $query);

        if ($result) return true;
        else return false;
    }

    function getAllStudent($offset, $currentYear, $colCode, $search, $con)
    {
        $query = "";
        if ($currentYear != "" && $colCode == '') //current year is only selected
            $query = "SELECT * FROM `student` WHERE `currentYear` = '$currentYear'";
        else if ($colCode != "" && $currentYear == '')
            $query = "SELECT * FROM `student` WHERE `colCode` = '$colCode'"; //filter only a course
        else if ($colCode != "" && $currentYear != "")
            $query = "SELECT * FROM `student` WHERE `colCode` = '$colCode' AND `currentYear` = '$currentYear'"; //filter both course and currentYear
        else if ($currentYear == '' && $colCode == '' && $search != '') {
            $query = "SELECT *
            FROM student
            WHERE personID IN (
                SELECT s.personID
                FROM student s
                JOIN person p ON s.personID = p.personID
                WHERE CONCAT(p.`fname`, ' ', p.`lname`) LIKE '%$search%')";
        } else
            $query = "SELECT * FROM `student`"; //select all

        $query .= "LIMIT $offset,$this->MAX_LIMIT";
        $result = mysqli_query($con, $query);

        $this->retrievedDetails($result, $con);
    }

    function retrievedDetails($result, $con)
    {
        $row = mysqli_num_rows($result);

        $response = "";
        $studentNo = array();
        $fullname = array();
        $contactNo = array();

        if ($result && $row > 0) {
            $response = "Success";
            //get data 
            while ($data = mysqli_fetch_assoc($result)) {
                $studentNo[] = $data['studNo'];
                $personID  = $data['personID'];
                $queryPerson = 'SELECT * FROM `person` WHERE `personID` = "' . $personID . '"';

                $resultPerson = mysqli_query($con, $queryPerson);
                while ($personData = mysqli_fetch_assoc($resultPerson)) {
                    $fullname[] = $personData['fname'] . $personData['lname'];
                    $contactNo[] = $personData['contactNo'];
                }
            }
        } else $response = "Unsuccessful";

        $data = array(
            "response" => $response,
            "studentNo" => $studentNo,
            "fullname" => $fullname,
            "contactNo" => $contactNo
        );

        echo json_encode($data);
    }
}
