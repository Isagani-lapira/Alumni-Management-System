<?php

require 'collegeAdminLog.php';
require 'notificationTable.php';
class PostData
{
    private $postTimeStamp = "";

    public function insertPostData($postID, $username, $colCode, $caption, $date, $img, $con, $colAdmin = false)
    {
        $imgFileLength = ($img != null) ? count($img['name']) : 0;
        $timestamp = date('Y-m-d H:i:s');
        $status = 'available'; // Default status

        $query = "INSERT INTO `post`(`postID`, `username`, `colCode`, `caption`, `date`, `timestamp`, `status`) 
              VALUES (?,?,?,?,?,?,?)";

        $stmt = mysqli_prepare($con, $query);

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
                        $this->insertImgPost($postID, $fileContent, $con);
                    }
                }

                //if the query is based on college admin then log it
                if ($colAdmin) {
                    $logObj = new CollegeLog();
                    $action = 'posted';
                    $details = 'Created a new post';
                    $resultLog = $logObj->insertLog($action, $details, $con);
                    if ($resultLog) return true;
                    else return false;
                }

                mysqli_stmt_close($stmt);
                return true;
            } else {
                mysqli_stmt_close($stmt);
                return false;
            }
        } else {
            return false;
        }
    }

    //insert images
    function insertImgPost($postID, $img, $con)
    {
        $random = rand(0, 100);
        $imageID = $postID . '-' . uniqid() . '-' . $random;

        // Prepare a parameterized query
        $query = "INSERT INTO `post_images`(`imageID`, `postID`, `image`) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($con, $query);

        if ($stmt) {
            // Bind the parameters
            mysqli_stmt_bind_param($stmt, 'sss', $imageID, $postID, $img);

            // Execute the query
            $result = mysqli_stmt_execute($stmt);

            if ($result) {
                mysqli_stmt_close($stmt);
                return true;
            }
        }

        return false;
    }


    //get all the post by admin
    function getPostAdmin($username, $startingDate, $endDate, $offset, $con)
    {
        $maxLimit = 10;
        $query = "";
        //check if it the post retrieve based on date
        if ($startingDate != null && $endDate != null)
            $query = "SELECT * FROM `post` WHERE `date` BETWEEN '$startingDate' AND '$endDate' 
            AND `username`='$username' AND `status` = 'available' ORDER by `timestamp` DESC LIMIT $offset,$maxLimit";

        else $query = "SELECT * FROM `post` WHERE `username`= '$username' AND `status`='available' ORDER by `timestamp` DESC LIMIT $offset ,$maxLimit";


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

    function getCollegePost($username, $college, $date, $maxLimit, $con)
    {
        //check user if it retrieve post today or not
        $queryPrevPost = "SELECT `timestamp` FROM `previous_post` WHERE `username`= '$username' AND `date_posted` = '$date'";
        $result = mysqli_query($con, $queryPrevPost);
        $numOfRetrievedData = mysqli_num_rows($result);

        //the user already retrieve post posted today
        if ($numOfRetrievedData > 0) {
            $prevTimeStamp = mysqli_fetch_assoc($result);
            $timestamp = $prevTimeStamp['timestamp'];

            //retrieve first the data left from day post
            $queryRetrievePost = "SELECT * FROM `post` WHERE `date`= '$date' AND `colCode`='$college' AND 
            `status` = 'available' AND `timestamp`>'$timestamp' LIMIT 0, $maxLimit";
            $result = mysqli_query($con, $queryRetrievePost);
            $row = mysqli_num_rows($result);

            if ($row > 0) {
                $postResult = $this->getPostData($result, $con); //get all the data of the post
                $data = json_decode($postResult, true);
                $prevtimestamp = $data['timestamp'];

                //update the latest timestamp
                $queryUpdate = "UPDATE `previous_post` SET `timestamp`='$prevtimestamp' WHERE `username`='$username' AND `date_posted` = '$date'";
                $resultUpdate = mysqli_query($con, $queryUpdate);

                if ($resultUpdate) echo $postResult;
            } else echo 'none';
        } else { // if the user just starting to retrieve data for today

            $queryRetrievePost = "SELECT * FROM `post` WHERE `date`= '$date' AND `colCode`='$college' AND `status` = 'available' ORDER BY `timestamp` DESC LIMIT 0, $maxLimit";
            $result = mysqli_query($con, $queryRetrievePost);

            $post = $this->getPostData($result, $con); //get all the data of the post
            if ($post) {
                //insert the previous post
                $query = "INSERT INTO `previous_post`(`username`, `date_posted`, `timestamp`) VALUES ('$username','$date','$this->postTimeStamp')";
                $result = mysqli_query($con, $query);

                //create a previous post
                if ($result) echo $post;
            } else echo 'none';
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
        $likedByCurrentUser = array();
        $report = array();
        if ($result && $row > 0) {
            $response = 'Success';
            while ($data = mysqli_fetch_assoc($result)) {
                $tempPostID = $data['postID'];
                $postID[] = $tempPostID;
                $username[] = $data['username'];
                $user = $data['username'];
                $colCode[] = $data['colCode'];
                $caption[] = $data['caption'];
                $date[] = $data['date'];
                $timestamp = $data['timestamp'];
                $this->postTimeStamp = $timestamp;
                $ID = $data['postID'];
                $images[] = $this->getPostImages($ID, $con);
                $comments[] = $this->getPostComments($ID, $con);
                $likes[] = $this->getPostLikes($ID, $con);

                $user = $this->getUserDetails($user, $con);
                $fullname[] = $user['fullname'];
                $imgProfile[] = $user['profilePic'];

                //check if the user already liked a certain post
                $likedByCurrentUser[] = $this->checkedByUser($tempPostID, $con);

                //get post report
                $report[] = $this->postReport($tempPostID, $con);
            }
        } else $response = 'Unsuccess';

        $data = array(
            'response' => $response,
            'username' => $username,
            'postID' => $postID,
            'colCode' => $colCode,
            'caption' => $caption,
            'date' => $date,
            "isLiked" => $likedByCurrentUser,
            'images' => $images,
            'comments' => $comments,
            'likes' => $likes,
            'fullname' => $fullname,
            'profilePic' => $imgProfile,
            'timestamp' => $timestamp,
            'report' => $report
        );

        return json_encode($data);
    }

    function insertToPrevPost($username, $date, $timestamp, $con)
    {
        $query = "INSERT INTO `previous_post`(`username`, `date_posted`, `timestamp`) 
        VALUES ('$username','$date','$timestamp')";
        $result = mysqli_query($con, $query);

        if ($result) echo 'yehey';
        else echo 'ayaw';
    }

    //check if the user is already like the post
    function checkedByUser($postID, $con)
    {
        $username = $_SESSION['username']; //current user
        $query = "SELECT * FROM `postlike` WHERE `username` = '$username' AND `postID` = '$postID'";
        $result = mysqli_query($con, $query);
        $row = mysqli_num_rows($result);

        if ($result && $row > 0) return true;
        else return false;
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

    function getProfilePost($username, $offset, $status, $con)
    {
        $maxLimit = 10;
        //query to get the post of user
        $query = "SELECT * FROM `post` WHERE `username` = '$username'  AND `status` = '$status'
        ORDER BY `timestamp` DESC LIMIT $offset, $maxLimit";
        $result = mysqli_query($con, $query);
        $row = mysqli_num_rows($result);

        if ($result && $row > 0) {
            //retrieve data for a specific day
            $postResult = $this->getPostData($result, $con); //get all the data of the post
            echo $postResult;
        } else echo 'none';
    }


    function reportPost($postID, $username, $reportCategory, $con)
    {

        $timestamp = date('Y-m-d H:i:s');
        $random = rand(0, 5000);
        $reportID = $timestamp . '-' . $postID . '-' . $username . '-' . $random;

        $query = "INSERT INTO `report_post`(`reportID`, `postID`, `username`, `timestamp`, 
        `report_category`) VALUES ('$reportID','$postID','$username','$timestamp','$reportCategory')";
        $result = mysqli_query($con, $query);

        if ($result) echo 'Success';
        else echo 'Failed';
    }

    function updatePostStatus($postID, $status, $con, $colAdmin = false)
    {
        $query = "UPDATE `post` SET `status`= '$status' WHERE `postID`='$postID'";
        $result = mysqli_query($con, $query);

        //if the query is based on college admin then log it
        if ($colAdmin) {
            $logObj = new CollegeLog();
            $action = 'deleted';
            $details = $status . ' post';
            $resultLog = $logObj->insertLog($action, $details, $con);

            if ($resultLog) echo 'Deleted';
            else echo 'Error';
        } else {
            if ($result) echo 'Deleted';
            else echo 'Error';
        }
    }

    function removeUserPost($postID, $username, $details, $con)
    {
        $query = "UPDATE `post` SET `status`= 'adminArchived' WHERE `postID`= ?";
        $stmt = mysqli_prepare($con, $query);
        $stmt->bind_param('s', $postID);
        $result = $stmt->execute();

        // notify user about the post
        if ($result) {
            $typeNotif = "delete";
            $notification = new Notification();
            $notify = $notification->insertNotif($postID, $username, $typeNotif, $con, $details);

            if ($notify) echo 'Success';
            else echo 'Unsuccess';
        } else echo 'Unsuccess';
    }

    function getAllPost($offset, $con)
    {
        $maxLimit = 10;
        $query = "SELECT * FROM `post` WHERE `status` = 'available'
        ORDER BY `timestamp` DESC LIMIT $offset, $maxLimit";
        $result = mysqli_query($con, $query);
        $row = mysqli_num_rows($result);

        if ($result && $row) {
            $postResult = $this->getPostData($result, $con);
            echo $postResult;
        } else echo 'failed';
    }

    function getCollege($college, $reportCat, $offset, $con)
    {
        $maxLimit = 10; //default number of max number of retrieval
        $query = "";

        //report category is available only
        if ($college == null && $reportCat != null) {
            $query = "SELECT p.*, COUNT(rp.postID) AS num_reports
            FROM post p
            LEFT JOIN report_post rp ON p.postID = rp.postID
            WHERE p.status = 'available' AND rp.report_category = '$reportCat'
            GROUP BY p.postID
            ORDER BY num_reports DESC, p.timestamp DESC 
            LIMIT $offset, $maxLimit";
        } else if ($reportCat == null && $college != null) {
            //get college only
            $query = "SELECT * FROM `post` WHERE `colCode` = '$college' 
            AND `status`='available' ORDER BY `timestamp` DESC LIMIT $offset,$maxLimit";
        } else if ($college != null && $reportCat != null) {
            //get all with condition of report category and college
            $query = "SELECT p.*
            FROM post p
            JOIN report_post rp ON p.postID = rp.postID
            WHERE p.colCode = '$college' AND rp.report_category = '$reportCat' 
            ORDER BY `timestamp` DESC LIMIT $offset,$maxLimit";
        } else {
            $query = "SELECT * FROM `post` WHERE `status` = 'available'
            ORDER BY `timestamp` DESC LIMIT $offset, $maxLimit";
        }

        $result = mysqli_query($con, $query);
        $row = mysqli_num_rows($result);

        if ($result && $row > 0) {
            $postResult = $this->getPostData($result, $con);
            echo $postResult;
        } else echo 'failed';
    }

    function checkPost($postID, $con)
    {
        $query = "SELECT * FROM `post` WHERE `postID` = '$postID'";
        $result = mysqli_query($con, $query);

        $row = mysqli_num_rows($result);

        if ($result && $row) {
            $postResult = $this->getPostData($result, $con);
            echo $postResult;
        } else echo 'failed';
    }

    function postReport($postID, $con)
    {
        //get all the report
        $report = array();
        $query = "SELECT `report_category` FROM `report_post` WHERE `postID`='$postID'";
        $result = mysqli_query($con, $query);
        $row = mysqli_num_rows($result);

        $response = "";
        //retrieve the report
        if ($result && $row > 0) {
            $response = "Success";
            while ($data = mysqli_fetch_assoc($result)) {
                $report[] = $data['report_category'];
            }
        } else $response = "Nothing";

        $data = array(
            "response" => $response,
            "report" => $report
        );
        return json_encode($data);
    }

    function postCaptionUpdate($postID, $caption, $con, $colAdmin = false)
    {
        $query = "UPDATE `post` SET `caption`= ? WHERE `postID` = ?";
        $stmt = mysqli_prepare($con, $query);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 'ss', $caption, $postID);
            if (mysqli_stmt_execute($stmt)) {
                //if the query is based on college admin then log it
                if ($colAdmin) {
                    $logObj = new CollegeLog();
                    $action = 'updated';
                    $details = 'Updated a post';
                    $resultLog = $logObj->insertLog($action, $details, $con);
                    if ($resultLog) echo 'Successful';
                    else 'Failed: ' . mysqli_error($con);
                } else
                    echo 'Successful';
            } else
                echo 'Failed: ' . mysqli_error($con);

            mysqli_stmt_close($stmt);
        } else echo 'Error';
    }

    function getAllCollegePost($colCode, $offset, $con)
    {
        $maxLimit = 10;
        $status = "available";
        $query = "SELECT * FROM `post` WHERE `status` = ? 
        AND `colCode` = ? ORDER BY `timestamp` DESC LIMIT $offset,$maxLimit";
        $stmt = mysqli_prepare($con, $query);

        if ($stmt) {
            $stmt->bind_param('ss', $status, $colCode);
            $stmt->execute();
            $result = $stmt->get_result();

            $postResult = $this->getPostData($result, $con);
            echo $postResult;
        }
    }

    function getNewlyCreatePost($username, $con)
    {
        $query = "SELECT * FROM `post` WHERE `username` = '$username' ORDER BY `timestamp` DESC LIMIT 1";
        $result = mysqli_query($con, $query);
        $row = mysqli_num_rows($result);

        if ($result && $row > 0) {
            $postResult = $this->getPostData($result, $con);
            echo $postResult;
        } else echo 'failed';
    }

    function createAOYPost($AOMID, $username, $postID, $colCode, $con)
    {

        $date = date('y/m/d');
        $year = date('Y');
        $caption = 'We extend our congratulations to the recently assigned Alumni of the Year ' . $year . ' 
        We are grateful for your hard work and dedication. Keep up the good work';
        $STATUS = 'available';

        $query = "INSERT INTO `post`(`postID`, `username`, `colCode`, `caption`, `date`, `timestamp`, `status`) 
        VALUES (?,?,?,?,?,?,?)";
        $stmt = mysqli_prepare($con, $query);

        if ($stmt) {
            $stmt->bind_param('sssssss', $postID, $username, $colCode, $caption, $date, $timestamp, $STATUS);
            $result = $stmt->execute();
            $stmt->close();

            // add image to the post images
            if ($result) {
                // get the image in the AOM
                $queryCoverImg = "SELECT `cover_img` FROM `alumni_of_the_month` WHERE `AOMID` = ?";
                $stmtCover = mysqli_prepare($con, $queryCoverImg);

                if ($stmtCover) {
                    $stmtCover->bind_param('s', $AOMID);
                    $stmtCover->execute();
                    $stmtCover->bind_result($coverPhoto);
                    $stmtCover->fetch();
                    $stmtCover->close();

                    // store the cover photo on the post images
                    $this->insertImgPost($postID, $coverPhoto, $con);
                }
            }
        }
    }
}
