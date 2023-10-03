<?php

// Search based from the filter table and name

/**
 * 
 * "SELECT suggestion_text FROM suggestions WHERE suggestion_text LIKE '%$query%' LIMIT 10";
 */



class PersonModel
{
    private $conn;
    private $colCode;

    public function __construct(mysqli $conn = null, string $colCode)
    {
        $this->conn = $conn;
        $this->colCode = $colCode;
    }



    public function getTotalCount($colCode): int
    {

        // Initialize the statement
        $stmt = $this->conn->stmt_init();

        $stmt = $this->conn->prepare('SELECT COUNT(*) AS total FROM `person` WHERE colCode = ?;');

        // *  Binds the variable to the '?', prevents sql injection
        $stmt->bind_param('s', $colCode);
        // execute the query
        $stmt->execute();
        // gets the myql_result. Similar result to mysqli_query
        $result = $stmt->get_result();
        $num_row = mysqli_num_rows($result);

        // the main assoc array to be return
        $count = 0;

        // count 

        if ($result && $num_row > 0) {
            // Gets every row in the query
            while ($record = mysqli_fetch_assoc($result)) {
                $count = $record['total'];
            }
        }

        return $count;
    }


    public function getSearchResult(string $userType, string $search, int $limit = 5)
    {
        //get the person ID of user

        $query = 'SELECT CONCAT(fname, " ",lname) AS fullname, studNo,  personID, FROM `person` 
            INNER JOIN `?` on ?.personID = person.personID
            WHERE colCode = ? AND CONCAT(fname, " " , lname)  LIKE "%?%"
            LIMIT ?";';

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('ssssi', $userType, $userType, $search, $this->colCode,  $limit);
            $stmt->execute();
            $result = $stmt->get_result();
            if (
                $result->num_rows
            ) {

                return $result->fetch_assoc();
            }
            return [];
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getOneById(string $personId)
    {
        //get the person ID of user
        $query = 'SELECT * FROM `person` WHERE personId = ?';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('s', $personId);
        $stmt->execute();

        $result = $stmt->get_result();

        if (
            $result->num_rows
        ) {

            return $result->fetch_assoc();
        }

        return [];
    }

    function getStudentsByYear($currentYear = ''): string
    {


        // Initialize the statement
        $stmt = $this->conn->stmt_init();
        if ($currentYear != "") {

            $stmt = $this->conn->prepare('SELECT studNo,person.personID,contactNo, 
            
            CONCAT(fname," ", lname) AS fullName
               FROM `student`
                INNER JOIN  `person` ON student.personID = person.personID
                  WHERE `currentYear` = ?');
            // *  Binds the variable to the '?', prevents sql injection
            $stmt->bind_param('s', $currentYear);
        } else {
            $stmt = $this->conn->prepare('SELECT studNo,person.personID,contactNo, CONCAT(fname," ", lname) AS fullName
               FROM `student`
                INNER JOIN  `person` ON student.personID = person.personID');
        }

        // execute the query
        $stmt->execute();
        // gets the myql_result. Similar result to mysqli_query
        $result = $stmt->get_result();
        $num_row = mysqli_num_rows($result);

        // the main assoc array to be return
        $json_result = array();
        // holds every row in the query
        $resultArray = array();

        if ($result && $num_row > 0) {
            $json_result['response'] = 'Successful';
            // Gets every row in the query
            while ($record = mysqli_fetch_assoc($result)) {
                $resultArray[] = $record;
            }
            $json_result['result'] = $resultArray;
        } else {
            $json_result['response'] = 'Unsuccesful';
        }


        return json_encode($json_result);
    }
}
