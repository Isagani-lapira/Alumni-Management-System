
   
<?php


// class that fetches from student information

/**
 * * Table Structure for Event Table
 * 
 * eventID, eventName, eventDate, colCode, about_event, 
 * contactLink, aboutImg, headerPhrase, eventPlace,eventStartTime
 * 
 * date_posted, adminID,  
 * 
 * 
 * 
 */
class Event
{
    private $conn;

    public function __construct(mysqli $conn = null)
    {
        $this->conn = $conn;
    }


    function setNewEvent($eventInformation, $colCode, $adminID): bool
    {



        // Initialize the statement
        $stmt = $this->conn->stmt_init();

        $stmt->prepare('INSERT INTO `event`(`eventID`, `eventName`, `eventDate`, 
        `about_event`, `contactLink`,  `headerPhrase`, `eventPlace`, `eventStartTime`, 
        `colCode`, `adminID`,`aboutImg`,`date_posted`)
        VALUES (?,?,?,?,?,?,?,?,?,?,?,CURDATE() )');

        $eventId = uniqid('event-',);
        // Blobed image
        $aboutImg = addslashes(file_get_contents($eventInformation['aboutImg']));

        // bind the parameters
        $stmt->bind_param(
            'sssssssssss',
            $eventId,
            $eventInformation['eventName'],
            $eventInformation['eventDate'],
            $eventInformation['about_event'],
            $eventInformation['contactLink'],
            $eventInformation['headerPhrase'],
            $eventInformation['eventPlace'],
            $eventInformation['eventStartTime'],
            $colCode,
            $adminID,
            $aboutImg,
        );

        // setup to return json data
        try {
            //code...
            return $stmt->execute();
            // execute the query
        } catch (\Throwable $th) {
            //throw $th;
            echo  $th->getMessage();
        }

        // end
    }

    function getEvents($currentYear = ''): string
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
