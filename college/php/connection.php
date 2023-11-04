<?php
$host = 'localhost';
$username = 'root';
$password = "";
$database = "alumni_management_system";

$mysql_con = new mysqli($host, $username, $password, $database);
mysqli_set_charset($mysql_con, "utf8");
if ($mysql_con->connect_error) {
    die('Connection Failed: ' . $mysql_con->connect_error);
}
