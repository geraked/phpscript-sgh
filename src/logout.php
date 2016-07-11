<?php
include_once 'config.php';
session_start();
session_unset();     // unset $_SESSION variable for the run-time 
session_destroy();   // destroy session data in storage
header("Location: ".MAIN_URL."login");
?>