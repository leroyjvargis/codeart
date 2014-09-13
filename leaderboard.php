<?php include "base.php"; ?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Leaderboard | Code-Art | SUMMIT'14 | College of Engg Chengannur</title>

<link href="css/styles.css" rel="stylesheet" type="text/css" media="all" />
</head>


<body>  
<div id="head">
 <div id="head_cen">
  <div id="head_sup" class="head_pad">
   <p class="search">
    
    <?php
      if(!empty($_SESSION['LoggedIn']) && !empty($_SESSION['Username']))
      {
        ?>
       <p style = "color: #797979; float: right; padding-top:10px  ">Logged in as <?php echo $_SESSION['Username']; ?>
          <a href="logout.php">Logout</a></li>
    <?php
      }
    ?>
   </p>
    <h1 class="logo"><a href="index.php">Code-Art</a></h1>
    <ul>
     <li><a href="index.php">Home</a></li>
     <li><a href="rules.html">Rules</a></li>
     <li><a class="active" href="leaderboard.php">Leaderboard</a></li>
     <li><a href="http://www.cecsummit.org">SUMMIT'14</a></li>
   </ul>
   
  </div>
 </div>
</div>

<div id="main">
 <div id="content" style = "width:800px:height:1200px;">
 <h1 style = "padding: 20px; font-size:40px; line-height:1em; text-align: center; color: #31a1ff;">Leaderboard</h1>
<table class="container">
	<thead>
		<tr>
			<th><h1>Position</h1></th>
			<th><h1>Name</h1></th>
			<th><h1>Points</h1></th>
			<th><h1>College</h1></th>
		</tr>
	</thead>
	<tbody>

<?php

	$result = mysqli_query($link, "SELECT * FROM users ORDER BY TotalPoints DESC");
	$no_users = mysqli_num_rows($result);
    $rank = 1;
    while($pos = mysqli_fetch_assoc($result))
    
    {
    	
?>
		<tr>
			<td><?php echo $rank++; ?></td>
			<td><?php echo $pos['Username']; ?></td>
			<td><?php echo $pos['TotalPoints']; ?></td>
			<td><?php echo $pos['College']; ?></td>
		</tr>
	</tbody>
<?php
	
}

?>

</table>	
</div>
</div>


<div id="foot">
 <div id="foot_cen">
 <h6><a href="index.php">Code-Art</a></h6>
 
    <p>© 2014. Designed by CEC WebTeam <br>
    Contact the admin at <?php echo $contact;?></p>
 </div>
</div>
</body>
</html>
