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

        $this->emailDetails($query, $con);
    }

    public function emailDetails($query, $con)
    {
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
    public function getEmail($name, $con)
    {
        $query = "SELECT CONCAT(p.`fname`, ' ', p.`lname`) AS fullname, p.`personal_email`
        FROM `person` p
        LEFT JOIN `univadmin` u ON p.`personID` = u.`personID`
        WHERE CONCAT(p.`fname`, ' ', p.`lname`) LIKE CONCAT('%', ?, '%')
        AND u.`personID` IS NULL";

        $stmt = mysqli_prepare($con, $query);
        // Bind the parameter
        mysqli_stmt_bind_param($stmt, "s", $name);
        mysqli_stmt_execute($stmt);

        // Get the result
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_num_rows($result);

        $response = "";
        $fullname = array();
        $email = array();

        if ($result && $row > 0) {
            $response = "success";
            while ($data = mysqli_fetch_assoc($result)) {
                $fullname[] = $data['fullname'];
                $email[] = $data['personal_email'];
            }
        } else {
            $response = "unsuccess";
            $suggestions[] = "No available email";
        }

        $data = array(
            "response" => $response,
            "fullname" => $fullname,
            "email" => $email,
        );

        echo json_encode($data);

        // Close the statement
        mysqli_stmt_close($stmt);
    }


    public function retrieveEmailsSent($personID, $offset, $con)
    {
        $maxLimit = 10;
        $query = "SELECT * FROM `email` WHERE `personID` = '$personID' ORDER by `dateSent` DESC
        LIMIT $offset, $maxLimit";

        $this->emailDetails($query, $con);
    }
}
