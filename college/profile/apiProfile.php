
<?php

session_start();

require "../php/connection.php";
require "../php/logging.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['update-college-form'])) {



        //"UPDATE `college` SET `colCode`='[value-1]',`colname`='[value-2]',`colEmailAdd`='[value-3]',`colContactNo`='[value-4]',`colWebLink`='[value-5]',`colLogo`='[value-6]',`colDean`='[value-7]',`colDeanImg`='[value-8]',`description`='[value-9]' WHERE 1";

        $colCode = $_SESSION['colCode'];

        $colName = $_POST['colName'];
        $colEmailAdd = $_POST['colEmailAdd'];
        $colContactNo = $_POST['colContactNo'];
        $colWebLink = $_POST['colWebLink'];
        $colDean = $_POST['colDean'];
        $description = $_POST['description'];

        $colLogoData = '';
        $colDeanImgData = '';

        if (isset($_FILES['colLogo']) && $_FILES['colLogo']['error'] === UPLOAD_ERR_OK && isset($_FILES['colDeanImg']) && $_FILES['colDeanImg']['error'] === UPLOAD_ERR_OK) {
            // there are logo and dean image uploaded
            $colLogo = $_FILES['colLogo']['tmp_name'];
            $colLogoData = file_get_contents($colLogo);


            $colDeanImg = $_FILES['colDeanImg']['tmp_name'];
            $colDeanImgData = file_get_contents($colDeanImg);


            $stmt = $mysql_con->prepare("UPDATE college SET  colname = ?, colEmailAdd = ?, colContactNo = ?, colWebLink = ?, colLogo = ?, colDean = ?, colDeanImg = ?, description = ? WHERE colCode = ?;");
            // *  Binds the variable to the '?', prevents sql injection
            $stmt->bind_param(
                "sssssssss",
                $colName,
                $colEmailAdd,
                $colContactNo,
                $colWebLink,
                $colLogoData,
                $colDean,
                $colDeanImgData,
                $description,
                $colCode
            );
        } else if (
            // there is only logo uploaded
            isset($_FILES['colLogo']) && $_FILES['colLogo']['error'] === UPLOAD_ERR_OK
        ) {
            $colLogo = $_FILES['colLogo']['tmp_name'];
            $colLogoData = file_get_contents($colLogo);


            $stmt = $mysql_con->prepare("UPDATE college SET  colname = ?, colEmailAdd = ?, colContactNo = ?, colWebLink = ?, colLogo = ?, colDean = ?,  description = ? WHERE colCode = ?;");
            // *  Binds the variable to the '?', prevents sql injection
            $stmt->bind_param(
                "ssssssss",
                $colName,
                $colEmailAdd,
                $colContactNo,
                $colWebLink,
                $colLogoData,
                $colDean,
                $description,
                $colCode
            );
        } else if (
            isset($_FILES['colDeanImg']) && $_FILES['colDeanImg']['error'] === UPLOAD_ERR_OK
        ) {
            $colDeanImg = $_FILES['colDeanImg']['tmp_name'];
            $colDeanImgData = file_get_contents($colDeanImg);


            $stmt = $mysql_con->prepare("UPDATE college SET  colname = ?, colEmailAdd = ?, colContactNo = ?, colWebLink = ?,  colDean = ?, colDeanImg = ?, description = ? WHERE colCode = ?;");
            // *  Binds the variable to the '?', prevents sql injection
            $stmt->bind_param(
                "ssssssss",
                $colName,
                $colEmailAdd,
                $colContactNo,
                $colWebLink,
                $colDean,
                $colDeanImgData,
                $description,
                $colCode
            );
        } else {
            // there is no image uploaded
            $stmt = $mysql_con->prepare("UPDATE college SET  colname = ?, colEmailAdd = ?, colContactNo = ?, colWebLink = ?, colDean = ?, description = ? WHERE colCode = ?;");
            // *  Binds the variable to the '?', prevents sql injection
            $stmt->bind_param(
                "sssssss",
                $colName,
                $colEmailAdd,
                $colContactNo,
                $colWebLink,
                $colDean,
                $description,
                $colCode
            );
        }
        //  make a query to update the college table using mysqli prepared statement
        try {
            $stmt->execute();
            // return json response
            setNewActivity(
                $mysql_con,
                $_SESSION['adminID'],
                "Update",
                "Updated College Information"
            );
            echo json_encode(
                array(
                    "message" => "Post data",
                    "status" => true,
                    "response" => "success"

                )
            );
        } catch (\Throwable $th) {
            echo json_encode(
                array(
                    "message" => "Post data",
                    "status" => false,
                    "response" => "failed",
                    "error" => $th->getMessage()
                )
            );
        }
        $stmt->close();
    } else {
        echo json_encode(
            array(
                "message" => "Post data",
                "status" => false,
                "response" => "failed"
            )
        );
    }
}
