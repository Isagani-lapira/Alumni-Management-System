<?php

class Alumni
{
    function insertAlumni($studNo, $personID, $colCode, $username, $batchYr, $con)
    {
        $query =  "INSERT INTO `alumni`(`studNo`, `personID`, `colCode`, `username`, `batchYr`) 
        VALUES ('$studNo','$personID','$colCode','$username','$batchYr')";

        $result = mysqli_query($con, $query);

        if ($result) return true;
        else return false;
    }

    function getAlumniRecord($con)
    {
        $query = "SELECT * FROM `alumni`";
        $result = mysqli_query($con, $query);
        $row = mysqli_num_rows($result);

        $response = "";
        $studNo = array();
        $fullname = array();
        $colCode = array();
        $batchYr =  array();
        $employmentStatus =  array();

        if ($result && $row > 0) {
            $response = "Success";
            while ($data = mysqli_fetch_assoc($result)) {
                $studNo[] = $data['studNo'];
                $personID = $data['personID'];
                $colCode[] = $data['colCode'];
                $batchYr[] = $data['batchYr'];
                $employmentStatus[] = $data['employment_status'];

                //query the personID for person table
                $queryPerson = 'SELECT * FROM `person` WHERE `personID` = "' . $personID . '"';
                $resultPerson = mysqli_query($con, $queryPerson);

                //get the alumni full name
                if ($resultPerson) {
                    $data = mysqli_fetch_assoc($result);
                    $fullname[] = $data['fname'] . ' ' . $data['lname'];
                }
            }
        } else $response = "Unsuccess";

        $alumniData = array(
            "result" => $response,
            "studentNo" => $studNo,
            "colCode" => $colCode,
            "batchYr" => $batchYr,
            "employmentStat" => $employmentStatus,
            "fullname" => $fullname
        );

        echo json_encode($alumniData);
    }
}
