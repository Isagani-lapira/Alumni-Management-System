<?php

class AlumniOfTheMonth
{
    private mysqli $conn;
    private string $colCode;

    public function __construct(mysqli $conn = null, string $colCode)
    {
        $this->conn = $conn;
        $this->colCode = $colCode;
    }

    public function getUserCountByDate($date): int
    {

        // Initialize the statement
        $stmt = $this->conn->stmt_init();

        $stmt = $this->conn->prepare('SELECT COUNT(*) AS total FROM `alumni_of_the_month` 
        WHERE colCode = ? dateCreated = ? ;');

        // *  Binds the variable to the '?', prevents sql injection
        $stmt->bind_param('s', $this->colCode);
        // execute the query
        $stmt->execute();
        // gets the myql_result. Similar result to mysqli_query
        $result = $stmt->get_result();
        $num_row = mysqli_num_rows($result);

        // count 
        $count = '';

        if ($result && $num_row > 0) {
            // Gets every row in the query
            while ($record = mysqli_fetch_assoc($result)) {
                $count = $record['total'];
            }
        } else {
            return 0;
        }


        return $count;
    }

    public function setNewAlumniOfTheMonth(string $studentId, array $details): bool
    {
        // Initialize the statement
        $stmt = $this->conn->stmt_init();

        $stmt = $this->conn->prepare('INSERT INTO alumni_of_the_month (studentNo,personID, quote, cover_img ,colCode, date_assigned,description )
        VALUES (?,?,?,?,?,CURDATE(),?);');

        // *  Binds the variable to the '?', prevents sql injection
        $stmt->bind_param('ssssss',  $studentId, $details['personID'], $details['quote'], $details['cover-img'], $this->colCode, $details['description']);
        // execute the query
        $stmt->execute();

        return true;
    }
    public function deleteAlumniOfTheMonth(string $aotmID): bool
    {
        // Initialize the statement
        $stmt = $this->conn->stmt_init();

        $stmt = $this->conn->prepare('DELETE FROM alumni_of_the_month WHERE AOMID = ?;');

        // *  Binds the variable to the '?', prevents sql injection
        $stmt->bind_param('s', $aotmID);
        // execute the query

        // check if the query is successful
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function updateExistingAlumniOfTheMonth($aotmID, array $details): bool
    {
        // Initialize the statement
        $stmt = $this->conn->stmt_init();

        if ($details['cover-img'] !== '') {
            $stmt = $this->conn->prepare('UPDATE alumni_of_the_month SET studentNo = ? , personID = ? ,  quote = ?, cover_img = ? WHERE AOMID = ?;');

            $stmt->bind_param('sssss', $details['studentNo'], $details['personID'], $details['quote'], $details['cover-img'], $aotmID);
        } else {
            $stmt = $this->conn->prepare('UPDATE alumni_of_the_month SET studentNo = ? , personID = ? , quote = ? WHERE AOMID = ?;');

            $stmt->bind_param('ssss', $details['studentNo'], $details['personID'], $details['quote'], $aotmID);
        }

        // execute the query
        $stmt->execute();

        return true;
    }


    public function getTotalCount(): int
    {

        // Initialize the statement
        $stmt = $this->conn->stmt_init();



        $stmt = $this->conn->prepare('SELECT COUNT(*) AS total FROM `alumni_of_the_month` WHERE colCode = ?;');

        // *  Binds the variable to the '?', prevents sql injection
        $stmt->bind_param('s', $this->colCode);
        // execute the query
        $stmt->execute();
        // gets the myql_result. Similar result to mysqli_query
        $result = $stmt->get_result();
        $num_row = mysqli_num_rows($result);

        // count 
        $count = '';

        if ($result && $num_row > 0) {
            // Gets every row in the query
            while ($record = mysqli_fetch_assoc($result)) {
                $count = $record['total'];
            }
        } else {
            return 0;
        }


        return $count;
    }


    public function getFullDetailById(string $id): array
    {

        //get the person ID of user
        $query = 'SELECT * FROM `alumni_of_the_month` WHERE studentNo = ?';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('s', $id);
        $stmt->execute();

        $result = $stmt->get_result();

        if (
            $result->num_rows
        ) {

            return $result->fetch_assoc();
        }

        return [];
    }


    function getAllLatest(int $offset = 0): array
    {
        // TODO use a more sensible query
        $stmt = $this->conn->prepare('SELECT alumni_of_the_month.*, 
        CONCAT(fName, " ", lName) AS fullname  FROM `alumni_of_the_month`
            INNER JOIN `alumni` on studNo = studentNo
            INNER JOIN `person` on person.personID = alumni.personID
              WHERE alumni_of_the_month.colCode = ?
              ORDER BY date_assigned DESC
              LIMIT  10  OFFSET  ? ;');
        $stmt->bind_param('si',  $this->colCode, $offset);

        try {
            // execute the query
            $stmt->execute();
            // gets the myql_result. Similar result to mysqli_query
            $result = $stmt->get_result();
            $num_row = mysqli_num_rows($result);
        } catch (\Throwable $th) {
            throw $th;
        }



        // holds every row in the query
        $resultArray = array();

        if ($result && $num_row > 0) {
            // Gets every row in the query
            while ($record = mysqli_fetch_assoc($result)) {
                // ! README ALWAYS USE base64_encode() when sending image to client. 2 Hours wasted because of this. 
                // $record['profile_img'] = base64_encode($record['profile_img']);
                $record['cover_img'] = base64_encode($record['cover_img']);
                $resultArray[] = $record;
            }
        } else {
        }

        return $resultArray;
    }
}
