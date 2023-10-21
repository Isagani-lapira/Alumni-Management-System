<?php

require_once 'connection.php';
session_start();

if (isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action == "readAnnouncement") {
        $currentDate = $_POST['currentDate'];
        $maxLimit = $_POST['maxLimit'];
        getAnnouncement($currentDate, $maxLimit, $mysql_con);
    } else if ($action == "readImageOfAnnouncement") {
        $announcementID = $_POST['announcementID'];
        getAnnouncementImg($announcementID, $mysql_con);
    } else if ($action == "insertData") {
        $headerImg = $_FILES['imgHeader'];
        $title = $_POST['title'];
        $description = $_POST['description'];
        $univAdminID = $_SESSION['univAdminID'];
        $imgCollection = (isset($_FILES['file'])) ? $_FILES['file'] : null;
        insertNews($title, $description, $univAdminID, $headerImg, $imgCollection, $mysql_con);
    } else if ($action = 'readAdminPost') {
        $offset = $_POST['offset'];
        $univAdminID = $_SESSION['univAdminID'];
        getAdminAnnouncement($univAdminID, $offset, $mysql_con);
    }
}


function getAnnouncement($currentDate, $maxLimit, $con)
{
    $offset = 0;

    $query = "SELECT * FROM `university_announcement` WHERE 
    `date_end`>='$currentDate' ORDER BY `date_posted` DESC LIMIT $offset, $maxLimit";
    $result = mysqli_query($con, $query);

    getDetails($result, $con);
}

function getAdminAnnouncement($univAdminID, $offset, $con)
{
    $maxLimit = 10;
    $query = "SELECT * FROM `university_announcement` WHERE `univAdminID` = '$univAdminID'
    ORDER BY `date_posted` DESC LIMIT $offset,$maxLimit";
    $result = mysqli_query($con, $query);

    getDetails($result, $con);
}

function getDetails($result, $con)
{
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

function insertNews($title, $description, $univAdminID, $headerImg, $imgCollection, $con)
{
    $random = rand(0, 4000);
    $announcementID = $title . '-' . substr(md5(uniqid()), 10) . '-' . $random;
    $datePosted = date('Y-m-d');
    $date_end = date('Y-m-d', strtotime('+2 weeks'));

    // Header image
    $headerTempName = $headerImg['tmp_name'];
    $fileContent = file_get_contents($headerTempName);
    $query = "INSERT INTO `university_announcement`(`announcementID`, `title`, `Descrip`, `univAdminID`,
     `date_posted`, `headline_img`, `date_end`) VALUES (?,?,?,?,?,?,?)";

    $stmt = mysqli_prepare($con, $query);
    $stmt->bind_param('sssssss', $announcementID, $title, $description, $univAdminID, $datePosted, $fileContent, $date_end);
    $result = $stmt->execute();
    if ($imgCollection == null) {
        if ($result) echo 'Success';
        else echo 'Failed';
    } else {
        $isCompleted = false;
        //add image collection
        $imgLength = count($imgCollection['name']);
        for ($i = 0; $i < $imgLength; $i++) {
            $fileContent = addslashes(file_get_contents($imgCollection['tmp_name'][$i]));
            $isCompleted = addAdittionalImg($announcementID, $fileContent, $con);
        }

        if ($isCompleted) echo 'Success';
        else echo 'Failed';
    }
}

function addAdittionalImg($announcementID, $image, $con)
{
    $random = rand(0, 4000);
    $imgID = uniqid() . '-' . $random;
    $query = "INSERT INTO `univ_announcement_images`(`imgID`, `announcementID`, `image`) 
    VALUES ('$imgID','$announcementID','$image')";
    $result = mysqli_query($con, $query);

    if ($result) return true;
    else return false;
}
