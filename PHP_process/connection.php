<?php
$host = 'localhost';
$username = 'u673355866.eliamarg';
$password = "Ga#1%L*DHFdR5&";
$database = "u673355866_eliamarg";

$mysql_con = new mysqli($host, $username, $password, $database);

if ($mysql_con->connect_error) {
    die('Connection Failed: ' . $mysql_con->connect_error);
}
