<?php
    require_once 'connection.php';
    require_once 'personDB.php';
    require_once 'userTable.php';
    require_once 'colAdmin.php';
    
    $arraData = $_POST['arrayData'];
    $myArray = json_decode($arraData, true);

    $file = $myArray[0];

    //values
    $colName = $myArray[1];
    $colCode = $myArray[2];
    $colEmailAdd = $myArray[3];
    $colContactNo = $myArray[4];
    $colWebLink = $myArray[5];
    $fname = $myArray[6];
    $lname = $myArray[7];
    $personalEm = $myArray[8];
    $bulsuEm = $myArray[9];
    $contactNo = $myArray[10];
    $address = $myArray[11];
    $birthday = $myArray[12];
    $gender = $myArray[13];
    $username = $myArray[15];
    $password = $myArray[16];
    

    $query = "INSERT INTO `college`(`colCode`, `colname`, 
    `colEmailAdd`, `colContactNo`, `colWebLink`, `colLogo`,
    `colDean`, `colDeanImg`) VALUES ('$colCode','$colName','$colEmailAdd',
    '$colContactNo','$colWebLink','$file',NULL,NULL)";

    $result = mysqli_query($mysql_con,$query);

    if($result){
        $randomNo = rand(1,2000);
        $currentDateTime = date('y/m/d h:i:s');
        $personID = 'admin'.$currentDateTime.'-'.$randomNo;
        $person = new personDB();
        
        //add data to the person database
        $resultPersonQuery = $person->insertPerson($personID,$fname,$lname,getAge($birthday),$birthday,
                                $contactNo,$address,$personalEm,$bulsuEm,$gender,null,$mysql_con);

        //add data to user table
        if($resultPersonQuery){
            $userTable = new User_Table();
            $userInsertion = $userTable->addUser($username,$password,'Admin',$mysql_con);

            $currentYr = date('Y');
            $adminID = 'ADM'.'-'.$currentYr.'-'.$randomNo;
            if($userInsertion){
                $colAdmin = new College_Admin();
                $colAdmin->insertColAdmin($adminID,$colCode,$personID,$username,$mysql_con);
            }
        }
    }
    else
        echo 'Unsuccessful insertions. Try again';

    //get current age
    function getAge($bday){
        // Current date
        $currentDate = date('Y-m-d');

        // Create DateTime objects for the birthdate and current date
        $birthdateObj = (int)substr($bday,0,4);
        $currentDateObj = (int)substr($currentDate,0,4);

        // Calculate the difference between the two dates
        $ageInterval = $birthdateObj-$currentDateObj;

        return $ageInterval; 
    }
    mysqli_close($mysql_con);
?>