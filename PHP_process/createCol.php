<?php
    require_once 'connection.php';
    
    $arraData = $_POST['arrayData'];
    $myArray = json_decode($arraData, true);

    $resposeData = [
        'status'=> 'success',
        'message'=>'Process successfully',
        'data'=>$myArray
    ];


    //values
    $colName = $myArray[2];
    $colCode = $myArray[3];
    $colEmailAdd = $myArray[4];
    $colContactNo = $myArray[5];
    $colWebLink = $myArray[6];

    $query = "INSERT INTO `college`(`colCode`, `colname`, `colEmailAdd`, 
    `colContactNo`, `colWebLink`, `colLogo`, `colAdmin`, `colDean`, 
    `colDeanImg`, `adminImg`) VALUES ('$colCode','$colName','$colEmailAdd',
    '$colContactNo','$colWebLink',NULL,NULL,NULL,
    NULL,NULL)";

    $result = mysqli_query($mysql_con,$query);

    if($result){
        echo 'Insertion Complete';
    }
    else
        echo 'problem insertions';

    mysqli_close($mysql_con);
?>