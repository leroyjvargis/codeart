<?php include "base.php"; ?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Register for Code-Art | SUMMIT'14 | College of Engg Chengannur</title>

<link href="css/styles.css" rel="stylesheet" type="text/css" media="all" />
</head>


<body>  
<div id="head">
 <div id="head_cen">
  <div id="head_sup" class="head_pad">
   <p class="search">
       </p>
    <h1 class="logo"><a href="index.php">Code-Art</a></h1>
    <ul>
     <li><a href="index.php">Home</a></li>
     <li><a href="rules.html">Rules</a></li>
     <li><a href="leaderboard.php">Leaderboard</a></li>
     <li><a href="http://www.cecsummit.org">SUMMIT'14</a></li>
   </ul>
   
  </div>
 </div>
</div>

<div id="main">
<?php
if(!empty($_POST['username']) && !empty($_POST['password'])  && !empty($_POST['email'])  && !empty($_POST['college'])  && !empty($_POST['phone']))
{
    $username = mysqli_real_escape_string($link, $_POST['username']);
    $password = md5(mysqli_real_escape_string($link, $_POST['password']));
    $email = mysqli_real_escape_string($link, $_POST['email']);
    $college = mysqli_real_escape_string($link, $_POST['college']);
    $phone = mysqli_real_escape_string($link, $_POST['phone']);
     
     $checkusername = mysqli_query($link, "SELECT * FROM users WHERE Username = '".$username."'");
      
     if(mysqli_num_rows($checkusername) == 1)
     {
        echo "<h1>Error</h1>";
        echo "<p>Sorry, that username is taken. Please go back and try again.</p>";
     }
     else
     {
        log_data($username, "Registered");
        $registerquery = mysqli_query($link, "INSERT INTO users (Username, Password, EmailAddress, College, Phone) VALUES('".$username."', '".$password."', '".$email."', '".$college."', '".$phone."')");
        if($registerquery)
        {
            echo "<h1>Success</h1>";
            echo "<p>Your account was successfully created. Please <a href=\"index.php\">click here to login</a>.</p>";
        }
        else
        {
            echo "<h1>Error</h1>";
            echo "<p>Sorry, your registration failed. Please go back and try again.</p>";    
        }       
     }
}
else
{
    ?>
     
     
    <div id="quotPanReg">
    <h3><span>register for </span>Code-Art</h3>
    <p> Enter your details to register: </p>
    <form method="post" action="register.php" name="registerform" id="registerform">
   
    <input name="username" type="text" placeholder="your name" class="txt" />
    <input name="password" type="password" placeholder="password" class="txt" /> 
    <input name="email" type="text" placeholder="your email id" class="ema" />
    <input name="college" type="text" placeholder="your college name" class="ema"  />
    <input name="phone" type="text" placeholder="your phone number" class="ema" />
    <input name="submit" type="submit" class="btn" value="register" />
    </form>
   </div>


    <?php
}
?>
 
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
