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
}
