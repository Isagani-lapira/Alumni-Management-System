<?php
    require_once 'connection.php';
    require_once 'personDB.php';
    require_once 'userTable.php';
    require_once 'colAdmin.php';

    if(isset($_POST['data'])) {

        try{
            $data = $_POST['data'];
            $actionArray = json_decode($data, true);
            $action = $actionArray['action'];
            switch($action){
                case 'read':
                    $totalCol = collegeCount($mysql_con);
                    echo $totalCol;
                    break;
                case 'create':
                    insertionCollege($mysql_con);
                    break;
                default:
                    echo 'An error';
                    break;
            }
        } catch(Exception $e){
            echo 'An error occured: '.$e->getMessage();
        }       
    }
    else echo 'Data has not been yet set. Try again later';

    function insertionCollege($con){
        if(isset($_FILES['collegeLogo']) && isset($_POST['arrayData'])){
            $image = addslashes(file_get_contents($_FILES['collegeLogo']['tmp_name']));

            $arraData = $_POST['arrayData'];
            $myArray = json_decode($arraData, true);

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
            
            //query
            $query = "INSERT INTO `college`(`colCode`, `colname`, 
            `colEmailAdd`, `colContactNo`, `colWebLink`, `colLogo`,
            `colDean`, `colDeanImg`) VALUES ('$colCode','$colName','$colEmailAdd',
            '$colContactNo','$colWebLink','$image',NULL,NULL)";

            $result = mysqli_query($con,$query);

            if($result){
                //will serve as person ID in the database
                $randomNo = rand(1,2000);
                $currentDateTime = date('y/m/d h:i:s');
                $personID = 'admin'.$currentDateTime.'-'.$randomNo;

                $person = new personDB();
                //add data to the person database
                $resultPersonQuery = $person->insertPerson($personID,$fname,$lname,getAge($birthday),$birthday,
                                        $contactNo,$address,$personalEm,$bulsuEm,$gender,null,$con);

                //add data to user table after successfully adding person data
                if($resultPersonQuery){
                    $userTable = new User_Table();
                    $userInsertion = $userTable->addUser($username,$password,'Admin',$con);

                    // serve as admin ID
                    $currentYr = date('Y');
                    $adminID = 'ADM'.'-'.$currentYr.'-'.$randomNo;

                    //add college admin data after successful creation of user account
                    if($userInsertion){
                        $colAdmin = new College_Admin();
                        $colAdmin->insertColAdmin($adminID,$colCode,$personID,$username,$con);
                    }
                }
                else echo 'Unexpected error. Try again!';
            } 
            else echo 'Unexpected error. Try again!';

        }   
        else echo 'Data has not been yet initialized';
    }

    // //get current age
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
    // read total number of collegegs
    function collegeCount($con){
        $query = 'SELECT COUNT(*) FROM college';
        $result = mysqli_query($con,$query);

        //fetch all the data that will return number of rows in an array format
        $row = mysqli_fetch_row($result);
        $count = $row[0]; //get the value that correspond to the total count

        return $count;
    }

    mysqli_close($mysql_con);
?>