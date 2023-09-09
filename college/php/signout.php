<?php
session_start();


require "./logging.php";
require "./connection.php";

logSignoutActivity($mysql_con, $_SESSION['adminID'], $_SESSION['colCode']);

// Unset all of the session variables.
$_SESSION = array();
session_destroy();
