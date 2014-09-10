<?php
session_start();
 
$dbhost = ""; 
$dbname = "";
$dbuser = "";
$dbpass = ""; 
 
$link = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname) or die("MySQL Error: " . mysql_error());

?>