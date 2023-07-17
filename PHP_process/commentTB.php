<?php
class Comment
{
    public function getPostComments($postID, $con)
    {
        $query = 'SELECT * FROM `comment` WHERE `postID`= "' . $postID . '"';
        $result = mysqli_query($con, $query);
        $row = mysqli_num_rows($result);

        $response = "";
        $commentID = array();
        $fullname  = array();
        $comment = array();

        if ($result && $row > 0) {
            $response = "Success";
            while ($data = mysqli_fetch_assoc($result)) {
                $commentID[] = $data['commentID'];
                $comment[] = $data['comment'];
                $user = $data['username'];



                $queryPerson = "SELECT 'univadmin' AS user_type, p.fname, p.lname
                FROM univadmin ua
                JOIN person p ON p.personID = ua.personID
                WHERE ua.username = '$user'
                UNION
                SELECT 'alumni' AS user_type, p.fname, p.lname
                FROM alumni al
                JOIN person p ON p.personID = al.personID
                WHERE al.username = '$user'
                UNION
                SELECT 'student' AS user_type, p.fname, p.lname
                FROM student s
                JOIN person p ON p.personID = s.personID
                WHERE s.username = '$user';
                ";

                $resultPerson = mysqli_query($con, $queryPerson);
                if ($resultPerson) {
                    while ($personData = mysqli_fetch_assoc($resultPerson)) {
                        $fullname[] = $personData['fname'] . ' ' . $personData['lname'];
                    }
                }
            }
        } else $response = "Unsuccess";

        $data = array(
            "result" => $response,
            'commentID' => $commentID,
            'fullname' => $fullname,
            'comment' => $comment,
        );

        echo json_encode($data);
    }
}
