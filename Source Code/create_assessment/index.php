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
        <li style='float:right'><a href='../logout'>Log Out</a></li>
        <li style='float:right'><a href='../stats'>Stats</a></li>
        <li style='float:right'><a class='active'  href=''>Create Assessment</a></li>
        <li style='float:right'><a href='../add_course'>Add Course</a></li>
        <li style='float:right'><a href='../course_view'>Course</a></li>
        <li style='float:right'><a href='../'>Dashboard</a></li>
        <li style='float:right; background-color:#253f52;' ><a style='padding: 14 20 13 5'><label style='font-size: 15; font-family:Play;'>".$_SESSION['guestname']."</label><br><label style='font-size: 12; font-family:Play;'>".ucfirst($_SESSION['guesttype'])."</label></a></li>
        <li id='link1' style='float:right; background-color:#253f52;'><img style='padding: 15 15 4 15; max-height:30px;' src='../user_profile.png'></li>
      </ul></div>
      ";

      echo "<br><br><br><br>";
      echo "<h1>Create Assessment</h1>";


      $sql = "Select * FROM courses WHERE proff = '" . $_SESSION['guestid'] . "'";
      $result = $conn->query($sql);
      if ($result->num_rows > 0) {    
        echo "<form method='POST'><br><label>Select Course</label><br><select name='course'>";
     
      while($row = $result->fetch_assoc()) {
        echo "<option value='" . $row['id'] . "'>" . $row['code'] . "</option>";
      
      }

      echo "</select><br><br><label>Quiz Name</label><br>";
      echo "<input name='quiz_name' type='text'></input><br><br>";
      echo "<label>Assessment Type</label><br>";
      echo "<select name='type'><option value='1'>Quiz</option><option value='2'>Live Poll</option><option value='3'>File Submission</option></select>";
      echo "<br><br><label>Number of Questions</label><br><select name='num'>";
      echo "<option value='1'>1</option><option value='2'>2</option>";
      echo "<option value='3'>3</option><option value='4'>4</option>";
      echo "<option value='5'>5</option><option value='6'>6</option>";
      echo "<option value='7'>7</option><option value='8'>8</option>";
      echo "<option value='9'>8</option><option value='10'>10</option>";
      //echo "<option value='11'>11</option><option value='12'>12</option>";
      //echo "<option value='13'>13</option><option value='14'>14</option>";
      //echo "<option value='15'>15</option><option value='16'>16</option>";
      //echo "<option value='17'>17</option><option value='18'>18</option>";
      //echo "<option value='19'>19</option><option value='20'>20</option>";

      echo "</select><br><br><label>Publish Quiz?</label><br><select name='pub_stat'><option value='1'>Yes</option><option value='0'>No</option></select><br><br>
      <label>Due Date & Time</label><br>
      <input type='date' id='examdate' name='date'
         value='2021-04-22'><br><input type='time' id='appt' name='time'
         value='23:59' required><br><br>";
         echo "<label>Duration</label><br><select name='duration'>";
         echo "<option value='5'>5 mins</option><option value='10'>10 mins</option>";
         echo "<option value='15'>15 mins</option><option value='30'>30 mins</option>";
         echo "<option value='60'>1 hour</option><option value='120'>2 hours</option></select>";
      echo "<br><br><input type='submit' value='Add Questions' name='add_questions'></input></form>";
      
      if(isset($_POST["add_questions"])){
            //header("Location: ../create_quiz");
            $_SESSION['create_assessment_num_ques'] = $_POST['num'];
            $_SESSION['create_assessment_type'] = $_POST['type'];
            $_SESSION['create_assessment_course'] = $_POST['course'];
            $_SESSION['create_assessment_name'] = $_POST['quiz_name'];
            $_SESSION['create_assessment_pub_stat'] = $_POST['pub_stat'];
            $_SESSION['create_assessment_date'] = $_POST['date'] . " " . $_POST['time'];
            $_SESSION['create_assessment_duration'] = $_POST['duration'];

            if ($_SESSION['create_assessment_type'] == 1) {

            echo "<script type='text/javascript'>location.href = '../create_quiz';</script>";

            }

            else if ($_SESSION['create_assessment_type'] == 2){
              echo "<script type='text/javascript'>location.href = '../create_poll';</script>";
            }

            else{
              echo "<script type='text/javascript'>location.href = '../create_file_submission';</script>";
            }
      }

    }
      else {
        header("Location: ../");
      }

    }
}
?>

<?php 
echo "</body><br><br><div id='main_footer'><p id='footerinfo'>Evalumate Cloud Project</p><p id='ipinfo'>Server IP : ".file_get_contents( "https://allansantosh.com/cloud_project/getip.php" )."</p></div>";
?>