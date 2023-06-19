<?php
    require_once 'connection.php';
    require_once 'personDB.php';
    
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

    $query = "INSERT INTO `college`(`colCode`, `colname`, 
    `colEmailAdd`, `colContactNo`, `colWebLink`, `colLogo`,
    `colDean`, `colDeanImg`) VALUES ('$colCode','$colName','$colEmailAdd',
    '$colContactNo','$colWebLink','$file',NULL,NULL)";

    $result = mysqli_query($mysql_con,$query);

    // $personID,$FName,$LName,$age,$bday,
    //$contactNo,$address,$personalEmail,$bulsuEmail,$gender,$profilePic 
    if($result){
        $currentDate = date('y/m/d');
        $personID = 'admin'.$currentDate;
        $person = new personDB();
        $person->insertPerson($personID,$fname,$lname,getAge($birthday),$birthday,
                                $contactNo,$address,$personalEm,$bulsuEm,$gender,null,$mysql_con);
    }
    else
        echo 'problem insertions';

    function getAge($bday){
        // Current date
        $currentDate = date('Y-m-d');

        // Create DateTime objects for the birthdate and current date
        $birthdateObj = new DateTime($bday);
        $currentDateObj = new DateTime($currentDate);

        // Calculate the difference between the two dates
        $ageInterval = $birthdateObj->diff($currentDateObj);

        // Retrieve the age from the difference
        $age = $ageInterval->y;

        return $age; 
    }
    mysqli_close($mysql_con);
?>