<?php

require_once 'connection.php';

if (isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action == "readAnnouncement") {
        $currentDate = $_POST['currentDate'];
        getAnnouncement($currentDate, $mysql_con);
    }
}


function getAnnouncement($currentDate, $con)
{
    $offset = 0;
    $maxLimit = 4;

    $query = "SELECT * FROM `university_announcement` WHERE 
    `date_end`>='$currentDate' ORDER BY `date_posted`ASC LIMIT $offset, $maxLimit";
    $result = mysqli_query($con, $query);
    $row = mysqli_num_rows($result);

    $response = "";
    $announcementID = array();
    $title = array();
    $Descrip = array();
    $fullname = array();
    $date_posted = array();
    $headline_img = array();

    if ($result && $row > 0) {
        $response = "Success";
        while ($data = mysqli_fetch_assoc($result)) {
            //store data that will retrieve
            $announcementID[] = $data['announcementID'];
            $title[] = $data['title'];
            $Descrip[] = $data['Descrip'];
            $univAdminID = $data['univAdminID'];
            $date_posted[] = $data['date_posted'];
            $headline_img[] = base64_encode($data['headline_img']);

            //get admin fullname
            $adminQuery = "SELECT p.fname, p.lname
            FROM univadmin AS ua
            JOIN person AS p ON ua.personID = p.personID
            WHERE ua.adminID = '$univAdminID'";

            $resultAdmin = mysqli_query($con, $adminQuery);

            if ($resultAdmin) {
                $data = mysqli_fetch_assoc($resultAdmin);
                $fullname[] = $data['fname'] . ' ' . $data['lname'];
            }
        }
    } else $response = "Failed";

    $data = array(
        "result" => $response,
        "announcementID" => $announcementID,
        "title" => $title,
        "Descrip" => $Descrip,
        "fullname" => $fullname,
        "date_posted" => $date_posted,
        "headline_img" => $headline_img,
    );

    echo json_encode($data);
}
