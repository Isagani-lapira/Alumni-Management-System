<?php
    require_once 'career.php';
    require_once 'connection.php';
    
    if(isset($_POST['action'])){
        try{
            $data = $_POST['action'];
            $arrayData = json_decode($data,true);
            $action = $arrayData['action'];

            if($action=='create'){
                $admin = $_POST['author'];

                //skills
                $skillData = $_POST['skills'];
                $skillArray = json_decode($skillData,true);

                $reqData = $_POST['requirements'];
                $reqArray = json_decode($reqData,true);
                //logo
                $image = addslashes(file_get_contents($_FILES['jobLogoInput']['tmp_name']));

                //retrieve the value 
                $jobTitle = $_POST['jobTitle'];
                $companyName = $_POST['companyName'];
                $projectDescript = $_POST['projDescriptTxt'];
                $minSalary = $_POST['minSalary'];
                $maxSalary = $_POST['maxSalary'];
                $qualification = $_POST['qualificationTxt'];
                $personID = $_POST['personID'];
                //for career ID
                $uniqueId = substr(md5(uniqid()), 0, 7); //unique id with length of 7
                $careerID = 'career'.$uniqueId;
    
                //insert a data 
                $career = new Career();
                $career->insertionJob($careerID,$jobTitle,$companyName,$projectDescript,
                                        $image,$minSalary,$maxSalary,'CICT',$admin,
                                        $skillArray,$reqArray,$personID,$mysql_con);
    
                if($career) echo 'Job successfully added on the hunt';
                else echo 'Unexpected issue: Try again later';

            }
            else if($action=='read'){

                $readCareer = new Career();
                //check if there's a condition or non
                $condition = ($_POST['query']=="NONE")? NULL:$_POST['query'];
                $readCareer->selectData($condition,$mysql_con);
            }
        }
        catch(Exception $e){
            echo $e->getMessage();
        }
        
    }
    else echo 'Action has not been yet initialized'
?>