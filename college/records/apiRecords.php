<?php
session_start();

require "../php/connection.php";
require_once "../php/checkLogin.php";
// Path: college/make-post/apiPosts.php

/**
 * Make a api response for the post table
 */


header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    if (isset($_GET['filter'])) {

        if ($_GET['filter'] === 'alumni') {

            echo json_encode(query_all_alumni_record());
        } else if ($_GET['filter'] === 'student') {
            echo json_encode(query_all_student_record());
        } else if ($_GET['filter'] === 'all') {
            echo json_encode(query_all_record());
        }
    } else {
        // echo json_encode(query_all_record());
    }
}

function query_all_record()
{
    require "../php/connection.php";
    $colCode = $_SESSION["colCode"];

    $data = array();
    $sql = "SELECT person.personID,studNo,contactNo, CONCAT(person.fname, ' ', person.lname) AS full_name
FROM alumni
JOIN person ON alumni.personID = person.personID
WHERE alumni.colCode = '$colCode'
UNION
SELECT person.personID,studNo,contactNo, CONCAT(person.fname, ' ', person.lname) AS full_name
FROM student
JOIN person ON student.personID = person.personID
WHERE student.colCode = '$colCode';";

    $result = mysqli_query($mysql_con, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    return $data;
}



function query_all_student_record()
{
    require "../php/connection.php";
    $colCode = $_SESSION["colCode"];

    $data = array();
    $sql = "SELECT student.*, CONCAT(person.fname, ' ', person.lname) AS full_name, contactNo
    FROM student
    JOIN person ON student.personID = person.personID
    WHERE student.colCode = '$colCode';";

    $result = mysqli_query($mysql_con, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    return $data;
}


function query_all_alumni_record()
{
    require "../php/connection.php";
    $colCode = $_SESSION["colCode"];

    $data = array();
    $sql = "SELECT alumni.*, CONCAT(person.fname, ' ', person.lname) AS full_name, contactNo
    FROM alumni
    JOIN person ON alumni.personID = person.personID
    WHERE alumni.colCode = '$colCode';";

    $result = mysqli_query($mysql_con, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    return $data;
}

// Path: college/make-post/apiPosts.php
