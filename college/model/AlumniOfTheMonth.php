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

        $stmt = $this->conn->prepare('INSERT INTO alumni_of_the_month (studentNo,personID, quote, cover_img ,colCode, date_assigned )
        VALUES (?,?,?,?,?,CURDATE());');

        // *  Binds the variable to the '?', prevents sql injection
        $stmt->bind_param('sssss',  $studentId, $details['personID'], $details['quote'], $details['cover-img'], $this->colCode);
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
