<html>
<?php
require '../config.php';
//use PHPMailer\PHPMailer\PHPMailer;
//use PHPMailer\PHPMailer\Exception;
session_start();
?>
<body id='body'>
<div id='header_table' align='center'>
<?php
if(!isset($_SESSION["sess_user"])){
    echo "<div id='navbar'> 
    <ul>
    <li id='link1' style='float:left'><a href='../' ><img style='padding: 0 0 0 10; max-height:30px;' src='../evalumate_logo_white.png'></a></li>
    <li id='link2' style='float:left'><a href='../'>EVALUMATE</a></li>
    <li style='float:right'><a href='../login'>Login</a></li>
      <li style='float:right'><a class='active' href='../register'>Register</a></li>
      </ul></div>
    ";}
else{
  header("Location: ../");
 // echo "<td id='header_right_logged_out'><a href='upload.php'><button id='upload'>Upload</button></a><a href='logout.php'><button id='logout'>Log Out</button></a><a href='manage.php'><button id='manage'>My Account</button></a></td>";
}
?>

<br><br><br><br>
<h1>Account Registration</h1><br><br>
  <form id="registerform" method="post" action="">
  <label>Enter Full Name:</label><br>
  <input type="text" name="fullname"></input><br><br>
  <label>Enter Username:</label><br>
  <input type="text" name="user"></input><br><br>
  <label>Select Role:</label><br>
  <input type="radio" checked="checked" id="student" name="role" value="student">
  <label for="student">Student</label><br>
  <input type="radio" id="instructor" name="role" value="instructor">
  <label for="instructor">Instructor</label><br><br>
  <label>Enter Password:</label><br>
  <input type="password" name="pass"></input><br><br>
  <label>Re-enter Password:</label><br>
  <input type="password" name="pass1"></input><br><br>
  <label>Enter Email:</label><br>
  <input type="text" name="email"></input><br><br>
  <?php
  if(isset($_POST["register"])){

   // Check if all fields filled or not

    if(!empty($_POST['fullname']) && !empty($_POST['user']) && !empty($_POST['pass']) && !empty($_POST['pass1']) && !empty($_POST['email'])) {

    // First name check

    $fullname=$_POST['fullname'];

    if ((preg_match("/^[a-zA-Z ]*$/",$fullname))) {

    // Next check if username exists

    $user=$_POST['user'];

    if (preg_match("/^[a-zA-Z0-9.]*$/",$user)) {
    // Next check if username exists

    $sqla="SELECT * FROM users WHERE username='".$user."'";
    $resulta = $conn->query($sqla);
    if($resulta->num_rows == 0) {

    $pass=$_POST['pass'];
    $pass1=$_POST['pass1'];

    // Check if passwords match

    if ($pass == $pass1) {

    $email=$_POST['email'];
    $date=date("Y-m-d");

    // Check if it is a valid Email

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

            
    $sqld="SELECT * FROM users WHERE email='".$email."'";
    $resultd = $conn->query($sqld);
    if($resultd->num_rows == 0) {

    $type = $_POST['role'];

    $sqlb="INSERT INTO users(id,name,username,password,email,type) VALUES(NULL,'$fullname','$user','$pass','$email','$type')";
	  $resultb=$conn->query($sqlb);

    if($resultb){
      $email_link = "https://allansantosh.com/cloud_project/register_mail.php?name_mail=".urlencode($fullname)."&user_name_mail=".urlencode($user)."&email_mail=".urlencode($email);
      include( $email_link );
    echo "<label>Account successfully created. Redirecting you to login page in 5 seconds.</label><br><br>";
   //header('Refresh: 5; URL=/login');
  	}
    else {
    echo "<label>Failed to register user</label><br><br>";
    }}
    else {
        echo "<label>Please try a different email</label></br></br>";
        }}
    else {
    echo "<label>Please enter a valid email</label></br></br>";
    }}
    else {
    echo "<label>Passwords do not match</label><br><br>";
    }}
    else {
    echo "<label>Please try a different usename</label></br></br>";
    }}
    else {
    echo "<label>Please enter a proper username</label></br></br>";
    }}
    else {
    echo "<label>Please enter a valid name</label></br></br>";
    }}
    else {
  	echo "<label>All fields are required</label><br><br>";
    }}
  ?>
<input type="submit" value="Register" name="register"></input>
</form>
<script>
window.onscroll = function() {myFunction()};

var navbar = document.getElementById("navbar");
var sticky = navbar.offsetTop;

function myFunction() {
  if (window.pageYOffset >= sticky) {
    navbar.classList.add("sticky")
  } else {
    navbar.classList.remove("sticky");
  }
}
    </script>
</html>
<?php 
echo "</body><br><br><div id='main_footer'><p id='footerinfo'>Evalumate Cloud Project</p><p id='ipinfo'>Server IP : ".file_get_contents( "https://allansantosh.com/cloud_project/getip.php" )."</p></div>";
?>