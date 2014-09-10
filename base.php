<?php
session_start();
 
$dbhost = "localhost"; 
$dbname = "codeart_db";
$dbuser = "root";
$dbpass = ""; 
 
$link = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname) or die("MySQL Error: " . mysql_error());

?>