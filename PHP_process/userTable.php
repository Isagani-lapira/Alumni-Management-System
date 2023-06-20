<?php

    class User_Table{

        //insertion of user
        public function addUser($username,$password,$accounType, $connection){
            $query = "INSERT INTO `user`(`username`, `password`, `accounType`) 
            VALUES ('$username','$password','$accounType')";

            if(mysqli_query($connection,$query))
                return true;
            else return false;
        }
    }

?>