<?php
session_start();


require "./logging.php";
require "./connection.php";


setNewActivity($mysql_con, $_SESSION['adminID'], "signout", "signed out");

// Unset all of the session variables.
$_SESSION = array();
session_destroy();
