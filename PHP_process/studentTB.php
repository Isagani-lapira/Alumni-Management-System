<?php

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

    function getStudenData($currentYear, $con)
    {
        $query = "";
        if ($currentYear != "")
            $query = 'SELECT * FROM `student` WHERE `currentYear` = "' . $currentYear . '"';
        else
            $query = 'SELECT * FROM `student`';

        $result = mysqli_query($con, $query);
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
