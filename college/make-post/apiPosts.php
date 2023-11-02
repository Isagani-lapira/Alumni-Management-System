<?php
session_start();

require "../php/connection.php";
require "../php/logging.php";
require_once "../php/checkLogin.php";
// Path: college/make-post/apiPosts.php

/**
 * Make a api response for the post table
 */

header("Content-Type: application/json");

// check if it is a get request
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET["all"])) {
        get_all_alumni();
    } else if (isset($_GET["postID"])) {
        apiGet($_GET["postID"]);
    } else if (isset($_GET["edit"])) {
        apiPostEdit($_GET["edit"]);
    } else if (isset($_GET["delete"])) {
        apiPostDelete($_GET["delete"]);
    } else {
        get_all_alumni();
    }
} else if ($_SERVER["REQUEST_METHOD"] == "POST") {


    // check if there is action 
    if (isset($_POST["action"])) {
        if ($_POST["action"] == "createPost") {


            $username = $_SESSION["username"];
            $colCode = $_SESSION["colCode"];
            $adminID = $_SESSION["adminID"];

            $caption = $_POST["description"];
            $img = (isset($_FILES['files'])) ? $_FILES['files'] : null;

            $date = date('y/m/d');
            $random = rand(0, 4000);
            $postID = uniqid() . '-' . $random;


            function insertImgPost($postID, $img, $mysql_con)
            {
                $random = rand(0, 100);
                $imageID = $postID . '-' . uniqid() . '-' . $random;

                //add image to the database
                $query = "INSERT INTO `post_images`(`imageID`, `postID`, `image`)
         VALUES ('$imageID','$postID','$img')";
                $result = mysqli_query($mysql_con, $query);

                if ($result) return true;
                else return false;
            }

            $imgFileLength = ($img != null) ? count($img['name']) : 0;
            $timestamp = date('Y-m-d H:i:s');
            $status = 'available'; // Default status

            $query = "INSERT INTO `post`(`postID`, `username`, `colCode`, `caption`, `date`, `timestamp`, `status`) 
              VALUES (?,?,?,?,?,?,?)";

            $stmt = mysqli_prepare($mysql_con, $query);

            if ($stmt) {
                // Bind data
                mysqli_stmt_bind_param($stmt, 'sssssss', $postID, $username, $colCode, $caption, $date, $timestamp, $status);
                $result = mysqli_stmt_execute($stmt);

                if ($result) {
                    // If the post has an image/s included
                    if ($imgFileLength) {
                        // Store all images one by one
                        for ($i = 0; $i < $imgFileLength; $i++) {
                            $fileContent = addslashes(file_get_contents($img['tmp_name'][$i]));
                            insertImgPost($postID, $fileContent, $mysql_con);
                        }
                    }

                    setNewActivity($mysql_con, $adminID, "posted", "Made a new Post");

                    // return true;
                    echo json_encode(array(
                        "error" => false, "data" => "",
                        "status" => true, "message" => "Post created successfully"
                    ));
                } else {
                    // return false;
                    echo json_encode(array("status" => false, "message" => "Something went wrong"));
                }
            } else {
                // return false;
                echo json_encode(array("status" => "error", "message" => "Something went wrong"));
            }
        } else if ($_POST["action"] == "archivePost") {
            // var_dump($_POST);
            // die();
            $postID = $_POST['postID'];

            try {
                // prepare the statement
                $stmt = $mysql_con->prepare("UPDATE post SET status = 'adminArchived' WHERE postID = ?");
                $stmt->bind_param("s", $postID);

                if ($stmt->execute()) {
                    $affectedRows = mysqli_affected_rows($mysql_con);

                    if ($affectedRows > 0) {
                        echo json_encode(array("status" => true, "message" => "Post archived successfully"));
                    } else {
                        echo json_encode(array("status" => false, "message" => "Post was not archived"));
                    }
                } else {
                    echo json_encode(array("status" => false, "message" => "Database error"));
                }
            } catch (\Throwable $th) {
                //throw $th;
                echo json_encode(array("status" => false, "message" => "Something went wrong", "error" => $th->getMessage()));
            }
        } else if ($_POST["action"] == "like") {
        } else if ($_POST["action"] == "comment") {
        }
    } else {
        // create a new post
        // create_post($_POST["message"]);
    }
}



function get_all_alumni()
{
    $posts = get_all();

    echo json_encode($posts);
}

function apiGet($id)
{
    $post = get_post($id);

    echo json_encode(
        array(
            'data' => $post,
            'status' => true,
            'message' => 'Post fetched successfully'
        )
    );
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
}

function get_post($id)
{
    try {

        require "../php/connection.php";
        // prepare the statement
        $stmt = $mysql_con->prepare("SELECT * FROM post WHERE postID = ?");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        $row['fullname'] = $_SESSION['fullName'];
        $row['profilePicture'] = $_SESSION['profilePicture'];

        // $query = "SELECT * FROM post WHERE postID = '$id'";
        // $result = mysqli_query($mysql_con, $query);
        // $row = mysqli_fetch_assoc($result);

        return $row;
    } catch (\Throwable $th) {
        throw $th;
        // 
    }
}

function get_all()
{

    require "../php/connection.php";
    // queries the db for all the posts
    // returns an array of posts
    $username = $_SESSION["username"];

    $posts = array();
    // $sql = "SELECT * FROM post WHERE username = '$username' ORDER BY timestamp DESC";
    // todo updated this to use status
    $sql = "SELECT p.*,
               COUNT(pl.likeID) AS like_count,
               COUNT(c.commentID) AS comment_count
        FROM post p
        LEFT JOIN postlike pl ON p.postID = pl.postID
        LEFT JOIN comment c ON p.postID = c.postID
        
        WHERE p.username = '$username' AND p.status = 'available'
        GROUP BY p.postID
        ORDER BY p.timestamp DESC";

    $result = mysqli_query($mysql_con, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $posts[] = $row;
    }
    return $posts;
}

// Path: college/make-post/apiPosts.php
