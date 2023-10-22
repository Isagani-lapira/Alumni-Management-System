<?php
session_start();

require "../php/connection.php";
require_once "../php/checkLogin.php";
// Path: college/make-post/apiPosts.php

/**
 * Make a api response for the post table
 */

header("Content-Type: application/json");


if (isset($_GET["all"])) {
    get_all_alumni();
} else if (isset($_GET["id"])) {
    apiGet($_GET["id"]);
} else if (isset($_GET["edit"])) {
    apiPostEdit($_GET["edit"]);
} else if (isset($_GET["delete"])) {
    apiPostDelete($_GET["delete"]);
} else {
    get_all_alumni();
}

function get_all_alumni()
{
    $posts = get_all();

    echo json_encode($posts);
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
    $username = $_SESSION["username"];

    $posts = array();
    // $sql = "SELECT * FROM post WHERE username = '$username' ORDER BY timestamp DESC";
    $sql = "SELECT p.*,
               COUNT(pl.likeID) AS like_count,
               COUNT(c.commentID) AS comment_count
        FROM post p
        LEFT JOIN postlike pl ON p.postID = pl.postID
        LEFT JOIN comment c ON p.postID = c.postID
        WHERE p.username = '$username'
        GROUP BY p.postID
        ORDER BY p.timestamp DESC";

    $result = mysqli_query($mysql_con, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $posts[] = $row;
    }
    return $posts;
}

// Path: college/make-post/apiPosts.php
