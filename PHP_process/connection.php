<?php
$host = 'localhost';
$username = 'root';
$password = "";
$database = "Alumni_Management_System";

$mysql_con = new mysqli($host, $username, $password, $database);

if ($mysql_con->connect_error) {
    die('Connection Failed: ' . $mysql_con->connect_error);
}
