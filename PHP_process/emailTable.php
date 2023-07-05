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

    public function queryDate($start, $end, $personID, $con)
    {
        //echo $start . ' ' . $end . ' ' . $personID;
        // //query for getting the email between one date to another date
        $query = 'SELECT * FROM `email` WHERE `dateSent`>= "' . $start . '" AND `dateSent`<= "' . $end . '"
                AND `personID` = "' . $personID . '"';

        $result = mysqli_query($con, $query);
        $row = mysqli_num_rows($result);

        // //store every email in these array
        $recipient = array();
        $colCode = array();
        $dateSent = array();

        $operationResult = '';
        // //get data
        if ($result && $row > 0) {
            $operationResult = 'Success';
            while ($data = mysqli_fetch_assoc($result)) {
                $recipient[] = $data['recipient'];
                $colCode[] = $data['colCode'];
                $dateSent[] = $data['dateSent'];
            }
        } else $operationResult = 'Unsuccess';

        // //data received on the client side
        $data = array(
            "result" => $operationResult,
            "recipient" => $recipient,
            "colCode" => $colCode,
            "dateSent" => $dateSent
        );

        echo json_encode($data); //return data as json
    }
}
