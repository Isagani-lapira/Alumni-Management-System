<?php

require_once 'notificationTable.php';
class Likes
{
    public function getLikes($postID, $con)
    {
        //query for getting fname and lname
        $query = "SELECT postlike.username,
        IFNULL(alumni.personID, student.personID) AS personID,
        IF(alumni.personID IS NOT NULL, 'alumni', 'student') AS user_type,
        person.fname,
        person.lname
        FROM post
        INNER JOIN postlike ON post.postID = postlike.postID
        LEFT JOIN alumni ON postlike.username = alumni.username
        LEFT JOIN student ON postlike.username = student.username
        LEFT JOIN person ON IFNULL(alumni.personID, student.personID) = person.personID
        WHERE post.postID = '$postID'";

        $response = "";
        $fullname = array();
        $result = mysqli_query($con, $query);
        $row = mysqli_num_rows($result);
        if ($result && $row > 0) {
            $response = "Success";
            while ($data = mysqli_fetch_assoc($result)) {
                $fullname[] = $data['fname'] . ' ' . $data['lname'];
            }
        } else $response = "Unsuccess";

        $data = array(
            'result' => $response,
            'fullname' => $fullname
        );
        echo json_encode($data);
    }

    public function addLikes($username, $postID, $con)
    {
        $random = rand(0, 1000);
        $postLike = $username .  substr(uniqid(), -4) . $random;

        $query = "INSERT INTO `postlike`(`likeID`, `username`, `postID`) 
        VALUES ('$postLike','$username','$postID')";
        $result =  mysqli_query($con, $query);

        if ($result) {
            //add to notification
            $typeOfNotif = 'like';
            $notifObj = new Notification();

            //get the username of the one who post
            $queryUN = "SELECT `username` FROM `post` WHERE `postID`= '$postID'";
            $postUNResult = mysqli_query($con, $queryUN);
            $data = mysqli_fetch_assoc($postUNResult);
            $postUsername = $data['username'];


            //perform insertion
            $insertNotif = $notifObj->insertNotif($postID, $postUsername, $typeOfNotif, $con);
            if ($insertNotif) echo 'Success';
            else 'Unsuccess';
        } else echo 'Unsuccess';
    }

    public function removeLike($username, $postID, $con)
    {
        $query = "DELETE FROM `postlike` WHERE `postID` ='$postID' AND `username`='$username'";
        $result =  mysqli_query($con, $query);

        if ($result) echo 'Success';
        else echo 'Unsuccess';
    }
}
