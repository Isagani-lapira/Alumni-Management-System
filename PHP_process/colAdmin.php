<?php

    class College_Admin{

        //adding college admin
        public function insertColAdmin($adminID,$colCode,$personID,$username, $con){
            $query = "INSERT INTO `coladmin`(`adminID`, `colCode`, `personID`,
             `username`) VALUES ('$adminID','$colCode','$personID','$username')";

            if(mysqli_query($con,$query)) return true;
            else return false;
            
        }
    }

?>