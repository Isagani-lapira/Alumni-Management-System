<?php

class PostData
{
    public function insertPostData($postID, $username, $colCode, $caption, $date, $img, $con)
    {
        $query = "INSERT INTO `post`(`postID`, `username`, `colCode`, `caption`, `date`) 
        VALUES ('$postID','$username','$colCode','$caption','$date')";
        $result = mysqli_query($con, $query);

        if ($result) {
            $hasImg = (count($img)) ? true : false;
            if ($hasImg) {
                echo 'rar';
            }

            return true;
        } else return false;
    }

    function insertImgPost($postID, $img, $con)
    {
        $imageID = $postID . uniqid();
        $query = "INSERT INTO `post_images`(`imageID`, `postID`, `image`) 
        VALUES ('$imageID','$postID','[value-3]')";
    }
}
