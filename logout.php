<?php include "base.php";
$username = $_SESSION['Username'];
log_data($username, "Logged out");		//log log out
$_SESSION = array();
  session_destroy(); ?>
<meta http-equiv="refresh" content="0;index.php">