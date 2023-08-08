<?php

session_start();
require_once 'connection.php';
require 'PostTB.php';

if (isset($_POST['action'])) {
    $actionArray = $_POST['action'];
    $data = json_decode($actionArray, true);
    $action = $data['action'];
    $post = new PostData();

    $username = $_SESSION['username'];
    switch ($action) {
        case 'insert':
            insertData($mysql_con);
            break;
        case 'read':
            $startgDate = $_POST['startDate'];
            $endDate = $_POST['endDate'];
            $offset = $_POST['offset'];
            $post->getPostAdmin($username, $startgDate, $endDate, $offset, $mysql_con);
            break;
        case 'readColPost':
            $college = $_SESSION['colCode'];
            $date = $data['retrievalDate'];
            $maxLimit = $data['maxRetrieve'];
            $post->getCollegePost($username, $college, $date, $maxLimit, $mysql_con);
            break;
        case 'insertPrevPost':
            $date = $data['date'];
            $timestamp = $data['timestamp'];
            $post->insertToPrevPost($username, $date, $timestamp, $mysql_con);
            break;
        case 'readUserPost':
            $date = $data['retrievalDate'];
            $post->getUserPost($username, $date, $mysql_con);
            break;
        case 'readUserArchievedPost':
            $date = $data['retrievalDate'];
            $post->getUserArchieved($username, $date, $mysql_con);
            break;
        case 'readAdminArchievedPost':
            $date = $_POST['retrievalDate'];
            $post->getUserArchieved($username, $date, $mysql_con);
            break;
        case 'reportPost':
            $postID = $_POST['postID'];
            $reportCateg = $_POST['category'];
            $post->reportPost($postID, $username, $reportCateg, $mysql_con);
            break;
        case 'readAdminProfile':
            $date = $_POST['retrievalDate'];
            $post->getAdminProfile($username, $date, $mysql_con);
            break;
        case 'deletePost':
            $postID = $_POST['postID'];
            $post->deletePost($postID, $mysql_con);
            break;
        case 'readAllPost':
            $offset = $_POST['offset'];
            $post->getAllPost($offset, $mysql_con);
            break;
        case 'displayReportData':
            getReportPost($mysql_con);
            break;
    }
}

function insertData($con)
{
    //data that will be sending
    $caption = $_POST['caption'];
    $college = $_POST['college'];
    $uploadedFiles = (isset($_FILES['files'])) ? $_FILES['files'] : null;

    $random = rand(0, 4000);
    $postID = uniqid() . '-' . $random;
    $username = $_SESSION['username']; // to be changed
    $date = date('y/m/d');
    $post = new PostData();

    //process of insertion
    $insertion = $post->insertPostData(
        $postID,
        $username,
        $college,
        $caption,
        $date,
        $uploadedFiles,
        $con
    );

    if ($insertion) echo 'successful';
    else echo 'unsuccessful';
}

function getReportPost($con)
{
    //retrieve every category
    $nudity = 0;
    $violence = 0;
    $Terrorism = 0;
    $HateSpeech = 0;
    $FalseInformation = 0;
    $SOS = 0;
    $harassment = 0;


    //nudity
    $queryNudity = "SELECT * FROM report_post rp
    JOIN post p ON rp.postID = p.postID
    WHERE p.status = 'available' AND rp.report_category = 'Nudity'";
    $result = mysqli_query($con, $queryNudity);
    $nudity = mysqli_num_rows($result);

    //violence
    $queryViolence = "SELECT * FROM report_post rp
    JOIN post p ON rp.postID = p.postID
    WHERE p.status = 'available' AND rp.report_category = 'Violence'";
    $resultViolence = mysqli_query($con, $queryViolence);
    $violence = mysqli_num_rows($resultViolence);

    // terrorism
    $queryTerrorism = "SELECT * FROM report_post rp
    JOIN post p ON rp.postID = p.postID
    WHERE p.status = 'available' AND rp.report_category = 'Terrorism'";
    $resultTerrorism = mysqli_query($con, $queryTerrorism);
    $Terrorism = mysqli_num_rows($resultTerrorism);

    //Hate Speech
    $querySpeech = "SELECT * FROM report_post rp
    JOIN post p ON rp.postID = p.postID
    WHERE p.status = 'available' AND rp.report_category = 'Hate Speech'";
    $resultSpeech = mysqli_query($con, $querySpeech);
    $HateSpeech = mysqli_num_rows($resultSpeech);

    // False Information
    $queryFI = "SELECT * FROM report_post rp
    JOIN post p ON rp.postID = p.postID
    WHERE p.status = 'available' AND rp.report_category = 'False Information'";
    $resultFI = mysqli_query($con, $queryFI);
    $FalseInformation = mysqli_num_rows($resultFI);

    // Suicide or self-injury
    $querySOS = "SELECT * FROM report_post rp
    JOIN post p ON rp.postID = p.postID
    WHERE p.status = 'available' AND rp.report_category = 'Suicide or self-injury'";
    $resultSOS = mysqli_query($con, $querySOS);
    $SOS = mysqli_num_rows($resultSOS);


    //harassment
    $queryHarass = "SELECT * FROM report_post rp
    JOIN post p ON rp.postID = p.postID
    WHERE p.status = 'available' AND rp.report_category = 'Harassment'";
    $resultHarass = mysqli_query($con, $queryHarass);
    $harassment = mysqli_num_rows($resultHarass);

    $categoryCounts = array(
        'nudity' => $nudity,
        'violence' => $violence,
        'Terrorism' => $Terrorism,
        'HateSpeech' => $HateSpeech,
        'FalseInformation' => $FalseInformation,
        'SOS' => $SOS,
        'harassment' => $harassment
    );

    echo json_encode($categoryCounts);
}
