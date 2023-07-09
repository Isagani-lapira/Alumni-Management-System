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

    function getPostAdmin($username, $con)
    {
        $query = 'SELECT * FROM `post` WHERE `username`= "' . $username . '"';
        $result = mysqli_query($con, $query);
        $row = mysqli_num_rows($result);

        $response = "";
        $postID = "";
        $colCode = array();
        $caption = array();
        $date = array();
        $images = array();
        $comments = array();
        if ($result && $row > 0) {
            $response = 'Success';
            while ($data = mysqli_fetch_assoc($result)) {
                $postID = $data['postID'];
                $colCode[] = $data['colCode'];
                $caption[] = $data['caption'];
                $date[] = $data['date'];

                $postID = $data['postID'];
                $images[] = $this->getPostImages($postID, $con);
                $comments[] = $this->getPostComments($postID, $con);
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
            'comments' => $comments
        );

        echo json_encode($data);
    }


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

    function getPostComments($postID, $con)
    {
        $query = 'SELECT * FROM `comment` WHERE `postID`= "' . $postID . '"';
        $result = mysqli_query($con, $query);
        $row = mysqli_num_rows($result);

        return $row;
    }
}
