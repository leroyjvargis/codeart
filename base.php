<?php
session_start();
 
$dbhost = ""; 	//DB credentials
$dbname = "";
$dbuser = "";
$dbpass = ""; 
 
$link = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname) or die("MySQL Error: " . mysql_error());

$contact = 'codeart@cecsummit.org';

function log_data($user, $action, $message = "nil", $details = "nil")
{
	global $link;	

	mysqli_query($link, "INSERT INTO logs (User, Action, Message, Details)
           VALUES ('$user', '$action', '$message', '$details')");
	
}
?>