<?php

class User_Table
{

    //insertion of user
    public function addUser($username, $password, $accounType, $connection)
    {
        $query = "INSERT INTO `user`(`username`, `password`, `accounType`) 
            VALUES ('$username','$password','$accounType')";

        if (mysqli_query($connection, $query)) return true;
        else return false;
    }

    public function checkUser($username, $password, $connection)
    {
        $query = "SELECT * FROM `user` WHERE BINARY `username` = '$username' AND BINARY `password` = '$password'";
        $result = mysqli_query($connection, $query);

        //check the result
        $row = mysqli_num_rows($result);

        //return the response
        if ($row > 0) {
            $data = mysqli_fetch_assoc($result);
            $accounType = $data['accounType'];

            echo "successful";
            session_start();
            $_SESSION['username'] = $username;
            $_SESSION['logged_in'] = true;
            $_SESSION['accountType'] = $accounType;
        } else echo "unsuccessful";
    }

    public function checkUsername($username, $connection)
    {
        $query = 'SELECT * FROM `user` WHERE `username` = "' . $username . '"';
        $result = mysqli_query($connection, $query);

        $row = mysqli_num_rows($result);

        if ($row > 0) echo 'exist';
        else echo 'available';
    }

    public function updatePassword($newPassword, $username, $connection)
    {
        $query = "UPDATE `user` SET `password`= ? WHERE `username` = ? ";
        $stmt = mysqli_prepare($connection, $query);

        if ($stmt) {
            $stmt->bind_param('ss', $newPassword, $username);
            $result = $stmt->execute();
            echo $result;
        }
    }
}
