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

    function deleteStudentRecord($username, $con)
    {
        $query = "DELETE FROM `student` WHERE `username`= ?";
        $stmt = mysqli_prepare($con, $query);

        if ($stmt) {
            $stmt->bind_param('s', $username);
            $result = $stmt->execute();

            if ($result) echo 'Success';
            else echo 'Failed';
        }
    }

    function updateCurrentYear($username, $con)
    {
        // check if the system already update the year
        $query = "SELECT COUNT(`last_update`) AS 'COUNT'  FROM `student`
        WHERE `username` = ? 
        AND YEAR(`last_update`) = YEAR(CURDATE())";

        $stmt = mysqli_prepare($con, $query);
        if ($stmt) {
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $stmt->bind_result($count);
            $stmt->fetch();
            $stmt->close();

            // get the last time the account has been updated
            $queryCurrentYear = "SELECT `currentYear` FROM `student` WHERE `username` = ?";
            $stmtCurrentYr = mysqli_prepare($con, $queryCurrentYear);
            $stmtCurrentYr->bind_param('s', $username);
            $stmtCurrentYr->execute();
            $stmtCurrentYr->bind_result($currentYear);
            $stmtCurrentYr->fetch();
            $stmtCurrentYr->close();

            if ($count === 0) { //not currently update the year
                // update the currentYear
                $queryUpdateYr = "UPDATE `student` SET `currentYear`= ?,
                `last_update`= ? WHERE `username` = ?";
                $stmtUpdate = mysqli_prepare($con, $queryUpdateYr);

                $currentUpdate = date('Y'); //update last update to current year
                $currentYear += 1; //increase the year

                if ($stmtUpdate) {
                    $stmtUpdate->bind_param('dss', $currentYear, $currentUpdate, $username);
                    $stmtUpdate->execute();
                }
            }
        }
    }
}
