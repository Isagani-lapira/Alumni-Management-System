<?php

    class User_Table{

        //insertion of user
        public function addUser($username,$password,$accounType, $connection){
            $query = "INSERT INTO `user`(`username`, `password`, `accounType`) 
            VALUES ('$username','$password','$accounType')";

            if(mysqli_query($connection,$query))return true;
            else return false;
        }

        public function checkUser($username,$password,$connection){
            $query = 'SELECT * FROM `user` WHERE `username`= "'.$username.'" AND `password`= "'.$password.'"';
            $result = mysqli_query($connection,$query);

            //check the result
            $row = mysqli_num_rows($result);

            //return the response
            if($row>0) echo "successful";
            else echo "unsuccessful";
        }
    }

?>