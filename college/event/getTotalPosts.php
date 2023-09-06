<?php
session_start();


require '../php/connection.php';
require '../model/Event.php';

$event = new Event($mysql_con);
echo $event->getTotalEvents($_SESSION['colCode']);
