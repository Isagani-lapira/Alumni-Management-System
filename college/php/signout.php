<?php

session_start();
session_destroy();

header("location: /college/login.php");
exit();
