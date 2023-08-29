<?php

class CollegeLog
{
    public function insertLog($action, $details, $con)
    {
        session_start();
        $adminID = $_SESSION['adminID'];
        $timestamp = date('Y-m-d H:i:s');

        $query = "INSERT INTO `collegeadmin_log`( `colAdmin`, `action`, `timestamp`, 
        `details`) VALUES (?,?,?,?)";

        $stmt = mysqli_prepare($con, $query);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ssss", $adminID, $action, $timestamp, $details);
            $result = mysqli_stmt_execute($stmt);

            if ($result) echo 'Success';
            else echo 'Unsuccess';
        }
    }
}
