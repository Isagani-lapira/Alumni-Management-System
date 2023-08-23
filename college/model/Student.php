
   
<?php


// class that fetches from student information
class Student
{
    private $conn;

    public function __construct(mysqli $conn = null)
    {
        $this->conn = $conn;
    }


    function setStudent($studNo, $colCode, $personID, $username, $currentYear): bool
    {
        $query = "INSERT INTO `student`(`studNo`, `colCode`, `personID`, `username`, `currentYear`)
         VALUES ('$studNo','$colCode','$personID','$username','$currentYear')";

        $result = mysqli_query($this->conn, $query);

        if ($result) return true;
        else return false;
    }

    function getStudentsByYear($currentYear = ''): string
    {

        header("Content-Type: application/json; charset=UTF-8");

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
