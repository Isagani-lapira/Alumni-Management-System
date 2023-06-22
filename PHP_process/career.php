<?php
    class Career{
        //job insertion
        public function insertionJob($careerID,$jobTitle,$companyName,$descript,
                        $logo,$minSalary,$maxSalary,$colCode,$author,$skill,$con){
            
            $date_posted  = date('y-m-d');
            $query = "INSERT INTO `career`(`careerID`, `jobTitle`, `companyName`, `jobDescript`, 
            `companyLogo`, `minSalary`, `maxSalary`, `colCode`, `author`, `date_posted`) 
            VALUES ('$careerID','$jobTitle','$companyName','$descript','$logo','$minSalary',
            '$maxSalary','$colCode','$author','$date_posted')";

            $result = mysqli_query($con, $query);
            
            //check if the result if success
            if($result){
                $skillLength = count($skill)-1; //there's always a one extra due to automatic creation of input field
                echo $skill[0];
                $index = 0;
                while($index<$skillLength){
                    $random = rand(0,5000);
                    $skillID = $careerID.'-'. $random;
                    
                    echo $skillID;
                    $this->insertSkill($skillID,$careerID,$skill[$index],$con);
                    $index++;
                }
            }
            else return false;
        }


        function insertSkill($skillID,$careerID,$skill,$con){
            $query = "INSERT INTO `skill`(`skillID`, `careerID`, `skill`) 
            VALUES ('$skillID','$careerID','$skill')";

            if(mysqli_query($con,$query)){
                return true;
            }
            else echo 'Unexpected error, try again later!' ;
        }


    }
    
?>