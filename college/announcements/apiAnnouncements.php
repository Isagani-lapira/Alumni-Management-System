<?php
session_start();

require "../php/connection.php";
// require_once "../php/checkLogin.php";
require '../php/logging.php';

/**
 * Make a api response for the email table
 */

header("Content-Type: application/json");


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['action'])) {

        if ($_POST['action'] === 'addAnnouncement') {

            $title = $_POST['title'];
            $description = $_POST['description'];

            // cover image
            // check if there is a cover image
            $cover_img = '';
            // check if there's a file uploaded
            if (isset($_FILES['cover-image']) && $_FILES['cover-image']['size'] > 0) {
                $cover_img = file_get_contents($_FILES['cover-image']['tmp_name']);
            } else {
                $cover_img = '';
            }

            $random = rand(0, 4000);
            $announcementID = $title . '-' . substr(md5(uniqid()), 10) . '-' . $random;
            $datePosted = date('Y-m-d');
            $date_end = date('Y-m-d', strtotime('+2 weeks'));
            $adminID = $_SESSION['adminID'];

            $colCode = $_SESSION["colCode"];

            $imgCollection = (isset($_FILES['file'])) ? $_FILES['file'] : null;

            $query = "INSERT INTO `college_announcement`(`announcementID`, `title`, `Descrip`, `adminID`,
     `date_posted`, `headline_img`, `date_end`,`colCode`) VALUES (?,?,?,?,?,?,?,?)";

            $stmt = mysqli_prepare($mysql_con, $query);
            $stmt->bind_param('ssssssss', $announcementID, $title, $description, $adminID, $datePosted, $cover_img, $date_end, $colCode);
            $result = $stmt->execute();


            // if there is no image collection, then just return this 
            if ($imgCollection == null) {
                if ($result) {
                    echo json_encode(array(
                        "result" => "Success",
                        "images" => null,
                        "status" => true,
                        "message" => "Announcement added successfully",
                        "announcementID" => $announcementID,
                        "error" => null
                    ));
                } else {
                    echo json_encode(array(
                        "result" => "Success",
                        "images" => null,
                        "status" => true,
                        "message" => "Announcement added successfully",
                        "announcementID" => $announcementID,
                        "error" => null
                    ));
                }
                // * Else if there is an image
            } else {

                try {
                    $isCompleted = false;
                    //add image collection
                    $imgLength = count($imgCollection['name']);
                    for ($i = 0; $i < $imgLength; $i++) {
                        $image = addslashes(file_get_contents($imgCollection['tmp_name'][$i]));
                        $random = rand(0, 4000);
                        $imgID = uniqid() . '-' . $random;
                        $query = "INSERT INTO `college_announcement_images`(`imgID`, `announcementID`, `image`)  VALUES ('$imgID','$announcementID','$image')";
                        $result = mysqli_query($mysql_con, $query);
                        if ($result) {
                            setNewActivity(
                                $mysql_con,
                                $_SESSION['adminID'],
                                'posted',
                                'Posted an announcement'
                            );
                            echo json_encode(array(
                                "result" => "Success",
                                "images" => null,
                                "status" => true,
                                "message" => "Images added successfully",
                                "announcementID" => $announcementID,
                                "error" => null
                            ));
                        } else {
                            echo json_encode(array(
                                "result" => "Failed",
                                "images" => null,
                                "status" => false,
                                "message" => "Images not complete",
                                "announcementID" => $announcementID,
                                "error" => "Images not complete"
                            ));
                        }
                    }
                } catch (\Throwable $th) {

                    echo json_encode(array(
                        "result" => "Failed",
                        "images" => null,
                        "status" => false,
                        "message" => "Images not complete",
                        "announcementID" => $announcementID,
                        "error" => $th
                    ));
                }
            }
        }
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET["all"])) {
        get_all_records();
    } else if (isset($_GET["id"])) {

        // get one  data from the db and its images from the other table
        $announcementID = $_GET["id"];

        try {

            $sql = "SELECT * FROM `college_announcement` WHERE `announcementID` = '$announcementID'";
            $result = mysqli_query($mysql_con, $sql);
            $row = mysqli_fetch_assoc($result);

            $row['headline_img'] = base64_encode($row['headline_img']);

            // get the images
            $query = "SELECT  `image` FROM `univ_announcement_images` WHERE `announcementID` = '$announcementID'";
            $result = mysqli_query($mysql_con, $query);
            $numRows = mysqli_num_rows($result);

            $img = array();


            if ($numRows > 0) {
                //get all the images
                while ($data = mysqli_fetch_assoc($result)) {
                    $img[] = base64_encode($data['image']);
                }
            }
            $row['extra_images'] = $img;

            $response = array(
                "result" => "Success",
                "status" => true,
                "error" => null,
                "data" => $row,
                // "images" => $img
            );

            echo json_encode($response);
        } catch (\Throwable $th) {
            //throw $th;
            echo json_encode(array(
                "result" => "Failed",
                "status" => false,
                "error" => $th,
                "data" => null,
                "images" => null
            ));
        }
    }
} else if (isset($_GET["edit"])) {
    apiPostEdit($_GET["edit"]);
} else if (isset($_GET["delete"])) {
    apiPostDelete($_GET["delete"]);
} else {
    get_all_records();
}




function get_all_records()
{
    $records = get_all();


    echo json_encode(
        array(
            "result" => "Success",
            "status" => true,
            "message" => "Announcement added successfully",
            "error" => null,
            "data" => $records

        )
    );
}

function apiGet($id)
{
    $post = get_post($id);
    $response = array(
        "message" => $post["message"],
        "likes" => $post["likes"],
        "comments" => $post["comments"],
        "date_posted" => $post["date_posted"],
        "action" => "<button class='daisy-btn bg-accent font-light text-sm ml-auto text-white hover:bg-darkAccent px-3 py-3 rounded-lg'>EDIT</button>"
    );
    echo json_encode($response);
}

function apiPostEdit($id)
{
    $post = get_post($id);
    $response = array(
        "message" => $post["message"],
        "likes" => $post["likes"],
        "comments" => $post["comments"],
        "date_posted" => $post["date_posted"],
        "action" => "<button class='daisy-btn bg-accent font-light text-sm ml-auto text-white hover:bg-darkAccent px-3 py-3 rounded-lg'>EDIT</button>"
    );
    echo json_encode($response);
}
function apiPostDelete($id)
{
    $post = get_post($id);
    $response = array(
        "message" => $post["message"],
        "likes" => $post["likes"],
        "comments" => $post["comments"],
        "date_posted" => $post["date_posted"],
        "action" => "<button class='daisy-btn bg-accent font-light text-sm ml-auto text-white hover:bg-darkAccent px-3 py-3 rounded-lg'>EDIT</button>"
    );
    echo json_encode($response);
}

function get_post($id)
{
    return [
        "message" => "This is a sample post",
        "likes" => 0,
        "comments" => 0,
        "date_posted" => "2021-10-10"
    ];
}

function get_all()
{

    require "../php/connection.php";
    // queries the db for all the posts
    // returns an array of posts
    $colCode = $_SESSION["colCode"];

    $posts = array();
    $sql = "SELECT title, Descrip, date_posted, announcementID
    FROM college_announcement 
    WHERE colCode = '$colCode' ORDER BY date_posted DESC";

    $result = mysqli_query($mysql_con, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        if (isset($row['headline_img'])) {

            $row['headline_img'] = base64_encode($row['headline_img']);
        }
        $posts[] = $row;
    }
    return $posts;
}

// Path: college/make-post/apiPosts.php
