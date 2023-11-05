<?php

class AlumniModel
{
    private $conn;
    private $colCode;

    public function __construct(mysqli $conn = null, string $colCode)
    {
        $this->conn = $conn;
        $this->colCode = $colCode;
    }

    // TODO use later, as there is no property for date_created yet.
    public function getUserCountByDate($date): int
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

    public function getTotalCount(): int
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


    public function getFullAlumniDetailById(string $id)
    {


        //get the person ID of user
        $query = 'SELECT person.fname, 
        person.lname,
        person.age,
        person.bday,
        person.gender,
        person.contactNo,
        person.address,
        person.personal_email,
        person.bulsu_email,
        person.facebookUN,
        person.instagramUN,
        person.twitterUN,
        person.linkedInUN,
        , studNo FROM person INNER JOIN `alumni` ON alumni.personID = person.personID where alumni.personID = ? ';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('s', $id);
        $stmt->execute();

        $result = $stmt->get_result();

        if (
            $result->num_rows
        ) {

            $row =  $result->fetch_assoc();
            // // ! README ALWAYS USE base64_encode() when sending image to client. 10 Hours wasted because of this.

            // // return $row;

            // $row['profilepicture'] = base64_encode($row['profilepicture']);

            return $row;
        }

        // return json_encode(array('response' => 'Unsuccessful', 'result' => []));
        return [];
    }


    public function getSearch(string $search, int $limit = 5)
    {
        //get the person ID of user
        $search = '%' . $search . '%';

        $query = 'SELECT CONCAT(fname, " ",lname) AS fullname, studNo,  person.personID FROM `person` 
            INNER JOIN `alumni` on alumni.personID = person.personID
            WHERE  CONCAT(fname, " " , lname)  LIKE ? AND colCode = ?
            LIMIT ?';

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('sss', $search, $this->colCode,  $limit);
            $stmt->execute();
            $result = $stmt->get_result();
            if (
                $result->num_rows
            ) {

                return $result->fetch_all(MYSQLI_ASSOC);
            }
            return [];
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
