<?php

class PostData
{
    public function insertPostData($postID, $username, $colCode, $caption, $date, $img, $con)
    {
        $imgFileLength = count($img['name']);
        $timestamp = date('Y-m-d H:i:s');

        $query = "INSERT INTO `post`(`postID`, `username`, `colCode`, `caption`, `date`, `timestamp`) 
        VALUES ('$postID','$username','$colCode','$caption','$date','$timestamp')";
        $result = mysqli_query($con, $query);

        if ($result) {
            //if the post has an image/s included
            if ($imgFileLength) {
                //store all images one by one
                for ($i = 0; $i < $imgFileLength; $i++) {
                    $fileContent = addslashes(file_get_contents($img['tmp_name'][$i]));
                    $this->insertImgPost($postID, $fileContent, $con);
                }
            }
            return true;
        } else {
            return false;
        }
    }

    //insert images
    function insertImgPost($postID, $img, $con)
    {
        $random = rand(0, 100);
        $imageID = $postID . '-' . uniqid() . '-' . $random;

        //add image to the database
        $query = "INSERT INTO `post_images`(`imageID`, `postID`, `image`)
         VALUES ('$imageID','$postID','$img')";
        $result = mysqli_query($con, $query);

        if ($result) return true;
        else return false;
    }

    //get all the post by admin
    function getPostAdmin($username, $startingDate, $endDate, $con)
    {
        $query = "";
        //check if it the post retrieve based on date
        if ($startingDate != null && $endDate != null)
            $query = 'SELECT * FROM `post` WHERE `date` BETWEEN "' . $startingDate . '" AND "' . $endDate . '" AND `username` = "' . $username . '"';
        else
            $query = 'SELECT * FROM `post` WHERE `username`= "' . $username . '"ORDER BY `date` DESC';

        $result = mysqli_query($con, $query);
        $row = mysqli_num_rows($result);

        //data that will be retrieving
        $response = "";
        $postID = array();
        $colCode = array();
        $caption = array();
        $date = array();
        $images = array();
        $comments = array();
        $likes = array();

        if ($result && $row > 0) {
            $response = 'Success';
            while ($data = mysqli_fetch_assoc($result)) {
                $postID[] = $data['postID'];
                $colCode[] = $data['colCode'];
                $caption[] = $data['caption'];
                $date[] = $data['date'];

                $ID = $data['postID'];
                $images[] = $this->getPostImages($ID, $con);
                $comments[] = $this->getPostComments($ID, $con);
                $likes[] = $this->getPostLikes($ID, $con);
            }
        }

        $data = array(
            'response' => $response,
            'username' => $username,
            'postID' => $postID,
            'colCode' => $colCode,
            'caption' => $caption,
            'date' => $date,
            'images' => $images,
            'comments' => $comments,
            'likes' => $likes,
        );

        echo json_encode($data);
    }

    //get all the images of a particular post
    function getPostImages($postID, $con)
    {
        $queryImg = 'SELECT * FROM `post_images` WHERE `postID` = "' . $postID . '"';
        $resultImg = mysqli_query($con, $queryImg);
        $rowImg = mysqli_num_rows($resultImg);

        $image = array();
        if ($resultImg && $rowImg > 0) {
            while ($data = mysqli_fetch_assoc($resultImg)) {
                $img = $data['image'];
                $imgFormat = base64_encode($img);
                $image[] = $imgFormat;
            }
        }

        return $image;
    }

    //get total number of comments f a particular post
    function getPostComments($postID, $con)
    {
        $query = 'SELECT * FROM `comment` WHERE `postID`= "' . $postID . '"';
        $result = mysqli_query($con, $query);
        $row = mysqli_num_rows($result);

        return $row;
    }

    //get total number of likes f a particular post
    function getPostLikes($postID, $con)
    {
        $query = "SELECT * FROM `postlike` WHERE `postID` = '$postID' ";
        $result = mysqli_query($con, $query);
        $row = mysqli_num_rows($result);

        return $row;
    }

    function getCollegePost($username, $college, $date, $con)
    {
        //check user if it retrieve post today or not
        $queryPrevPost = "SELECT `timestamp` FROM `previous_post` WHERE `username`= '$username' AND `date_posted` = '$date'";
        $result = mysqli_query($con, $queryPrevPost);
        $numOfRetrievedData = mysqli_num_rows($result);

        //the user already retrieve post posted today
        if ($numOfRetrievedData > 0) {
            $prevTimeStamp = mysqli_fetch_assoc($result);
            $timestamp = $prevTimeStamp['timestamp'];

            //retrieve first the data left from today's post
            $queryRetrievePost = "SELECT * FROM `post` WHERE `date`= '$date' AND `colCode`='$college' AND `timestamp`>'$timestamp' LIMIT 0, 10";
            $result = mysqli_query($con, $queryRetrievePost);

            //get all the data of the post
            $this->getPostData($result, $con);
        } else { // if the user just starting to retrieve data for today

            $queryRetrievePost = "SELECT * FROM `post` WHERE `date`= '$date' AND `colCode`='$college' ORDER BY `date` DESC ";
            $result = mysqli_query($con, $queryRetrievePost);
            //get all the data of the post
            $this->getPostData($result, $con);
        }
    }

    function getPostData($result, $con)
    {
        $row = mysqli_num_rows($result);

        //data that will be retrieving
        $response = "";
        $timestamp = "";
        $username = array();
        $fullname = array();
        $imgProfile = array();
        $postID = array();
        $colCode = array();
        $caption = array();
        $date = array();
        $images = array();
        $comments = array();
        $likes = array();

        if ($result && $row > 0) {
            $response = 'Success';
            while ($data = mysqli_fetch_assoc($result)) {
                $postID[] = $data['postID'];
                $username[] = $data['username'];
                $user = $data['username'];
                $colCode[] = $data['colCode'];
                $caption[] = $data['caption'];
                $date[] = $data['date'];
                $timestamp = $data['timestamp'];
                $ID = $data['postID'];
                $images[] = $this->getPostImages($ID, $con);
                $comments[] = $this->getPostComments($ID, $con);
                $likes[] = $this->getPostLikes($ID, $con);

                $user = $this->getUserDetails($user, $con);
                $fullname[] = $user['fullname'];
                $imgProfile[] = $user['profilePic'];
            }
        } else $response = 'None';

        $data = array(
            'response' => $response,
            'username' => $username,
            'postID' => $postID,
            'colCode' => $colCode,
            'caption' => $caption,
            'date' => $date,
            'images' => $images,
            'comments' => $comments,
            'likes' => $likes,
            'fullname' => $fullname,
            'profilePic' => $imgProfile,
            'timestamp' => $timestamp,
        );

        echo json_encode($data);
    }

    function insertToPrevPost($username, $date, $timestamp, $con)
    {
        $query = "INSERT INTO `previous_post`(`username`, `date_posted`, `timestamp`) 
        VALUES ('$username','$date','$timestamp')";
        $result = mysqli_query($con, $query);

        if ($result) echo 'yehey';
        else echo 'ayaw';
    }



    function getUserDetails($username, $con)
    {
        $queryPerson = "SELECT 'univadmin' AS user_type, p.fname, p.lname, p.profilepicture
                FROM univadmin ua
                JOIN person p ON p.personID = ua.personID
                WHERE ua.username = '$username'
                UNION
                SELECT 'alumni' AS user_type, p.fname, p.lname, p.profilepicture
                FROM alumni al
                JOIN person p ON p.personID = al.personID
                WHERE al.username = '$username'
                UNION
                SELECT 'student' AS user_type, p.fname, p.lname, p.profilepicture
                FROM student s
                JOIN person p ON p.personID = s.personID
                WHERE s.username = '$username'";

        $fullname = "";
        $profilePic = "";
        $resultPerson = mysqli_query($con, $queryPerson);
        if ($resultPerson) {
            while ($personData = mysqli_fetch_assoc($resultPerson)) {
                $fullname = $personData['fname'] . ' ' . $personData['lname'];
                $img = $personData['profilepicture'];
                $profilePic = base64_encode($img);
            }
        }

        $accDetails = array(
            "fullname" => $fullname,
            "profilePic" => $profilePic
        );
        return $accDetails;
    }
}
