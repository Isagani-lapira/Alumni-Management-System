<?php
    require_once 'career.php';
    require_once 'connection.php';

    if(isset($_POST['action'])){
        $data = $_POST['action'];
        $arrayData = json_decode($data,true);
        $action = $arrayData['action'];

        if($action=='create'){
            //retrieve the value 
            $jobTitle = $_POST['jobTitle'];
            $companyName = $_POST['companyName'];
            $projectDescript = $_POST['projDescriptTxt'];
            $minSalary = $_POST['minSalary'];
            $maxSalary = $_POST['maxSalary'];
            $qualification = $_POST['qualificationTxt'];

            // $jobTitle = $_POST['jobTitle'];
            // $jobTitle = $_POST['jobTitle'];
            // $jobTitle = $_POST['jobTitle'];
            //insert a data 
            $career = new Career();
            $career->insertionJob('rar',$jobTitle,$companyName,$projectDescript,
                                    NULL,$minSalary,$maxSalary,'CICT','Admin',NULL,$mysql_con);

            if($career) echo 'inserted';
            else echo 'not inserted';
        }
    }
    else echo 'Action has not been yet initialized'
?>