<?php
session_start();


require '../php/connection.php';
require '../model/AlumniOfTheMonth.php';
require '../model/AlumniModel.php';
// require '../php/checkLogin.php';



if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $colCode = $_SESSION['colCode'];


    function get_all_alumni_of_the_month()
    {
        require "../php/connection.php";
        $colCode = $_SESSION['colCode'];
        $stmt = $mysql_con->prepare('SELECT aom.AOMID, aom.studentNo
        , aom.quote, aom.date_assigned, aom.colCode , aom.last_updated_date,
          aom.status, alumni.*,
        CONCAT(fName, " ", lName) AS fullname, 
        person.personID  FROM `alumni_of_the_month` as aom
            INNER JOIN `alumni` on studNo = aom.studentNo
            INNER JOIN `person` on person.personID = alumni.personID
              WHERE aom.colCode = ?
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
                    // // ! README ALWAYS USE base64_encode() when sending image to client. 2 Hours wasted because of this. 
                    // // $record['profile_img'] = base64_encode($record['profile_img']);

                    // if ($record['profilepicture'] !== '') {
                    //     $record['profilepicture'] = base64_encode($record['profilepicture']);
                    // }
                    // $record['cover_img'] = base64_encode($record['cover_img']);
                    $resultArray[] = $record;
                }
            } else {
                $resultArray =  [];
            }


            return $resultArray;
        } catch (\Throwable $th) {
            throw $th;
            // return [];
        }
    }

    function get_this_month_alumni_of_the_month()
    {
        require "../php/connection.php";
        $colCode = $_SESSION['colCode'];
        $stmt = $mysql_con->prepare('SELECT aom.AOMID, aom.studentNo, aom.quote, aom.date_assigned, aom.colCode , aom.last_updated_date,
        aom.status, alumni.*, person.personID,
        CONCAT(fName, " ", lName) AS fullname,  alumni.*  FROM `alumni_of_the_month` as aom
            INNER JOIN `alumni` on studNo = aom.studentNo
            INNER JOIN `person` on person.personID = alumni.personID
              WHERE aom.colCode = ? AND DATE_FORMAT(aom.date_assigned, "%Y-%m") = DATE_FORMAT(CURDATE(), "%Y-%m")
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
                    // // ! README ALWAYS USE base64_encode() when sending image to client. 2 Hours wasted because of this. 


                    // if ($record['profilepicture'] !== '') {
                    //     $record['profilepicture'] = base64_encode($record['profilepicture']);
                    // }

                    // $record['cover_img'] = base64_encode($record['cover_img']);
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


    function get_testimonials($testimonialID)
    {

        require "../php/connection.php";
        $stmt = $mysql_con->prepare('SELECT * FROM `testimonials` WHERE AOMID = ?');
        $stmt->bind_param('s', $testimonialID);

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

                    if ($record['profile_img'] !== '') {
                        $record['profile_img'] = base64_encode($record['profile_img']);
                    }
                    $resultArray[] = $record;
                }
            } else {
                $resultArray =  [];
            }

            return $resultArray;
        } catch (\Throwable $th) {
            throw $th;
            // return [];
        }
    }

    function get_achievements($id)
    {
        require "../php/connection.php";
        $stmt = $mysql_con->prepare('SELECT * FROM `achievement` WHERE AOMID = ?');
        $stmt->bind_param('s', $id);

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

    function get_AOTM_by_Id($aom_id)
    {
        require "../php/connection.php";
        $stmt = $mysql_con->prepare('SELECT aom.AOMID, aom.studentNo, aom.quote, aom.date_assigned, aom.colCode , aom.last_updated_date,
        aom.status, alumni.*, person.personID,
        CONCAT(fName, " ", lName) AS fullname, alumni.*  FROM `alumni_of_the_month`as aom
            INNER JOIN `alumni` on studNo = aom.studentNo
            INNER JOIN `person` on person.personID = alumni.personID
              WHERE aom.colCode = ? AND aom.AOMID = ?
              ORDER BY date_assigned DESC
              LIMIT 1;');
        $stmt->bind_param('ss', $_SESSION['colCode'], $aom_id);

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
                    // // ! README ALWAYS USE base64_encode() when sending image to client. 2 Hours wasted because of this.
                    // if ($record['profilepicture'] !== '') {
                    //     $record['profilepicture'] = base64_encode($record['profilepicture']);
                    // }
                    // $record['cover_img'] = base64_encode($record['cover_img']);
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

    function handleGetTestimonial()
    {
        // check if id is set

    }

    // if there is an action
    if (isset($_GET['action'])) {

        $action = $_GET['action'];

        try {

            header("Content-Type: application/json; charset=UTF-8");

            switch ($action) {

                case 'getTestimonial':
                    $id = $_GET['aomID'];
                    if (!isset($id)) {
                        echo json_encode(['data' => [], 'response' => 'Failed', 'error' => 'No testimonial ID', 'status' => false]);
                        die();
                    }
                    echo json_encode(['data' => get_testimonials($_GET['aomID']), 'response' => 'Successful']);
                    break;
                case 'getAchievement':
                    echo json_encode(['data' => get_achievements($_GET['aomID']), 'response' => 'Successful']);

                    break;
                case 'getAOTMById':
                    echo json_encode(['data' => get_AOTM_by_Id($_GET['aomID']), 'response' => 'Successful']);
                    break;
                case 'getThisMonth':
                    $data = get_this_month_alumni_of_the_month();
                    // var_dump($data);
                    // die();
                    echo json_encode(['data' =>  $data, 'response' => 'Successful']);
                    break;
                case 'getAll':
                    echo json_encode(['data' => get_all_alumni_of_the_month(), 'response' => 'Successful']);

                    break;
                case 'getAllAOTM':
                    echo json_encode(['data' => get_all_alumni_of_the_month(), 'response' => 'Successful']);

                    break;
                case 'partial':
                    $model = new AlumniOfTheMonth($mysql_con, $_SESSION['colCode']);
                    // get the offset from the url
                    $offset = $_GET['offset'];
                    $results = $model->getAllLatest($offset);
                    echo json_encode(['data' => $results, 'response' => 'Successful']);
                    break;
                case 'searchPerson':
                    $model = new AlumniModel($mysql_con, $_SESSION['colCode']);
                    $results = $model->getFullAlumniDetailById($_GET['personId']);
                    echo json_encode(['data' => $results, 'response' => 'Successful']);
                    break;
                default:

                    break;
            }
        } catch (\Throwable $th) {
            echo json_encode(['data' => [], 'response' => 'Unsuccessful', 'error' => $th->getMessage(), 'status' => false]);
        }





        // if ($_GET['action'] === 'getTestimonial') {
        // } else if ($_GET['action'] === 'getAchievement') {
        //     try {
        //         echo json_encode(['data' => get_achievements($_GET['aomID']), 'response' => 'Successful']);
        //     } catch (\Throwable $th) {
        //         echo json_encode(['data' => [], 'response' => 'Unsuccessful', 'error' => $th->getMessage(), 'status' => false]);
        //     }
        // } else if ($_GET['action'] === 'getAOTMById') {
        //     try {
        //         echo json_encode(['data' => get_AOTM_by_Id($_GET['aomID']), 'response' => 'Successful']);
        //     } catch (\Throwable $th) {
        //         echo json_encode(['data' => [], 'response' => 'Unsuccessful', 'error' => $th->getMessage(), 'status' => false]);
        //     }
        // }
        // die();
    }


    // if (isset($_GET['latest']) && $_GET['latest'] === 'month') {
    //     header("Content-Type: application/json; charset=UTF-8");
    //     try {
    //         $data = get_all_alumni_of_the_month();
    //         // var_dump($data);
    //         // die();
    //         echo json_encode(['data' =>  $data, 'response' => 'Successful']);
    //     } catch (\Throwable $th) {
    //         echo json_encode(['data' => [], 'response' => 'Unsuccessful', 'error' => $th->getMessage(), 'status' => false]);
    //     }
    // }


    // if (isset($_GET['latest']) && $_GET['latest'] === 'month') {
    // } else   if (isset($_GET['getAll']) && $_GET['getAll'] === 'true') {
    //     $data = get_all_alumni_of_the_month();
    //     var_dump($data);
    //     die();
    //     try {
    //         echo json_encode(['data' => get_all_alumni_of_the_month(), 'response' => 'Successful']);
    //     } catch (\Throwable $th) {
    //         echo json_encode(['data' => [], 'response' => 'Unsuccessful', 'error' => $th->getMessage(), 'status' => false]);
    //     }
    // } else {
    //     $results = null;
    //     $model = new AlumniOfTheMonth($mysql_con, $_SESSION['colCode']);

    //     header("Content-Type: application/json; charset=UTF-8");



    //     if (isset($_GET['partial']) &&   $_GET['partial'] === 'true') {
    //         // get the offset from the url
    //         $offset = $_GET['offset'];
    //         //convert to int
    //         $offset = (int) $offset;

    //         try {
    //             //code...

    //             $results = $model->getAllLatest($offset);
    //             echo json_encode(['data' => $results, 'response' => 'Successful']);
    //         } catch (\Throwable $th) {
    //             //throw $th;
    //             echo json_encode(['data' => [], 'response' => 'Unsuccessful', 'error' => $th->getMessage(), 'status' => false]);
    //         }
    //     } else if (isset($_GET['getPersonId'])) {

    //         try {
    //             $model = new AlumniModel($mysql_con, $_SESSION['colCode']);

    //             $results = $model->getFullAlumniDetailById($_GET['personId']);


    //             echo json_encode(['data' => $results, 'response' => 'Successful']);
    //         } catch (\Throwable $th) {
    //             echo json_encode(['data' => [], 'response' => 'Unsuccessful', 'error' => $th->getMessage(), 'status' => false]);
    //         }
    //     } else  if (isset($_GET['studentNo'])) {
    //         try {
    //             // Return the full detail of the alumni of the month

    //             $results = $model->getFullDetailById($_GET['studentNo']);
    //             echo json_encode(['data' => [$results], 'response' => 'Successful']);
    //         } catch (\Throwable $th) {

    //             echo json_encode(['data' => [], 'response' => 'Unsuccessful', 'error' => $th->getMessage(), 'status' => false]);
    //         }
    //     } else {
    //         echo json_encode(['data' => [], 'response' => 'Unsuccessful',  'status' => false]);
    //     }
    // }


} else {
    echo "You are not supposed to be here.";
    header("refresh:5; url=../index.php");
    exit();
}
