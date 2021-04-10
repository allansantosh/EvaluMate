<html>
<?php

require "../config.php";
session_start();
if(!isset($_SESSION["sess_user"])){
   header("Location: ../login");
}

else {

    if ($_SESSION['guesttype'] == "student"){
        header("Location: ../");
    }

    else {

        echo "<div id='navbar'> 
        <ul>
        <li id='link1' style='float:left'><a href='../' ><img style='padding: 0 0 0 10; max-height:30px;' src='../evalumate_logo_white.png'></a></li>
        <li id='link2' style='float:left'><a href='../'>EVALUMATE</a></li>
        <li style='float:right'><a href='logout'>Log Out</a></li>
        <li style='float:right'><a href='../stats'>Stats</a></li>
        <li style='float:right'><a href='../create_assessment'>Create Assessment</a></li>
        <li style='float:right'><a class='active' href=''>Add Course</a></li>
        <li style='float:right'><a href='../'>Dashboard</a></li>
        <li style='float:right; background-color:#253f52;' ><a style='padding: 14 20 13 5'><label style='font-size: 15; font-family:Play;'>".$_SESSION['guestname']."</label><br><label style='font-size: 12; font-family:Play;'>".ucfirst($_SESSION['guesttype'])."</label></a></li>
        <li id='link1' style='float:right; background-color:#253f52;'><img style='padding: 15 15 4 15; max-height:30px;' src='../user_profile.png'></li>
      
      </ul></div>
      ";
    }

}
?>
<br><br><br><br>
<h1>Add New Course</h1><br><br>
  <form id="registerform" method="post" action="">
  <label>Enter Course Code:</label><br>
  <input type="text" name="course_code"></input><br><br>
  <label>Enter Course Name:</label><br>
  <input type="text" name="course_name"></input><br><br>
  <?php
  if(isset($_POST["register"])){

   // Check if all fields filled or not

    if(!empty($_POST['course_code']) && !empty($_POST['course_name'])){

    // First name check

    $cname=$_POST['course_name'];
    $ccode=$_POST['course_code'];
    

    if ((preg_match("/^[a-zA-Z ]*$/",$cname))) {
    if (preg_match("/^[a-zA-Z0-9.]*$/",$ccode)) {

    // Next check if username exists

  
    // Next check if username exists
    $user_id = $_SESSION['guestid'];

    $sqla="SELECT * FROM courses WHERE code='".$ccode."'";
    $resulta = $conn->query($sqla);
    if($resulta->num_rows == 0) {

    //$sqlb="INSERT INTO courses(id,code,name,proff) VALUES(NULL,'".$code."','".$name."',''".$user_id."')";
    $sqlb="INSERT INTO courses(id,code,name,proff) VALUES('NULL','".$ccode."','".$cname."','".$user_id."')";
	  $resultb=$conn->query($sqlb);

    if($resultb){
    echo "<label>Course successfully added. Redirecting you to dashboard in 5 seconds.</label><br><br>";
    header('Refresh: 5; URL=../');
  	}
    else {
    echo "<label>Failed to register course</label><br><br>";
    }}
    else {
    echo "<label>Please try a different course code</label></br></br>";
    }}
    else {
    echo "<label>Please enter a valid course code</label></br></br>";
    }}
    else {
    echo "<label>Please enter a valid course name</label></br></br>";
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


<?php 
echo "</body><br><br><div id='main_footer'><p id='footerinfo'>Evalumate Cloud Project</p><p id='ipinfo'>Server IP : ".file_get_contents( "https://allansantosh.com/cloud_project/getip.php" )."</p></div>";
?>