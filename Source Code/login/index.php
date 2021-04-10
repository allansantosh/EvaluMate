<html>
<?php
require '../config.php';
//use PHPMailer\PHPMailer\PHPMailer;
//use PHPMailer\PHPMailer\Exception;
session_start();
?>
<body id='body'>
<?php
if(!isset($_SESSION["sess_user"])){
  echo "<div id='navbar'> 
  <ul>
    <li id='link1' style='float:left'><a href='../' ><img style='padding: 0 0 0 10; max-height:30px;' src='../evalumate_logo_white.png'></a></li>
    <li id='link2' style='float:left'><a href='../'>EVALUMATE</a></li>
    <li style='float:right'><a class='active' href='../login'>Login</a></li>
    <li style='float:right'><a href='../register'>Register</a></li>
  </ul></div>
  ";
//echo "<td id='header_right_logged_in'><a href='../register'><button id='register'>Register</button></a></td>";
}
else{
  header("Location: ../");
  //echo "<td id='header_right_logged_out'><a href='upload.php'><button id='upload'>Upload</button></a><a href='logout.php'><button id='logout'>Log Out</button></a><a href='manage.php'><button id='manage'>My Account</button></a></td>";
}
?>
  <br><br><br><br>
  <h1>Account Login</h1><br><br>
  <form id="loginform" method="post" action="">
  <label>Username:</label><br>
  <input name="user" type="text"></input><br><br>
  <label>Password:</label><br>
  <input name="pass" type="password"></input><br><br>
  <?php
  if(isset($_POST["submit"])){

  if(!empty($_POST['user']) && !empty($_POST['pass'])) {
  	$user=$_POST['user'];
  	$pass=$_POST['pass'];
  	$sql="SELECT * FROM users WHERE username='".$user."' AND password='".$pass."'";
    $result = $conn->query($sql);
  	if($result->num_rows > 0)
  	{
  	while($row = $result->fetch_assoc())
  	{
  	$dbusername=$row['username'];
  	$dbpassword=$row['password'];
    $dbguestid=$row['id'];
    $dbguestname=$row['name'];
    $dbguesttype=$row['type'];
    $dbguestemail=$row['email'];
  	}
  	if($user == $dbusername && $pass == $dbpassword)
  	{
  	session_start();
  	$_SESSION['sess_user']=$user;
    $_SESSION['guestid']=$dbguestid;
    $_SESSION['guesttype']=$dbguesttype;
    $_SESSION['guestname']=$dbguestname;
    $_SESSION['guestemail']=$dbguestemail;
   
    if ($_SESSION['redirecturl'] != null){
    header('Location: ' . $_SESSION['redirecturl'].'');
    header('Location: ../');
    }
    else {
    header('Location: ../');
    }
  	}
  	} else {
  	echo "<label>Invalid username or password!</label><br><br>";
  	}

  } else {
  	echo "<label>All fields are required!</label><br><br>";
  }
  }
  ?>
<input type="submit" value="Login" name="submit"></input> <br>
</form>
</html>
<?php 
echo "</body><br><br><div id='main_footer'><p id='footerinfo'>Evalumate Cloud Project</p><p id='ipinfo'>Server IP : ".file_get_contents( "https://allansantosh.com/cloud_project/getip.php" )."</p></div>";
?>