<?php
    class personDB{
        // insertion data
        public function insertPerson($personID,$FName,$LName,$age,$bday,
        $contactNo,$address,$personalEmail,$bulsuEmail,$gender,$profilePic ,$con){
            
            $query = "INSERT INTO `person`(`personID`, `fname`, `lname`, 
            `age`, `bday`, `gender`, `contactNo`, `address`, `personal_email`, 
            `bulsu_email`, `profilepicture`) VALUES ('$personID','$FName',
            '$LName','$age','$bday','$gender','$contactNo','$address','$personalEmail',
            '$bulsuEmail','$profilePic')";

            $result = mysqli_query($con,$query);
            
            if($result) return true;
            else return false;
        }
    }
?>