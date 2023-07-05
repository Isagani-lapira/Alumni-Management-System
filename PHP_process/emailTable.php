<?php
require_once 'connection.php';
class EmailTable
{
    public function insertEmail($emailID, $recipient, $colCode, $date, $personID, $con)
    {
        //query for inserting data for email
        $query = "INSERT INTO `email`(`emailID`, `recipient`, `colCode`, `dateSent`, `personID`) 
        VALUES ('$emailID','$recipient','$colCode','$date','$personID')";
        $result = mysqli_query($con, $query);

        if ($result) echo 'Success';
        else echo 'Unsuccess';
    }
}
