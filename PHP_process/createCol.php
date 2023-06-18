<?php
    require_once 'connection.php';

    $query = 'INSERT INTO college (colCode,colname,colEmailAdd,colContactNo,
            colWebLink, colLogo,colAdmin,colDean,colDeanImg,adminImg)
            VALUES (?,?,?,?,?,?,NULL,NULL,NULL,NULL)';

    $stmt = $mysql_con->prepare($query);

    // //add values
    // $stmt->bind_param('sssssb',)

    echo 'rar';
?>