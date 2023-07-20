<?php

class PostData
{
    public function insertPostData($postID, $username, $colCode, $caption, $date, $img, $con)
    {
        $imgFileLength = count($img['name']);
        $query = "INSERT INTO `post`(`postID`, `username`, `colCode`, `caption`, `date`) 
        VALUES ('$postID','$username','$colCode','$caption','$date')";
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
}
