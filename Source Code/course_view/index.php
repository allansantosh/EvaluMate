<html>
<?php

require "../config.php";
session_start();
if(!isset($_SESSION["sess_user"])){
   header("Location: ../login");
}

else {

    if ($_SESSION['guesttype'] == "student" || $_SESSION['course_id'] == NULL){
        header("Location: ../");
    }

    else {

        echo "<div id='navbar'> 
        <ul>
        <li id='link1' style='float:left'><a href='../' ><img style='padding: 0 0 0 10; max-height:30px;' src='../evalumate_logo_white.png'></a></li>
        <li id='link2' style='float:left'><a href='../'>EVALUMATE</a></li>
        <li style='float:right'><a href='../logout'>Log Out</a></li>
        <li style='float:right'><a href='../stats'>Stats</a></li>
        <li style='float:right'><a   href='../create_assessment'>Create Assessment</a></li>
        <li style='float:right'><a href='../add_course'>Add Course</a></li>
        <li style='float:right'><a class='active' href=''>Course</a></li>
        <li style='float:right'><a href='../'>Dashboard</a></li>
        <li style='float:right; background-color:#253f52;' ><a style='padding: 14 20 13 5'><label style='font-size: 15; font-family:Play;'>".$_SESSION['guestname']."</label><br><label style='font-size: 12; font-family:Play;'>".ucfirst($_SESSION['guesttype'])."</label></a></li>
        <li id='link1' style='float:right; background-color:#253f52;'><img style='padding: 15 15 4 15; max-height:30px;' src='../user_profile.png'></li>
      </ul></div><br><br><br><br>
      ";

      $sql = "SELECT courses.id as id, code, courses.name as name, users.name as proff, users.email FROM courses LEFT JOIN users ON courses.proff = users.id WHERE courses.id = '" . $_SESSION['course_id'] . "'";
      $result=$conn->query($sql);
      if ($result->num_rows > 0) {   
          echo "<div id='dash_quiz_table1' align='center' style='padding-left:50px;'><table max-width: fit-content; align-items: center;'><tr><td style='text-align: right;'><div style='width:100%;max-width:400px;     border-radius: 20px;box-shadow:0 10px 16px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19) !important;'><img style='max-width: 400px;     border-radius: 20px;' id='thumbnailimage'src='../course_poster.jpg'/></div></td>";
        while($row = $result->fetch_assoc()) {
           echo "<td style='padding:20; text-align: left;'><h1>".$row['code']." - ".$row['name']."</h1>";
           echo "</td>";
       }

       $final_score1 = 0.00;
       $num_quizes1 = 0;

     }

     $sql3 = "SELECT quiz.id as id, quiz.name, grades.grade FROM quiz LEFT JOIN grades ON quiz.id = grades.quiz_id WHERE course = ".$_SESSION['course_id'];
     $result3 = $conn->query($sql3);
     if ($result3->num_rows > 0) {   
      while($row = $result3->fetch_assoc()) {
          $num_quizes1 = $num_quizes1 + 1;
          $final_score1 = $final_score1 + $row['grade'];
     }
     $final_score1 = ($final_score1 / ($num_quizes1 * 100)) * 100;
  }

     ?>
<td>
<div id="container">
  <div id="card">
    <div id="box">
      <div id="percent">
      <?php


      if($final_score1 < 50) {
          $stroke_colour = "#ff0b00";
      }
      else if ($final_score1 >=50 && $final_score1 < 65) {
          $stroke_colour = "#ffd600";
      }
      else if ($final_score1 >=65 && $final_score1 < 80) {
          $stroke_colour = "#8cc13e";
      }
      else {
          $stroke_colour = "#00ff43";
      }


        echo "<style>
        
        #card:nth-child(1) #svg #circle:nth-child(2) {
          stroke-dashoffset: calc(440 - (440 *".round($final_score1,2) .") / 100);
          stroke: ".$stroke_colour.";
        }
        </style>
        ";
        ?>
        <svg id = "svg"> 
          <circle id = "circle" cx="70" cy="70" r="70"></circle>
          <circle id = "circle" cx="70" cy="70" r="70"></circle>
        </svg>
        <div id="number">
          <h2><?php echo round($final_score1,2); ?><span>%</span></h2>
        </div>
      </div>
      <h2 id="text">Class Average</h2>
    </div>
</div>
      </div>
      </td>



<?php
echo "</tr></table></div>";


        echo "<br><h1>All Assessments</h1><br>";
        $sql2 = "SELECT * FROM quiz LEFT JOIN (SELECT grades.quiz_id, AVG(grade) as avg FROM grades GROUP BY grades.quiz_id) t1 ON quiz.id = t1.quiz_id where course = ".$_SESSION['course_id'];
        //echo $sql2;
        $result2 = $conn->query($sql2);
        if ($result2->num_rows > 0) {   
            echo"<div id='dash_quiz_table' align='center'><table ><tr><th>Quiz Name</th><th>Class Average</th><th>Edit</th><th>Class Stats</th><th>Grades</th></tr>";
          while($row = $result2->fetch_assoc()) {
              echo "<tr><td>".$row['name']."</td>";
              echo "<td>".round($row['avg'],2)."%</td>";
              echo "<td><form method='POST'><input type='hidden' name='the_id' value=".$row['id']."><input type='hidden' name='quiz_type' value=".$row['quiz_type']."><input id='inner_button' type='submit' name='edit_quiz_details' value='Edit Assessment'></form>";
              echo "<td><form method='POST'><input type='hidden' name='the_id' value=".$row['id']."><input id='inner_button' type='submit' name='quiz_stats' value='Stats'></form></td>";
              if($row['quiz_type'] == '3' ) {
                echo "<td><form method='POST'><input type='hidden' name='the_id' value=".$row['id']."><input id='inner_button' type='submit' name='set_grades' value='Set Grade'></form></tr>";
              }
              else {
                echo "<td><label>Assessment auto graded</label></td></tr>";
              }
              
          }
          echo "</table></div>";
      }
      else{
          echo "<label>You have not added any assessments</label>";
      }



      $sql2 = "SELECT * FROM quiz where course = ".$_SESSION['course_id']. " AND poll_comp = 0 AND quiz_type = 2";
      //echo $sql2;
      $result2 = $conn->query($sql2);
      if ($result2->num_rows > 0) {   
          echo "<br><br><h1>Launch Polls</h1><br>";
          echo"<div id='dash_quiz_table' align='center'><table ><tr><th>Quiz Name</th><th>Option</th></tr>";
        while($row = $result2->fetch_assoc()) {
            echo "<tr><td>".$row['name']."</td>";
            echo "<td><form method='POST'><input type='hidden' name='the_id' value=".$row['id']."><input id='inner_button' type='submit' name='launch_poll' value='Launch Poll'></form></tr>";
  
        }
        echo "</table></div>";
    }
      echo "<br><br>";

      if(isset($_POST["edit_quiz_details"])){
        $_SESSION['quiz_edit_id'] = $_POST['the_id'];
        if($_POST['quiz_type'] == '3'){
          echo "<script type='text/javascript'>location.href = '../edit_file_submission';</script>";
        } 
        else {
          echo "<script type='text/javascript'>location.href = '../edit_quiz';</script>";
        }
     
      }

      if(isset($_POST["launch_poll"])){
        $_SESSION['poll_id'] = $_POST['the_id'];
        $_SESSION['poll_live'] = "yes";
      echo "<script type='text/javascript'>location.href = '../launch_poll';</script>";
      }
      
      if(isset($_POST["quiz_stats"])){
        $_SESSION['quiz_stats_id'] = $_POST['the_id'];
      echo "<script type='text/javascript'>location.href = '../quiz_stats';</script>";
      }

      if(isset($_POST["set_grades"])){
        $_SESSION['quiz_grades_id'] = $_POST['the_id'];
      echo "<script type='text/javascript'>location.href = '../set_grades';</script>";
      }

  }
}
?>
<?php 
echo "</body><br><br><div id='main_footer'><p id='footerinfo'>Evalumate Cloud Project</p><p id='ipinfo'>Server IP : ".file_get_contents( "https://allansantosh.com/cloud_project/getip.php" )."</p></div>";
?>