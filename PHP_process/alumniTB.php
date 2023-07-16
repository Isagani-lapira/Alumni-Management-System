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
}
