<html>
<?php

// Main Home Page

require "config2.php";
session_start();
if(!isset($_SESSION["sess_user"])){
   header("Location: login");
}

else {

  $user_id = $_SESSION['guestid'];

    if ($_SESSION['guesttype'] == "student"){

    echo "<div id='navbar'> 
    <ul>
      <li id='link1' style='float:left'><a href='../' ><img style='padding: 0 0 0 10; max-height:30px;' src='../evalumate_logo_white.png'></a></li>
      <li id='link2' style='float:left'><a href='../'>EVALUMATE</a></li>
      <li style='float:right'><a href='logout'>Log Out</a></li>
      <li style='float:right'><a href='enroll'>Enroll</a></li>
      <li style='float:right'><a class='active' href=''>Dashboard</a></li>
      <li style='float:right; background-color:#253f52;' ><a style='padding: 14 20 13 5'><label style='font-size: 15; font-family:Play;'>".$_SESSION['guestname']."</label><br><label style='font-size: 12; font-family:Play;'>".ucfirst($_SESSION['guesttype'])."</label></a></li>
      <li id='link1' style='float:right; background-color:#253f52;'><img style='padding: 15 15 4 15; max-height:30px;' src='../user_profile.png'></li>
    </ul></div>
    ";
    }
    else {
        
    echo "<div id='navbar'> 
    <ul>
      <li id='link1' style='float:left'><a href='../' ><img style='padding: 0 0 0 10; max-height:30px;' src='../evalumate_logo_white.png'></a></li>
      <li id='link2' style='float:left'><a href='../'>EVALUMATE</a></li>
      <li style='float:right'><a href='logout'>Log Out</a></li>
      <li style='float:right'><a href='stats'>Stats</a></li>
      <li style='float:right'><a href='create_assessment'>Create Assessment</a></li>
      <li style='float:right'><a href='add_course'>Add Course</a></li>
      <li style='float:right'><a class='active' href=''>Dashboard</a></li>
      <li style='float:right; background-color:#253f52;' ><a style='padding: 14 20 13 5'><label style='font-size: 15; font-family:Play;'>".$_SESSION['guestname']."</label><br><label style='font-size: 12; font-family:Play;'>".ucfirst($_SESSION['guesttype'])."</label></a></li>
      <li id='link1' style='float:right; background-color:#253f52;'><img style='padding: 15 15 4 15; max-height:30px;' src='../user_profile.png'></li>
      </ul></div>
    ";
    }


?>
<head>
<script>

  <?php 
      if ($_SESSION['guesttype'] == "student"){
        ?>
function startTime() {
  var today = new Date();
  var h = today.getHours();
  var m = today.getMinutes();
  var s = today.getSeconds();
  var ampm = h >= 12 ? 'PM' : 'AM';
  if (h>12) { h = h - 12} 
  m = checkTime(m);
  s = checkTime(s);
  document.getElementById('full_date_time').innerHTML =
  h + ":" + m + ":" + s + " " + ampm;
  var t = setTimeout(startTime, 500);
  document.getElementById('rem_count').innerHTML = parseInt(document.getElementById('all_count').innerHTML) - parseInt(document.getElementById('comp_count').innerHTML);
}
function checkTime(i) {
  if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
  return i;
}

<?php
} 
else {

  ?>

function startTime() {
  var today = new Date();
  var h = today.getHours();
  var m = today.getMinutes();
  var s = today.getSeconds();
  var ampm = h >= 12 ? 'PM' : 'AM';
  if (h>12) { h = h - 12} 
  m = checkTime(m);
  s = checkTime(s);
  document.getElementById('full_date_time').innerHTML =
  h + ":" + m + ":" + s + " " + ampm;
  var t = setTimeout(startTime, 500);
  document.getElementById('rem_count').innerHTML = parseInt(document.getElementById('all_count').innerHTML) - parseInt(document.getElementById('pub_count').innerHTML);
}
function checkTime(i) {
  if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
  return i;
}

  <?php
}


//document.getElementById('rem_count').innerHTML = parseInt(document.getElementById('all_count').innerHTML) - parseInt(document.getElementById('comp_count').innerHTML);
   
?>
   
</script>
</head>
<?php

    echo "<body onload='startTime()'><br><br><br><br>";
    echo "<h1>Welcome " .$_SESSION['guestname']."</h1><br>";

    if ($_SESSION['guesttype'] == "student"){

      $sql_1 = "SELECT COUNT(id) as comp_count FROM student_attempt WHERE user_id = ". $user_id . " AND completed = 1";
      $result_1 = $conn->query($sql_1);
      if ($result_1->num_rows > 0) {  
        
        while($row = $result_1->fetch_assoc()) {
          $comp_count = $row['comp_count'];
        }
      }
      else {
        $comp_count = 0;
      }

      $sql_2 = "SELECT Count(quiz.id) as all_count FROM quiz INNER JOIN student_course_reg ON quiz.course = student_course_reg.course_id WHERE user_id =  $user_id";
      $result_2 = $conn->query($sql_2);
      if ($result_2->num_rows > 0) {  
        
        while($row = $result_2->fetch_assoc()) {
          $all_count = $row['all_count'];
        }
      }
      else {
        $all_count = 0;
      }

    echo "<div id='books_table' align='center'><table style ='table-layout: fixed;'><tr><td style='padding:20px;'>
    
    <div style='width:100%; background: linear-gradient(to top left, #025fa2 0%, #003c66 100%); max-width:400px; padding: 15 0 15 0; border-radius: 20px;box-shadow:0 10px 16px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19) !important;'><h3>Assessments Remaining</h3><h1><div id='rem_count'></div></h2>
    </div>
    </td><td style='padding:20px;'>
    
    <div style='width:100%;max-width:400px; background: linear-gradient(to top right, #025fa2 0%, #003c66 100%); padding: 15 0 15 0; border-radius: 20px;box-shadow:0 10px 16px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19) !important;'><h3>Assessments Completed</h3><h1><div id='comp_count'>$comp_count</div></h1>
    </div>
    </td><td style='padding:20px;'>
    <div style='width:100%;background: linear-gradient(to bottom right, #025fa2 0%, #003c66 100%);max-width:400px;padding: 15 0 15 0; border-radius: 20px;box-shadow:0 10px 16px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19) !important;'><h3>Total Assessments</h3><h1><div id='all_count'>$all_count</div></h1>
    </div>
    
    </td><td style='padding:20px;'>
    <div style='width:100%; background: linear-gradient(to bottom left, #025fa2 0%, #003c66 100%); max-width:400px; padding: 15 0 15 0; border-radius: 20px;box-shadow:0 10px 16px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19) !important;'><h3>Time</h3><h1><div id='full_date_time'></div></h1>
    </div></td></tr></table></div><br><br>";
    }
else {

  $sql_1 = "SELECT Count(quiz.id) as pub_count FROM quiz WHERE proff =  $user_id AND publish = 1";
  $result_1 = $conn->query($sql_1);
  if ($result_1->num_rows > 0) {  
    
    while($row = $result_1->fetch_assoc()) {
      $pub_count = $row['pub_count'];
    }
  }
  else {
    $pub_count = 0;
  }

  $sql_2 = "SELECT Count(quiz.id) as all_count FROM quiz WHERE proff =  $user_id";
  $result_2 = $conn->query($sql_2);
  if ($result_2->num_rows > 0) {  
    
    while($row = $result_2->fetch_assoc()) {
      $all_count = $row['all_count'];
    }
  }
  else {
    $all_count = 0;
  }

  echo "<div id='books_table' align='center'><table style ='table-layout: fixed;'><tr><td style='padding:20px;'>
    
  <div style='width:100%; background: linear-gradient(to top left, #025fa2 0%, #003c66 100%); max-width:400px; padding: 15 0 15 0; border-radius: 20px;box-shadow:0 10px 16px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19) !important;'><h3>Assessments Not Released</h3><h1><div id='rem_count'></div></h2>
  </div>
  </td><td style='padding:20px;'>
  
  <div style='width:100%;max-width:400px; background: linear-gradient(to top right, #025fa2 0%, #003c66 100%); padding: 15 0 15 0; border-radius: 20px;box-shadow:0 10px 16px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19) !important;'><h3>Assessments Published</h3><h1><div id='pub_count'>$pub_count</div></h1>
  </div>
  </td><td style='padding:20px;'>
  <div style='width:100%;background: linear-gradient(to bottom right, #025fa2 0%, #003c66 100%);max-width:400px;padding: 15 0 15 0; border-radius: 20px;box-shadow:0 10px 16px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19) !important;'><h3>Total Assessments</h3><h1><div id='all_count'>$all_count</div></h1>
  </div>
  
  </td><td style='padding:20px;'>
  <div style='width:100%; background: linear-gradient(to bottom left, #025fa2 0%, #003c66 100%); max-width:400px; padding: 15 0 15 0; border-radius: 20px;box-shadow:0 10px 16px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19) !important;'><h3>Time</h3><h1><div id='full_date_time'></div></h1>
  </div></td></tr></table></div><br><br>";
}
 ?>
<div style='padding-left:90px; padding-right:100px;'>
<a style='z-index:-1;' class="weatherwidget-io" href="https://forecast7.com/en/43d65n79d38/toronto/" data-label_1="TODAY'S" data-label_2="WEATHER" data-font="Play" data-icons="Climacons Animated" data-theme="original" data-basecolor="#024f87" >TORONTO WEATHER</a></div>
<script>
!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src='https://weatherwidget.io/js/widget.min.js';fjs.parentNode.insertBefore(js,fjs);}}(document,'script','weatherwidget-io-js');
</script>

 <?php


    echo "<br><br><br><br><h1 id='courses_label'>Your Courses</h1><br>";
    if ($_SESSION['guesttype'] == "instructor"){
    $sql = "Select * FROM courses WHERE proff = '" . $_SESSION['guestid'] . "'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
      echo "<div id='books_table' align='center'><table style ='table-layout: fixed;'>";
      echo "<tr>";
      $current_row = 1;
      while($row = $result->fetch_assoc()) {
        if (fmod($current_row, 3) == 0) {
        //  echo "<td><div>";
         // echo "<br><img style='max-width: 80%;' id='thumbnailimage'src='course_poster.jpg'/><br>";
        //  echo "<br><label>".$row["code"] . " - " . $row["name"]."</label>";
        echo "<td>";
          echo "<form method='POST'><input type='hidden' name='the_id' value=".$row['id']."><input id='image_button' type='submit' name='course_submit_btn' value=''></form>";
          echo "<div style='pointer-events: none; display:inline-block; justify-content: center; position:relative;
          align-items: center; border-radius: 20px; padding-top: 66px; padding-bottom: 10px;text-align:center; background: linear-gradient(to bottom, rgba(0, 0, 0, 0) 0%, rgba(0, 0, 0, 1) 100%); width: 95%; top: -114px;'>".$row['code']." - ".$row['name']."</div>";
          echo "<div></div></td></tr><tr>";
        }
        else { //<a style='text-decoration:none' href='watch.php?video=".$row['code']."'>
        echo "<td>";
        //echo "<br><img style='max-width: 80%;' id='thumbnailimage'src='course_poster.jpg'/><br>";
        //echo "<br><label>".$row["code"] . " - " . $row["name"]."</label>";
        echo "<form method='POST' style=''><input type='hidden' name='the_id' value=".$row['id']."><input id='image_button' type='submit' name='course_submit_btn' value=''></form>";
        echo "<div style='pointer-events: none;  display:inline-block; justify-content: center; position:relative;
        align-items: center; border-radius: 20px; padding-top: 66px; padding-bottom: 10px; text-align:center; background: linear-gradient(to bottom, rgba(0, 0, 0, 0) 0%, rgba(0, 0, 0, 1) 100%); width: 95%; top: -114px;'>".$row['code']." - ".$row['name']."</div>";
        echo "</div></div></td>";
        }
        $current_row = $current_row + 1;
      }
      echo "</tr></table></div><br>";
      }
     else {
      echo "<label>You have no courses to display!</label></br></br>";
    }
}

else{

  $sql = "Select courses.id, courses.name, courses.code FROM courses LEFT JOIN student_course_reg ON courses.id = student_course_reg.course_id WHERE user_id = '$user_id'";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
    echo "<div id='books_table' align='center'><table style ='table-layout: fixed;'>";
    echo "<tr>";
    $current_row = 1;
    while($row = $result->fetch_assoc()) {
      if (fmod($current_row, 3) == 0) {
        //  echo "<td><div>";
         // echo "<br><img style='max-width: 80%;' id='thumbnailimage'src='course_poster.jpg'/><br>";
        //  echo "<br><label>".$row["code"] . " - " . $row["name"]."</label>";
        echo "<td>";
          echo "<form method='POST'><input type='hidden' name='the_id' value=".$row['id']."><input id='image_button' type='submit' name='course_submit_btn' value=''></form>";
          echo "<div style='pointer-events: none;  display:inline-block; justify-content: center; position:relative;
        align-items: center; border-radius: 20px; padding-top: 66px; padding-bottom: 10px; text-align:center; background: linear-gradient(to bottom, rgba(0, 0, 0, 0) 0%, rgba(0, 0, 0, 1) 100%); width: 95%; top: -114px;'>".$row['code']." - ".$row['name']."</div>";
        echo "<div></div></td></tr><tr>";
        }
        else { //<a style='text-decoration:none' href='watch.php?video=".$row['code']."'>
        echo "<td>";
        //echo "<br><img style='max-width: 80%;' id='thumbnailimage'src='course_poster.jpg'/><br>";
        //echo "<br><label>".$row["code"] . " - " . $row["name"]."</label>";
        echo "<form method='POST'><input type='hidden' name='the_id' value=".$row['id']."><input id='image_button' type='submit' name='course_submit_btn' value=''></form>";
        echo "<div style='pointer-events: none;  display:inline-block; justify-content: center; position:relative;
        align-items: center; border-radius: 20px; padding-top: 66px; padding-bottom: 10px; text-align:center; background: linear-gradient(to bottom, rgba(0, 0, 0, 0) 0%, rgba(0, 0, 0, 1) 100%); width: 95%; top: -114px;'>".$row['code']." - ".$row['name']."</div>";
        echo "</div></div></td>";
        }
        $current_row = $current_row + 1;
      }
      echo "</tr></table></div><br>";
      }
     else {
      echo "<label>You have no courses to display!</label></br></br>";
    }


}

echo "<h1 id='courses_label'>Assessments</h1><br>";
if ($_SESSION['guesttype'] == "instructor"){
$sql = "Select quiz.name as v1, courses.code as v2, courses.name as v3, quiz.publish as v4, quiz.due as v5 FROM quiz LEFT JOIN courses ON quiz.course = courses.id WHERE quiz.proff = '" . $_SESSION['guestid'] . "'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
  echo "<div id='dash_quiz_table' align='center'><table>";
  echo "<tr><th>Assessment Name</th><th>Course</th><th>Course Name</th><th>Publish</th><th>Due Date</th></tr>";   
  while($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>".$row['v1']."</td>";
    echo "<td>".$row['v2']."</td>";
    echo "<td>".$row['v3']."</td>";
    if ($row['v4'] == '0'){
        echo "<td><img style='max-height: 25px;' src='xmark.png' </td>";
    }
    else {
        echo "<td><img style='max-height: 25px;' src='check.png' </td>";
    }
    echo "<td>".$row['v5']."</td></tr>";
    //echo "<form method='POST'><input type='hidden' name='the_id' value=".$row['id']."><input id='inner_button' type='submit' name='course_submit_btn' value='View Course'></form>";
  }
  echo "</table></div><br>";
  }
 else {
  echo "<label>You have no assessments to display!</label></br></br>";
}
}

else{

 // $sql = "SELECT quiz.id, quiz.name as qn, courses.code, courses.name as cn, quiz.duration, quiz.due
  //FROM quiz LEFT JOIN courses ON quiz.course = courses.id LEFT JOIN student_course_reg 
// ON courses.id = student_course_reg.course_id WHERE student_course_reg.user_id = $user_id and quiz.publish = 1";
  $sql = "SELECT t1.id as id, qn , t1.code, cn as cn, t1.duration, t1.due, t1.quiz_type FROM 
  (SELECT quiz.id, quiz.name as qn, courses.code, courses.name as cn, quiz.duration, quiz.due, quiz.quiz_type
  FROM quiz LEFT JOIN courses ON quiz.course = courses.id LEFT JOIN student_course_reg 
 ON courses.id = student_course_reg.course_id WHERE student_course_reg.user_id = $user_id and quiz.publish = 1) as t1 LEFT JOIN student_attempt ON t1.id = student_attempt.quiz_id AND
 student_attempt.user_id = $user_id WHERE completed is NULL OR completed = 0 ";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
    echo "<div id='dash_quiz_table' align='center'><table>";
    echo "<tr><th>Assessment Name</th><th>Course</th><th>Course Name</th><th>Duration</th><th>Due Date</th><th>Option</th></tr>";   
    while($row = $result->fetch_assoc()) {
      echo "<tr>";
      echo "<td>".$row['qn']."</td>";
      echo "<td>".$row['code']."</td>";
      echo "<td>".$row['cn']."</td>";
      echo "<td>".$row['duration']." mins</td>";
      echo "<td>".$row['due']."</td>";
      echo "<td><form method='POST'><input type='hidden' name='the_id' value=".$row['id']."><input type='hidden' name='quiz_type' value=".$row['quiz_type']."><input id='inner_button' type='submit' name='quiz_select_submit_btn' value='Start Assessment'></form></td></tr>";
    }
    echo "</table></div><br>";
    }
   else {
    echo "<label>You have no assessments to display!</label><br><br>";
  }


  if(isset($_POST['quiz_select_submit_btn']))
{
  //Jan 5, 2022 15:37:25
  $date = date_create();
  $date = date_timestamp_get($date);
  //$date =  date_format($date,"M d, Y H:i:s");
  $sql = "SELECT * FROM student_attempt WHERE quiz_id = '".$_POST['the_id']."' AND user_id = '".$_SESSION['guestid']."'";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
  //  echo "<label>SDvsdvsdv</label>" ; 
  }
  else{
     $sql2 = "INSERT INTO student_attempt(id,quiz_id,start_time,completed,user_id) VALUES(NULL,'".$_POST['the_id']."','".$date."',0,'".$_SESSION['guestid']."')";
     $result2 = $conn->query($sql2);
     echo $sql2;
  }
  
   ///header("Location: quiz");

  if ($_POST['quiz_type'] == 1) {
   $_SESSION['quiz_id'] = $_POST['the_id'];
   echo "<script type='text/javascript'>location.href = '../quiz';</script>";
  }

 else  if ($_POST['quiz_type'] == 2) {
    $_SESSION['poll_id'] = $_POST['the_id'];
    echo "<script type='text/javascript'>location.href = '../poll';</script>";
   }

  else{
    $_SESSION['file_sub_id'] = $_POST['the_id'];
    echo "<script type='text/javascript'>location.href = '../file_submission';</script>";
  }
   //exit();
}

}

}
if(isset($_POST['course_submit_btn']))
{
  if ($_SESSION['guesttype'] == "student"){
    echo "<script>window.location.replace('../course_details');</script>";
   $_SESSION['course_id'] = $_POST['the_id'];
   exit();

  }
  else {
    //header("Location: course_view");
    $_SESSION['course_id'] = $_POST['the_id'];
    echo "<script>window.location.replace('../course_view');</script>";
   // exit();


  }
}


echo "<br>";
?>
<?php 
echo "</body><br><br><div id='main_footer'><p id='footerinfo'>Evalumate Cloud Project</p><p id='ipinfo'>Server IP : ".file_get_contents( "https://allansantosh.com/cloud_project/getip.php" )."</p></div>";
?>