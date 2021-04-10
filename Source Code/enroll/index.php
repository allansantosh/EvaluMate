<html>
<?php

require "../config.php";
session_start();
if(!isset($_SESSION["sess_user"])){
   header("Location: ../login");
}

else {

    if ($_SESSION['guesttype'] == "student"){
        
        $user_id = $_SESSION['guestid'];

        echo "<div id='navbar'> 
        <ul>
          <li id='link1' style='float:left'><a href='../' ><img style='padding: 0 0 0 10; max-height:30px;' src='../evalumate_logo_white.png'></a></li>
          <li id='link2' style='float:left'><a href='../'>EVALUMATE</a></li>
          <li style='float:right'><a href='../logout'>Log Out</a></li>
          <li style='float:right'><a class='active' href=''>Enroll</a></li>
          <li style='float:right'><a  href='../'>Dashboard</a></li>
          <li style='float:right; background-color:#253f52;' ><a style='padding: 14 20 13 5'><label style='font-size: 15; font-family:Play;'>".$_SESSION['guestname']."</label><br><label style='font-size: 12; font-family:Play;'>".ucfirst($_SESSION['guesttype'])."</label></a></li>
          <li id='link1' style='float:right; background-color:#253f52;'><img style='padding: 15 15 4 15; max-height:30px;' src='../user_profile.png'></li>
        </ul></div>
        ";
    }

    else {
      header("Location: ../");
    }

}
?>

<br><br><br><br>
<h1>Enroll in a Course</h1><br><br>
  <form id="registerform" method="post" action="">

<?php
  $sql = "SELECT courses.id, courses.code FROM courses LEFT JOIN (SELECT courses.id, code FROM courses LEFT JOIN student_course_reg ON courses.id = student_course_reg.course_id WHERE user_id = '$user_id')  as t1
  ON courses.id = t1.id WHERE t1.id IS NULL";
      $result = $conn->query($sql);
      if ($result->num_rows > 0) {    
        echo "<label>Select Course</label><br><select name='course'>";
     
      while($row = $result->fetch_assoc()) {
        echo "<option value='" . $row['id'] . "'>" . $row['code'] . "</option>";
      
      }

      echo "</select><br><br>";
    }

    else {
        header("Location: ../");
    }

?>
  <?php
  if(isset($_POST["register"])){
    $course_id = $_POST['course'];
    $sql2 = "INSERT into student_course_reg (id,user_id,course_id) VALUES (NULL,$user_id,$course_id)";
    $result2 = $conn->query($sql2);

    if($result2) {
            echo "<label>Successfully enrolled in course!</label><br><br>";
            header("Location: ../enroll");
    }

    else{
        echo "<label>Failed to enroll in course!</label><br><br>";
    }

    }

  ?>
<input type="submit" value="Enroll" name="register"></input>
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