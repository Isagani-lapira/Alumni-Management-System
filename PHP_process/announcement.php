<?php

require_once 'connection.php';
session_start();

if (isset($_POST['action'])) {
    $action = $_POST['action'];
    $univAdminID = $_SESSION['univAdminID'];

    if ($action == "readAnnouncement") {
        $currentDate = $_POST['currentDate'];
        getAnnouncement($currentDate, $mysql_con);
    } else if ($action == "readImageOfAnnouncement") {
        $announcementID = $_POST['announcementID'];
        getAnnouncementImg($announcementID, $mysql_con);
    } else if ($action == "insertData") {
        $headerImg = $_FILES['imgHeader'];
        $title = $_POST['title'];
        $description = $_POST['description'];
        insertNews($title, $description, $univAdminID, $headerImg);
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

function getAnnouncementImg($id, $con)
{
    $query = "SELECT  `image` FROM `univ_announcement_images` WHERE `announcementID` = '$id'";
    $result = mysqli_query($con, $query);
    $row = mysqli_num_rows($result);

    $response = "";
    $img = array();
    if ($result && $row > 0) {
        $response = "Success";
        //get all the images
        while ($data = mysqli_fetch_assoc($result)) {
            $img[] = base64_encode($data['image']);
        }
    } else $response = 'Nothing';

    $data = array(
        "result" => $response,
        "images" => $img,
    );
    //send back the images
    echo json_encode($data);
}

function insertNews($title, $description, $univAdminID, $headerImg)
{
    $random = rand(0, 4000);
    $announcementID = $title . '-' . substr(md5(uniqid()), 10) . '-' . $random;
    $datePosted = date('Y-m-d');
    $date_end = date('Y-m-d', strtotime('+2 weeks'));

    $tempName = $headerImg['tmp_name'];
    $fileContent = addslashes(file_get_contents($tempName));
    $query = "INSERT INTO `university_announcement`(`announcementID`, `title`, `Descrip`, `univAdminID`,
     `date_posted`, `headline_img`, `date_end`) VALUES ('$announcementID','$title','$description',
     '$univAdminID','$datePosted','$fileContent','$date_end')";

    if ($query) echo 'Success';
    else echo 'Failed';
}
