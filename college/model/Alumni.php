<?php

class Alumni
{
    private $conn;

    public function __construct(mysqli $conn = null)
    {
        $this->conn = $conn;
    }

    // TODO use later, as there is no property for date_created yet.
    public function getUserCountByDate($colCode, $date): int
    {

        // Initialize the statement
        $stmt = $this->conn->stmt_init();

        $stmt = $this->conn->prepare('SELECT COUNT(*) AS total FROM `alumni` 
        WHERE colCode = ? dateCreated = ? ;');

        // *  Binds the variable to the '?', prevents sql injection
        $stmt->bind_param('s', $colCode);
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

    public function getTotalCount($colCode): int
    {

        // Initialize the statement
        $stmt = $this->conn->stmt_init();

        $stmt = $this->conn->prepare('SELECT COUNT(*) AS total FROM `alumni` WHERE colCode = ?;');

        // *  Binds the variable to the '?', prevents sql injection
        $stmt->bind_param('s', $colCode);
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


    public function getFullAlumniDetailById(string $id, bool $isJSON)
    {

        //get the person ID of user
        $query = 'SELECT * FROM person where personID = ?';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('s', $id);
        $stmt->execute();

        $result = $stmt->get_result();
        $jsonResult = array();

        if (
            $result->num_rows
        ) {
            if ($isJSON) {
                return $result->fetch_assoc();
            }
            // ok
            $jsonResult['response'] = 'Successful';
            $jsonResult['result'] = $result->fetch_assoc();
            return json_encode($jsonResult);
        }

        return json_encode(array('response' => 'Unsuccessful', 'result' => []));
    }
}
