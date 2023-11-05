<?php
$host = 'localhost';
$username = 'root';
$password = "";
$database = "Alumni_Management_System";

$mysql_con = new mysqli($host, $username, $password, $database);
mysqli_set_charset($mysql_con, "utf8");


if ($mysql_con->connect_error) {
    die('Connection Failed: ' . $mysql_con->connect_error);
}
