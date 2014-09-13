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
        $username = $_SESSION['Username'];
        $user = mysqli_query($link, "SELECT UserID FROM users WHERE Username = '".$username."'");
        $userID = mysqli_fetch_array($user);
        $userIDn = $userID['UserID'];
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
    $d1 = strtotime("September 12");
    $days = ceil(($d1-time())/60/60/24);

    if($rno === '1')
    {
      $input = file_get_contents('questions/r1_q.txt');
      $output_expected = file_get_contents('questions/r1_a.txt');
    }
    else if($rno === '2')
    {
       $input = file_get_contents('questions/r2_q.txt');
      $output_expected = file_get_contents('questions/r2_a.txt');
    }

    $service_url = 'http://api.hackerearth.com/code/run/';
    $client_id = '';

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


  $table = "round" . $rno;

$output = preg_replace('/\s+/', '', $output);
$output_expected = preg_replace('/\s+/', '', $output_expected);


if($output == $output_expected)
  
{
  $details = $lang . " " . $time_used . " " . $memory_used . " " . $output;
  log_data($username, "Correct output", $code_data, $details);
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
  log_data($username, "Wrong output", $code_data);
  ?><script>
          var data = <?php echo json_encode($output); ?>;
          bootbox.dialog({
           message: "Your code has compiled successfully, but the output obtained did not match what we expected. Please try again. If you're sure your code is correct, contact the admin. This is the output we obtained: " + data,
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
          log_data($username, "Compilation error", $code_data);
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
        
          <?php 
          check_submissions("1");
          echo file_get_contents('questions/r1.txt');
          roundnumber("1"); ?>

   </div>

   <div class="tab-pane fade" id="round2">
   
       <?php 
          check_submissions("2");
          echo file_get_contents('questions/r2.txt');
          roundnumber("2"); ?>


   </div>

   <div class="tab-pane fade" id="round3">
  <?php 
          //check_submissions("3");
          echo file_get_contents('questions/r3.txt');
          //roundnumber("1"); ?>

   </div>

   <div class="tab-pane fade" id="round4">
  <?php 
          //check_submissions("3");
          echo file_get_contents('questions/r4.txt');
          //roundnumber("1"); ?>

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

function check_submissions($rndno)
{ 
  global $userIDn;
  global $link;
  $r_table = "round" . $rndno;
  $check_submitted = mysqli_query($link, "SELECT * FROM `".$r_table."` WHERE UserID = '".$userIDn."'");
  if(mysqli_num_rows($check_submitted))
  {
    $round_details = mysqli_fetch_array($check_submitted);
        
  ?>
    <div id="ct_pan">
    <p>You've already <span>successfully attempted</span> this round. Here is your performance. </p>
    
    <div id="pricing-table" class="clear">
    
     <div class="plan" style = "width: 180px;height: 140px">
        <h3>Points<span><?php echo round($round_details['Points'], 2); ?></span></h3>
     </div>
    
     <div class="plan" style = "width: 180px;height: 140px">
        <h3>Language<span><?php echo ucfirst(strtolower($round_details['Language']));?></span></h3>
     </div>
 
     <div class="plan" style = "width: 180px;height: 140px">
        <h3>Submissions<span><?php echo $round_details['Submissions'];?> </span></h3>
     </div>
    
     <div class="plan" style = "width: 180px;height: 140px">
        <h3 style = "letter-spacing: -1px; margin: -20px -20px 20px -10px;">Time Taken<span><?php echo round($round_details['TimeTaken'], 3);?></span></h3>
     </div>  
    </div>
  </div>

  <?php 
  }
}

?>
</div>
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
