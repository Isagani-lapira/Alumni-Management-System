<?php
session_start();


require '../php/connection.php';
require '../model/AlumniOfTheMonth.php';
require '../model/AlumniModel.php';
require '../php/checkLogin.php';


if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $colCode = $_SESSION['colCode'];
    function get_all_alumni_of_the_month()
    {
        require "../php/connection.php";
        $colCode = $_SESSION['colCode'];
        $stmt = $mysql_con->prepare('SELECT alumni_of_the_month.*, 
        CONCAT(fName, " ", lName) AS fullname, person.*  FROM `alumni_of_the_month`
            INNER JOIN `alumni` on studNo = studentNo
            INNER JOIN `person` on person.personID = alumni.personID
              WHERE alumni_of_the_month.colCode = ?
              ORDER BY date_assigned DESC;');
        $stmt->bind_param('s', $colCode);

        try {
            // execute the query
            $stmt->execute();
            // gets the myql_result. Similar result to mysqli_query
            $result = $stmt->get_result();
            $num_row = mysqli_num_rows($result);
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
                $resultArray =  [];
            }

            return $resultArray;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    function get_latest_alumni_of_the_month()
    {
        require "../php/connection.php";
        $colCode = $_SESSION['colCode'];
        $stmt = $mysql_con->prepare('SELECT alumni_of_the_month.*, 
        CONCAT(fName, " ", lName) AS fullname, person.*  FROM `alumni_of_the_month`
            INNER JOIN `alumni` on studNo = studentNo
            INNER JOIN `person` on person.personID = alumni.personID
              WHERE alumni_of_the_month.colCode = ?
              ORDER BY date_assigned DESC
              LIMIT 1;');
        $stmt->bind_param('s', $colCode);

        try {
            // execute the query
            $stmt->execute();
            // gets the myql_result. Similar result to mysqli_query
            $result = $stmt->get_result();
            $num_row = mysqli_num_rows($result);
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
                $resultArray =  [];
            }

            return $resultArray;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    header("Content-Type: application/json; charset=UTF-8");



    if (isset($_GET['latest']) && $_GET['latest'] === 'month') {

        try {
            echo json_encode(['data' => get_latest_alumni_of_the_month(), 'response' => 'Successful']);
        } catch (\Throwable $th) {
            echo json_encode(['data' => [], 'response' => 'Unsuccessful']);
        }
    } else   if (isset($_GET['getAll']) && $_GET['getAll'] === 'true') {

        try {
            echo json_encode(['data' => get_all_alumni_of_the_month(), 'response' => 'Successful']);
        } catch (\Throwable $th) {
            echo json_encode(['data' => [], 'response' => 'Unsuccessful']);
        }
    } else {
        $results = null;
        $model = new AlumniOfTheMonth($mysql_con, $_SESSION['colCode']);

        if (isset($_GET['partial']) &&   $_GET['partial'] === 'true') {
            // get the offset from the url
            $offset = $_GET['offset'];
            //convert to int
            $offset = (int) $offset;

            $results = $model->getAllLatest($offset);
            echo json_encode(['data' => $results, 'response' => 'Successful']);
        } else if (isset($_GET['getPersonId']) && isset($_GET['personId'])) {
            $model = new AlumniModel($mysql_con, $_SESSION['colCode']);
            $results = $model->getFullAlumniDetailById($_GET['personId'], true);
            echo json_encode(['data' => [$results], 'response' => 'Successful']);
        } else {
            // Return the full detail of the alumni of the month
            $results = $model->getFullDetailById($_GET['studentNo']);
            // header("Content-Type: application/json; charset=UTF-8");
            echo json_encode(['data' => [$results], 'response' => 'Successful']);
        }
    }
} else {
    echo "You are not supposed to be here.";
    header("refresh:5; url=../index.php");
    exit();
}
