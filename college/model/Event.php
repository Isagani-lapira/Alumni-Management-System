
   
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


    function setNewEvent($eventInformation, $colCode, $adminID): bool|string
    {



        // Initialize the statement
        $stmt = $this->conn->stmt_init();

        $stmt->prepare('INSERT INTO `event`(`eventID`, `eventName`, `eventDate`, 
        `about_event`,    `eventPlace`, `eventStartTime`, `event_category`,
        `colCode`, `adminID`,`aboutImg`,`date_posted`)
        VALUES (?,?,?,?,?,?,?,?,?,?,CURDATE() )');

        $eventId = uniqid('event-',);
        // Blobed image. add_slashes() breaks the image when using prepared statement
        $aboutImg = file_get_contents($eventInformation['aboutImg']);

        // bind the parameters
        $stmt->bind_param(
            'ssssssssss',
            $eventId,
            $eventInformation['eventName'],
            $eventInformation['eventDate'],
            $eventInformation['about_event'],
            // $eventInformation['contactLink'],
            // $eventInformation['headerPhrase'],
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
            throw $th;
            // return false;
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

    function setEditEvent($eventInformation, $colCode, $adminID): bool|string
    {
        // Initialize the statement
        $stmt = $this->conn->stmt_init();
        if (!isset($eventInformation['aboutImg'])) {
            $stmt->prepare('UPDATE `event` SET  `eventName` = ?, `eventDate` = ?, 
            `about_event` = ?,  `eventPlace` = ?, `eventStartTime` = ?, `event_category` = ?,
            `colCode`  = ?, `adminID` = ?
            WHERE `eventID` = ? 
            ');

            // bind the parameters
            $stmt->bind_param(
                'sssssssss',
                $eventInformation['eventName'],
                $eventInformation['eventDate'],
                $eventInformation['about_event'],
                // $eventInformation['contactLink'],
                // $eventInformation['headerPhrase'],
                $eventInformation['eventPlace'],
                $eventInformation['eventStartTime'],
                $eventInformation['event_category'],
                $colCode,
                $adminID,
                $eventInformation['eventID'],
            );
        } else {
            $stmt->prepare('UPDATE `event` SET  `eventName` = ?, `eventDate` = ?, 
            `about_event` = ?,  `eventPlace` = ?, `eventStartTime` = ?, `event_category` = ?,
            `colCode`  = ?, `adminID` = ?, `aboutImg` = ? 
            WHERE `eventID` = ? 
            ');

            // Blobed image. add_slashes() breaks the image when using prepared statement
            $aboutImg = file_get_contents($eventInformation['aboutImg']);
            // bind the parameters
            $stmt->bind_param(
                'ssssssssss',
                $eventInformation['eventName'],
                $eventInformation['eventDate'],
                $eventInformation['about_event'],
                // $eventInformation['contactLink'],
                // $eventInformation['headerPhrase'],
                $eventInformation['eventPlace'],
                $eventInformation['eventStartTime'],
                $eventInformation['event_category'],
                $colCode,
                $adminID,
                $aboutImg,
                $eventInformation['eventID'],
            );
        }

        // setup to return json data
        try {
            //code...
            if ($stmt->execute()) {
                // check if update successful
                $stmt->close();
                return true;
            }
            // // check if the query is successful
            // if ($status === false) {
            //     trigger_error($stmt->error, E_USER_ERROR);
            // }

            // execute the query
        } catch (\Throwable $th) {
            //throw $th;
            echo  $th->getMessage();
        }

        // end
    }

    // Delete an event entry
    function deleteEventByID($eventInformation, $colCode): bool|string
    {
        // Initialize the statement

        $stmt = $this->conn->stmt_init();

        $stmt->prepare(
            ' DELETE FROM event WHERE eventID = ? AND colCode  = ? ; '
        );

        // bind the parameters
        $stmt->bind_param(
            'ss',
            $eventInformation['eventID'],
            $colCode,

        );

        try {
            $status = $stmt->execute();
            // check if the query is successful
            if ($status === false) {
                trigger_error($stmt->error, E_USER_ERROR);
            }
            return $stmt->affected_rows;
            // execute the query
        } catch (\Throwable $th) {
            //throw $th;
            return  $th->getMessage();
        }

        // end
    }


    function getNewPartialEventsByOffset(int $offset = 0, string $colCode, string|null $category): array
    {
        // fetch all events regardless of category  
        if ($category === null) {
            // Initialize the statement
            $stmt = $this->conn->prepare('SELECT * 
            FROM `event`
              WHERE `colCode` = ? 
              ORDER BY date_posted DESC
              LIMIT  5  OFFSET  ? 
              ');
            $stmt->bind_param('si', $colCode, $offset);
        } else {
            // Initialize the statement
            $stmt = $this->conn->prepare('SELECT *
            FROM `event`
              WHERE `colCode` = ? AND `event_category` = ?
              LIMIT  5  OFFSET  ? 
              ');
            $stmt->bind_param('ssi', $colCode, $category, $offset);
        }

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
