<?php
    require_once 'career.php';
    require_once 'connection.php';

    if(isset($_POST['action'])){

        try{
            $data = $_POST['action'];
            $arrayData = json_decode($data,true);
            $action = $arrayData['action'];
            $admin = $_POST['author'];

            //skills
            $skillData = $_POST['skills'];
            $skillArray = json_decode($skillData,true);

            //logo
            $image = addslashes(file_get_contents($_FILES['jobLogoInput']['tmp_name']));

            if($action=='create'){
                //retrieve the value 
                $jobTitle = $_POST['jobTitle'];
                $companyName = $_POST['companyName'];
                $projectDescript = $_POST['projDescriptTxt'];
                $minSalary = $_POST['minSalary'];
                $maxSalary = $_POST['maxSalary'];
                $qualification = $_POST['qualificationTxt'];
    
                //for career ID
                $uniqueId = substr(md5(uniqid()), 0, 7); //unique id with length of 7
                $careerID = 'career'.$uniqueId;
    
                //insert a data 
                $career = new Career();
                $career->insertionJob($careerID,$jobTitle,$companyName,$projectDescript,
                                        $image,$minSalary,$maxSalary,'CICT',$admin,
                                        $skillArray,$mysql_con);
    
                if($career) echo 'Job successfully added!';
                else echo 'Unexpected issue: Try again later';

            }
        }
        catch(Exception $e){
            echo $e->getMessage();
        }
        
    }
    else echo 'Action has not been yet initialized'
?>