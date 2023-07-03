<?php
class UniversityAdmin
{
    public function insertUnivAdmin($adminID, $personID, $username, $con)
    {
        $query = "INSERT INTO `univadmin`(`adminID`, `personID`, `username`) 
        VALUES ('$adminID','$personID','$username')";

        if (mysqli_query($con, $query)) return true;
        else return false;
    }
}
