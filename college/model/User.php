
   
<?php


// class that fetches from student information
class User
{
    private $conn;

    public function __construct(mysqli $conn = null)
    {
        $this->conn = $conn;
    }



    function getUserCount(string $colCode): array
    {


        // Initialize the statement
        $stmt = $this->conn->stmt_init();

        $stmt = $this->conn->prepare('SELECT SUM(total_rows) AS total
        FROM (
            SELECT COUNT(*) AS total_rows FROM `student` WHERE colCode = ?
            UNION ALL
            SELECT COUNT(*) AS total_rows FROM `alumni`WHERE colCode = ?
        ) AS combined_results;');

        // *  Binds the variable to the '?', prevents sql injection
        $stmt->bind_param('ss', $colCode, $colCode);
        // execute the query
        $stmt->execute();
        // gets the myql_result. Similar result to mysqli_query
        $result = $stmt->get_result();
        $num_row = mysqli_num_rows($result);

        // the main assoc array to be return
        $json_result = array();

        // count 
        $count = '';

        if ($result && $num_row > 0) {
            $json_result['response'] = 'Successful';
            // Gets every row in the query
            while ($record = mysqli_fetch_assoc($result)) {
                $count = $record['total'];
            }
            $json_result['total'] = $count;
        } else {
            $json_result['response'] = 'Unsuccesful';
        }


        return $json_result;
    }
}
