<?php

class Alumni
{
    private $conn;

    public function __construct(mysqli $conn = null)
    {
        $this->conn = $conn;
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
