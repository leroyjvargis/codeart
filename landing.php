<?php include "base.php"; ?>

<!DOCTYPE html>
<html>
<link href="css/bootstrap.css" rel="stylesheet" type="text/css" media="all" />
<link href="css/styles.css" rel = "stylesheet" type = "text/css" media = "all" />


<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="http://netdna.bootstrapcdn.com/bootstrap/3.0.3/js/bootstrap.min.js"></script>
<script src="http://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
<script src = "js/bootbox.min.js"></script>


<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Code-Art | SUMMIT'14 | College of Engineering Chengannur</title>
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
          <a href="logout.php">Logout</a></li></p>
    <?php
      }
    ?>
   </p>
    <h1 class="logo"><a href="index.php">Code-Art</a></h1>
    <ul style = "margin-right: -50px; width:500px">
     <li><a href="index.php">Home</a></li>
     <li><a href="rules.html">Rules</a></li>
     <li><a href="leaderboard.php">Leaderboard</a></li>
     <li><a href="http://www.cecsummit.org">SUMMIT'14</a></li>
   </ul>
   
  </div>
 </div>
</div>

<div id="main">
 <div id="content" style = "width:800px: height:1200px;">
  <div id="content_cen" style = "height: 900px;">
  
<?php

if(isset($_POST["code"]) && isset($_POST["lang"]) )
{
    $code_data = $_POST["code"];
    //$code_data = stripslashes($code_data);
    $lang = $_POST["lang"];
    $rno = $_POST["rno"];
    $d1 = strtotime("September 9");
    $days = ceil(($d1-time())/60/60/24);

    if($rno === '1')
    {
      $input = "CollegeofEngineeringChengannur 3 college techfest SUMMIT";
      $output_expected = "30";
    }
    else if($rno === '2')
    {
       $input = "4
                 4
                 16 24 18 6
                 3
                 32 45 6
                 3
                 48 90 54
                 4
                 212 444 368 526";
      $output_expected = "50
                          56
                          98
                          320";
    }

    $service_url = 'http://api.hackerearth.com/code/run/';
    $client_id = ''; //your client secret id here

    $curl = curl_init($service_url);

    $curl_post_data = array(
        'client_secret' => $client_id,
        'async' => 0,
        'source' => $code_data,
        'lang' => $lang,
        'input' => $input,
        'time_limit' => 10,
        'memory_limit' => 262144
    );



    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $curl_post_data);
    $curl_response = curl_exec($curl);

    if ($curl_response === false) {
        $info = curl_getinfo($curl);
        curl_close($curl);
        die('error occured during curl exec. Additioanl info: ' . var_export($info));
    }
    curl_close($curl);

    $decoded = json_decode($curl_response, true);
    if (isset($decoded->response->status) && $decoded->response->status == 'ERROR') {
        die('error occured: ' . $decoded->response->errormessage);    }

    $run_status = $decoded['compile_status'];

    if($run_status === 'OK')
    {
        $time_used = $decoded['run_status']['time_used'];
        $memory_used = $decoded['run_status']['memory_used'];
        $output = $decoded['run_status']['output'];
        $username = $_SESSION['Username'];
        $user = mysqli_query($link, "SELECT UserID FROM users WHERE Username = '".$username."'");
        $userID = mysqli_fetch_array($user);
        $userIDn = $userID['UserID'];

  $table = "round" . $rno;

$output = preg_replace('/\s+/', '', $output);
$output_expected = preg_replace('/\s+/', '', $output_expected);


if($output == $output_expected)
  
{
  $points_scored = 30 - ($time_used * 10) - abs($days);
  $checkuser = mysqli_query($link, "SELECT * FROM `".$table."` WHERE UserID = '".$userIDn."'");
  if(!mysqli_num_rows($checkuser))
    {
       if(mysqli_query($link, "INSERT INTO `".$table."` (UserID, TimeTaken, MemoryUsed, Output, Language, Code, Points, Submissions)
           VALUES ('$userIDn', '$time_used', '$memory_used', '$output', '$lang', '$code_data', '$points_scored', 1)"))
       {
?><script>
          var time = <?php echo json_encode($time_used); ?>;
          var mem = <?php echo json_encode($memory_used); ?>;
          bootbox.dialog({
           message: "Your code has been compiled successfully and submitted.\n Time Taken: " + time + "\n Memory Used: " + mem,
           title: "Success!",
           buttons: {
              main: {
              label: "OK",
              className: "btn-success"    }
            }
         });
</script><?php    


      }
       else
       {
?><script>
          bootbox.dialog({
           message: "Your code has been compiled successfully, but we are having problems connecting to the server.
           Please try again. Sorry for the inconvenience.",
           title: "Error!",
           buttons: {
              main: {
              label: "Close",
              className: "btn-danger"    }
            }
          });
</script><?php
       }
    }
  else
    {
       if(mysqli_query($link, "UPDATE `".$table."` 
          SET TimeTaken = $time_used, MemoryUsed = $memory_used, Submissions = (Submissions + 1), Code = '$code_data', Points = '$points_scored', Language = '$lang', Output = '$output'
            WHERE UserID = '$userIDn'"))
          {
?><script>
          var time = <?php echo json_encode($time_used); ?>;
          var mem = <?php echo json_encode($memory_used); ?>;
          bootbox.dialog({
           message: "Your code has been compiled successfully and submitted.\n Time Taken: " + time + "\n Memory Used: " + mem,
           title: "Success!",
           buttons: {
              main: {
              label: "OK",
              className: "btn-success"    }
          }
        });
</script><?php    
          }
          else
          {
?><script>
          bootbox.dialog({
           message: "Your code has been compiled successfully, but we are having problems connecting to the server.
           Please try again. Sorry for the inconvenience.",
           title: "Error!",
           buttons: {
              main: {
              label: "Close",
              className: "btn-danger"    }
                  }
          });
</script><?php
          }

    }
}
else
{
  $points_scored = 0;
  ?><script>
          bootbox.dialog({
           message: "Your code has compiled successfully, but the output obtained did not match what we expected. Please try again. If you're sure your code is correct, contact the admin.",
           title: "Error!",
           buttons: {
              main: {
              label: "OK",
              className: "btn-danger"    }
                  }
          });
</script><?php
}
}
  else
   {    
          ?><script>
          var data = <?php echo json_encode($run_status); ?>;
          bootbox.dialog({
          message: data,
          title: "Compilation Error",
          buttons: {
              main: {
              label: "OK",
              className: "btn-danger"    }
          }
        });
          </script><?php    
   }
}        
   

if(!empty($_SESSION['LoggedIn']) && !empty($_SESSION['Username']))
{
?>

<ul id="myTab" class="nav nav-tabs">
   <li class="active"><a href="#round1" data-toggle="tab">round <span>one</span></a></li>
   <li><a href="#round2" data-toggle="tab">round <span>two</span></a></li>
   <li><a href="#round3" data-toggle="tab">round <span>three</span></a></li>
   <li><a href="#round4" data-toggle="tab">round <span>four</span></a></li>
   
      </ul>
   </li>
</ul>
<div id="myTabContent" class="tab-content">
   <div class="tab-pane fade in active" id="round1">
        <br><br>
        <p style = "font-family:"Trebuchet MS", Arial, Helvetica, sans-serif; font-size:14px; color:#4d4d4d;">
        Given a string S of length l, and a set S of n sample string(s).</p>
        <p>We can reduce the string s using the set S by this way:
        <p>&nbsp;&nbsp;&nbsp;Wherever S(i) appears as a consecutive substring of the string s, you can delete (or not) it.</p>
        <p>&nbsp;After each deletion, you will get a new string S by joining the part to the left and to the right of the deleted substring.</p>
        <br>
        <p><b>Input:</b></p>
        <p>&nbsp;&nbsp;The first line contains the string S</p>
        <p>&nbsp;&nbsp;The second line contains the integer n</p>
        <p>&nbsp;&nbsp;Within the last n lines, the i-th line contains the string S(i).</p>
        <br>
        <p><b>Output:</b></p>
        <p>&nbsp;&nbsp;Output on a single line an integer which is the minimum length found.</p>

        <p><b>Example:</b> <br><br>
        Input:<br>
        aaabccd<br>3<br>abc<br>ac<br>aaa
        Output:<br>
        2        </p>      

        <?php roundnumber("1"); ?>

   </div>

   <div class="tab-pane fade" id="round2">
   <br><br>
      <p>Given N numbers, you need to tell the number of distinct factors of the product of these N numbers.</p>
      <p><b>Input:</b> <br>
      First line of input contains a single integer T, the number of test cases. <br>
      Each test starts with a line containing a single integer N. <br>
      The next line consists of N space separated integers (Ai). </p><br>

      <p><b>Output:</b> <br>
      For each test case, output on a separate line the total number of factors of the product of given numbers.</p>

      <p><b>Example:</b> <br><br>
      Input:<br>
      3<br>3<br>3 5 7<br>3<br>2 4 6<br>2<br>5 5<br><br>
      Output:<br>
      8<br>10<br>3
      </p>      
 
        <?php roundnumber("2"); ?>

   </div>

   <div class="tab-pane fade" id="round3">
   <br><br>
     <p>Round 3 will be active on September 20. Rounds 1 and 2 are now active.</p>

      <?php// roundnumber("3"); ?>

   </div>

   <div class="tab-pane fade" id="round4">
   <br><br>
      <p>Round 4 will be active on Sep 20, with round 3. Do crack rounds 1 and 2 in the meanwhile.</p>

       <?php// roundnumber("4"); ?>

   </div>
</div>

<script>
   $(function () {
      $('#myTab li:eq(0) a').tab('show');
   });
</script>


<?php 
}
else
{
?><script>
          bootbox.dialog({
           message: "Please Login first.",
           title: "You are not logged in.",
           buttons: {
              main: {
              label: "Close",
              className: "btn-danger"    }
          }
        });
</script><?php
               echo '<meta http-equiv="refresh" content="1;index.php">';
}

function roundnumber ($roundno) 
{
  

 if($roundno == '1')
      $roundn = "one";
   elseif ($roundno === '2')
      $roundn = "two";
   elseif ($roundno === '3')
      $roundn = "three";
   else 
      $roundn = "four";

 echo'    <div id="quotPanReg" style = "width:100%; height:auto;">
    <h3>code for round <span>'.$roundn.'</span></h3>
   
   <form method="post" action="landing.php" name="codeform" id="codeform'.$roundno.'">
      <textarea name = "code" rows = "30" cols = "100" form = "codeform'.$roundno.'" required>
      </textarea>
      <input type="hidden" name="rno" value ="'.$roundno.'"><br> &nbsp&nbsp
      <input type="radio" name="lang" value="C" checked >C &nbsp&nbsp&nbsp
      <input type="radio" name="lang" value="CPP">C++ &nbsp&nbsp&nbsp
      <input type="radio" name="lang" value="PYTHON">Python &nbsp&nbsp&nbsp
      <input type="radio" name="lang" value="RUBY">Ruby &nbsp&nbsp&nbsp
      <input type="radio" name="lang" value="JAVA">Java<br> 
     <input name="submit" type="submit" class="btn" value="submit" />
  </form>
  </div>'; 
}



?>
</div>
</div>
</div>


<div id="foot">
 <div id="foot_cen">
 <h6><a href="index.php">Code-Art</a></h6>
 
    <p>© 2014. Designed by CEC WebTeam</p>
 </div>
</div>
</body>
</html>
