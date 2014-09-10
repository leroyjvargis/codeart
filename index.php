<?php include "base.php"; ?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Code-Art | SUMMIT'14 | College of Engg Chengannur</title>

<link href="css/styles.css" rel="stylesheet" type="text/css" media="all" />
</head>

<body>
<div id="head">
 <div id="head_cen">
  <div id="head_sup" class="head_height">
 
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

    <h1 class="logo"><a href="index.php">CODE-ART</a></h1>
    <ul>
     <li><a class="active" href="index.php">Home</a></li>
     <li><a href="rules.html">Rules</a></li>
     <li><a href="leaderboard.php">Leaderboard</a></li>
     <li><a href="http://www.cecsummit.org">SUMMIT'14</a></li>
   </ul>

   <div style = "padding-top: 270px; width: 800px;">
   <h4 class = "title"> Welcome to Code-Art - an online coding competition in association with SUMMIT'14. </h4>
   </div>
  
  </div>
 </div>
</div>

<div id="content">
 <div id="content_cen">
  <div id="content_sup">

 
<?php

if(!empty($_SESSION['LoggedIn']) && !empty($_SESSION['Username']))
{
        $username = $_SESSION['Username'];
        $user = mysqli_query($link, "SELECT UserID FROM users WHERE Username = '".$username."'");
        $userID = mysqli_fetch_array($user);
        $userIDn = $userID['UserID'];

        $no_answered = 0;
        $r1_check = mysqli_query($link, "SELECT * FROM round1 WHERE UserID = '".$userIDn."'");
        $r2_check = mysqli_query($link, "SELECT * FROM round2 WHERE UserID = '".$userIDn."'");
        $r3_check = mysqli_query($link, "SELECT * FROM round3 WHERE UserID = '".$userIDn."'");
        $r4_check = mysqli_query($link, "SELECT * FROM round4 WHERE UserID = '".$userIDn."'");

        if(mysqli_num_rows($r1_check) == 1)
          $no_answered++;
        if(mysqli_num_rows($r2_check) == 1)
          $no_answered++;
        if(mysqli_num_rows($r3_check) == 1)
          $no_answered++;
        if(mysqli_num_rows($r4_check) == 1)
          $no_answered++;
    
        $points_r1_all = mysqli_fetch_array($r1_check);
        $points_r1 = $points_r1_all['Points'];

        $points_r2_all = mysqli_fetch_array($r2_check);
        $points_r2 = $points_r2_all['Points'];

        $points_r3_all = mysqli_fetch_array($r3_check);
        $points_r3 = $points_r3_all['Points'];

        $points_r4_all = mysqli_fetch_array($r4_check);
        $points_r4 = $points_r4_all['Points'];

        $totpoints = $points_r1 + $points_r2 + $points_r3 + $points_r4;

        mysqli_query($link, "UPDATE users SET TotalPoints = '$totpoints' WHERE UserID = '$userIDn'");
        
        $result = mysqli_query($link, "SELECT * FROM users ORDER BY TotalPoints DESC");

        $no_users = mysqli_num_rows($result);
        
       
        $rank = 1;
      
        while($pos = mysqli_fetch_assoc($result)) 
        {
          if ($username == $pos['Username'])
            break;
          else 
            $rank++;
        }  

        $d1=strtotime("September 25");
        $date=ceil(($d1-time())/60/60/24);

  

       
?>

  <div id="ct_pan">
    <p>Welcome <span><?php echo $username; ?></span>. Here are your stats at Code-Art. </p>
    
    <div id="pricing-table" class="clear">
    
     <div class="plan">
        <h3>Answered<span><?php echo $no_answered; ?>/4</span></h3>
     </div>
    
     <div class="plan">
        <h3>Points<span><?php echo round($totpoints, 2);?></span></h3>
     </div>
 
     <div class="plan">
        <h3>Position<span><?php echo $rank;?> </span></h3>
     </div>
    
     <div class="plan">
        <h3 style = "letter-spacing: -1px; margin: -20px -20px 20px -10px;">Days Remaining<span><?php echo $date;?></span></h3>
     </div>  
    </div>
  </div>

     <ul id="infoPan">
    <li>
     <h3><span>round</span> one <img src="images/icon1.png" alt="" /></h3>
     <p>This round tests your aptitude in programming. Programmer’s freedom is maximum as any programming language is allowed for this round. </p>
     <p class="descrip">Points will be based on the time of submission and the output as well. If you believe that you have a little interest in programming then you should try this out. Believe us! This will awaken the programmer in you.</p>
    </li>
    <li>
     <h3><span>round</span> two <img src="images/icon2.png" alt="" /></h3>
     <p>Round two will be more difficult. Cook the code to produce delicious outputs. Rules are the same as round 1 with maximum freedom of language. </p>
     <p class="descrip">you will easily clear this with some logical thinking and decent programming skills. You are now heading through the middle of Code-Art. Points in this round is also based on the output and the time of submission. Just keep in mind that Coding is in fact an ART. Things getting a bit serious …eh??</p>
    </li>
    <li>
     <h3><span>round</span> three <img src="images/icon3.png" alt="" /></h3>
     <p>This round enables you to use programming skills in you to the maximum. With the level counting up, the difficulty too increases</p>
     <p class="descrip">You are now heading to the final parts of Code Art. You are restricted to use only C and C++ to cook code. The points will be based on output, time for execution, memory usage and efficiency. Now, you will be knocking door to the final round of Code Art.</p>
    </li>
    <li>
     <h3><span>round</span> four <img src="images/icon1.png" alt="" /></h3>
     <p>The final step for your success, the final round of Code Art. This round will test your logic, programming skills, efficiency and much more.</p>
     <p class="descrip">Difficulty level will be comparatively high, but still simple, interesting and fun filled. This round helps you to increase your thinking capability, aptitude and creativity. This can be a turning point for you and a crucial one. Again, you are restricted to use only C and C++ for solving the problem.</p>
    </li>
   </ul>
  
     <div id="quotPan">
         <h3 style = "width:848px; text-align: center">The Questions for <span> Code-Art </span> are right <a href = "landing.php">here</a>!</h3>

 <?php
}
elseif(!empty($_POST['username']) && !empty($_POST['password']))
{
    $username = mysqli_real_escape_string($link, $_POST['username']);
    $password = md5(mysqli_real_escape_string($link, $_POST['password']));
     
    $checklogin = mysqli_query($link, "SELECT * FROM users WHERE Username = '".$username."' AND Password = '".$password."'");
     
    if(mysqli_num_rows($checklogin) == 1)
    {
        $row = mysqli_fetch_array($checklogin);
        $email = $row['EmailAddress'];
         
        $_SESSION['Username'] = $username;
        $_SESSION['EmailAddress'] = $email;
        $_SESSION['LoggedIn'] = 1;
      
      ?>   
        <div id="quotPan">
         <h3 style = "width:473px"><span>successfully logged in to </span>Code-Art</h3>
         <br>
        <p>Redirecting to member login page... </p>
      
        <meta http-equiv="refresh" content="2;index.php">
      <?php
    }
    else
    {
        echo "<h1>Error</h1>";
        echo "<p>Sorry, your account could not be found. Please <a href=\"index.php\">click here to try again</a>.</p>";
    }
}
else
{
    ?>
    <div id="ct_pan">
    <p>Log in to <span>Code-Art</span> to see your stats and start playing.</p>
   </div>
   
   <div id="quotPan">
    <h3><span>login to </span>Code-Art</h3>
    <form method="post" action="index.php" name="loginform" id="loginform">
   
    <input name="username" type="text" placeholder="your name" class="txt" />
    <input name="password" type="password" placeholder="password" class="txt" />
    <input name="submit" type="submit" class="btn" value="submit" />
    </form>

    <br>
    <p>Thanks for visiting! Please either login, or <a href="register.php">click here to register</a>.</p>
   </div>
   
   <?php
}

?>

     
  
   
  </div>
 </div>
</div>

<div id="foot">
 <div id="foot_cen">
 <h6><a href="index.php">CODE-ART</a></h6>
 
    <p>© 2014. Designed by CEC WebTeam</p>
 </div>
</div>
</body>
</html>
