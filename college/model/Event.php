
   
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
        `about_event`, `contactLink`,  `headerPhrase`, `eventPlace`, `eventStartTime`, `event_category`,
        `colCode`, `adminID`,`aboutImg`,`date_posted`)
        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,CURDATE() )');

        $eventId = uniqid('event-',);
        // Blobed image. add_slashes() breaks the image when using prepared statement
        $aboutImg = file_get_contents($eventInformation['aboutImg']);

        // bind the parameters
        $stmt->bind_param(
            'ssssssssssss',
            $eventId,
            $eventInformation['eventName'],
            $eventInformation['eventDate'],
            $eventInformation['about_event'],
            $eventInformation['contactLink'],
            $eventInformation['headerPhrase'],
            $eventInformation['eventPlace'],
            $eventInformation['eventStartTime'],
            $eventInformation['event_category'],
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

    function getTotalEvents(string $colCode)
    {
        // Initialize the statement
        $stmt = $this->conn->prepare('SELECT COUNT(*) AS count FROM `event` WHERE `colCode` = ?');
        $stmt->bind_param('s', $colCode);

        try {
            // execute the query
            $stmt->execute();
            // gets the myql_result. Similar result to mysqli_query
            $result = $stmt->get_result();
            $count = $result->fetch_assoc();
        } catch (\Throwable $th) {
            throw $th;
        }
        return $count['count'];
    }

    function getEventById(string $eventID,  string $colCode)
    {
        // Initialize the statement
        $stmt = $this->conn->prepare('SELECT * FROM  `event` WHERE `colCode` = ? AND `eventID` = ?');
        $stmt->bind_param('ss', $colCode, $eventID);

        try {
            // execute the query
            $stmt->execute();
            $result = $stmt->get_result();
            $result = $result->fetch_assoc();
            // ! README ALWAYS USE base64_encode() when sending image to client. 2 Hours wasted because of this. 
            $result['aboutImg'] = base64_encode($result['aboutImg']);
            return $result;
        } catch (\Throwable $th) {
            throw $th;
        }
    }


    function getNewPartialEventsByOffset(int $offset = 0, string $colCode): array
    {


        // Initialize the statement
        $stmt = $this->conn->prepare('SELECT eventID, eventName, eventDate, 
        date_posted, aboutImg, headerPhrase, eventStartTime,about_event
            FROM `event`
              WHERE `colCode` = ?
              LIMIT  5  OFFSET  ? 
              ');


        $stmt->bind_param('si', $colCode, $offset);


        try {
            // execute the query
            $stmt->execute();
            // gets the myql_result. Similar result to mysqli_query
            $result = $stmt->get_result();
            $num_row = mysqli_num_rows($result);
        } catch (\Throwable $th) {
            throw $th;
        }


        // the main assoc array to be return
        $json_result = array();
        // holds every row in the query
        $resultArray = array();

        if ($result && $num_row > 0) {
            $json_result['response'] = 'Successful';
            // Gets every row in the query
            while ($record = mysqli_fetch_assoc($result)) {
                // ! README ALWAYS USE base64_encode() when sending image to client. 2 Hours wasted because of this. 
                $record['aboutImg'] = base64_encode($record['aboutImg']);
                $resultArray[] = $record;
            }
            $json_result['result'] = $resultArray;
        } else {
            $json_result['response'] = 'Unsuccesful';
        }

        $json_result['offset'] = $offset;
        return $json_result;
    }
}
