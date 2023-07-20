<?php

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
}
