<?php
    class Career{
        //job insertion
        public function insertionJob($careerID,$jobTitle,$companyName,$descript,
                        $logo,$minSalary,$maxSalary,$colCode,$author,$con){
            
            $date_posted  = date('y-m-d');
            $query = "INSERT INTO `career`(`careerID`, `jobTitle`, `companyName`, `jobDescript`, 
            `companyLogo`, `minSalary`, `maxSalary`, `colCode`, `author`, `date_posted`) 
            VALUES ('$careerID','$jobTitle','$companyName','$descript','$logo','$minSalary',
            '$maxSalary','$colCode','$author','$date_posted')";

            $result = mysqli_query($con, $query);

            //check if the result if success
            if($result) return true;
            else return false;
        }
    }
    
?>