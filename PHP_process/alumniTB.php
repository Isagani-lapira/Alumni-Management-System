<?php

class Alumni
{
    function insertAlumni($studNo, $personID, $colCode, $username, $batchYr, $employmentStatus, $con)
    {
        $query =  "INSERT INTO `alumni`(`studNo`, `personID`, `colCode`, `username`, `batchYr`) 
        VALUES ('$studNo','$personID','$colCode','$username','$batchYr' '$employmentStatus')";

        $result = mysqli_query($con, $query);

        if ($result) return true;
        else return false;
    }

    function getAlumniRecord($offset, $con)
    {
        $maxLimit = 10;
        $query = "SELECT * FROM `alumni` ORDER BY `batchYr` DESC LIMIT $offset,$maxLimit";
        $result = mysqli_query($con, $query);
        $row = mysqli_num_rows($result);
        $this->alumniDetails($result, $row, $con);
    }

    function getAlumniCount($con)
    {
        $count = 0;
        $query = "SELECT COUNT(*) AS 'total' FROM `alumni`";
        $stmt = mysqli_prepare($con, $query);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result)
            $count = $result->fetch_assoc()['total'];

        return $count;
    }


    function alumniDetails($result, $row, $con)
    {
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
                while ($personData = mysqli_fetch_assoc($resultPerson)) {
                    $fullname[] = $personData['fname'] . $personData['lname'];
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

    function alumniByFilter($offset, $batchYr, $college, $status, $con)
    {
        $maxLimit = 10;
        $query = "";
        $stmt = null;

        // batch only filter
        if ($batchYr != "" && $college == "" && $status == "") {
            //batch only available
            $query = "SELECT * FROM `alumni` WHERE `batchYr` = ? LIMIT $offset,$maxLimit";
            $stmt = mysqli_prepare($con, $query);
            $stmt->bind_param('s', $batchYr);
        } else if ($batchYr != "" && $college != "" && $status == "") {
            // batch and college are available
            $query = "SELECT * FROM `alumni` WHERE `batchYr` = ? AND `colCode`= ? LIMIT $offset,$maxLimit";
            $stmt = mysqli_prepare($con, $query);
            $stmt->bind_param('ss', $batchYr, $college);
        } else if ($batchYr != "" && $college != "" && $status != "") {
            // all of them are available
            $query = "SELECT * FROM `alumni` WHERE `batchYr` = ? AND `colCode`= ? AND `employment_status`= ? LIMIT $offset,$maxLimit";
            $stmt = mysqli_prepare($con, $query);
            $stmt->bind_param('sss', $batchYr, $college, $status);
        } else if ($batchYr == "" && $college == "" && $status != "") {
            // status only available
            $query = "SELECT * FROM `alumni` WHERE `employment_status` = ? LIMIT $offset,$maxLimit";
            $stmt = mysqli_prepare($con, $query);
            $stmt->bind_param('s', $status);
        } else if ($batchYr != "" && $college == "" && $status != "") {
            // status and batch only available
            $query = "SELECT * FROM `alumni` WHERE `employment_status` = ? AND `batchYr` = ? LIMIT $offset,$maxLimit";
            $stmt = mysqli_prepare($con, $query);
            $stmt->bind_param('ss', $status, $batchYr);
        } else if ($batchYr == "" && $college != "" && $status == "") {
            // college only available
            $query = "SELECT * FROM `alumni` WHERE `colCode` = ? LIMIT $offset,$maxLimit";
            $stmt = mysqli_prepare($con, $query);
            $stmt->bind_param('s', $college);
        } else if ($batchYr == "" && $college != "" && $status != "") {
            // college and status only available
            $query = "SELECT * FROM `alumni` WHERE `colCode` = ? AND employment_status = ? LIMIT $offset,$maxLimit";
            $stmt = mysqli_prepare($con, $query);
            $stmt->bind_param('ss', $college, $status);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $row = mysqli_num_rows($result);
        $this->alumniDetails($result, $row, $con);
    }
}
