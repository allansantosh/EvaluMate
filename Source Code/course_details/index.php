<html>
<?php

// Main Home Page

require "../config.php";
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
          <li style='float:right'><a href='../logout'>Log Out</a></li>
          <li style='float:right'><a href='../enroll'>Enroll</a></li>
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
             echo "<h3>Instructor: ".$row['proff']."</h3>";
             echo "<h3>Email: ".$row['email']."</h3></td>";
         }
         
         $final_score = 0.00;
         $num_quizes = 0;

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

       $sql2 = "SELECT quiz.id as id, quiz.name, grades.grade FROM quiz LEFT JOIN grades ON quiz.id = grades.quiz_id WHERE course = ".$_SESSION['course_id']." and grades.user_id = ".$_SESSION['guestid'] ;
       //echo $sql2;
       $result2 = $conn->query($sql2);
       if ($result2->num_rows > 0) {   
          // echo"<div id='dash_quiz_table' align='center'><table ><tr><th>Quiz Name</th><th>Your Grade</th><th>Class Average</th><th>Option</th></tr>";
          
         while($row = $result2->fetch_assoc()) {
            $num_quizes = $num_quizes + 1;
            $final_score = $final_score + $row['grade'];
         }        
        $final_score = ($final_score / ($num_quizes * 100)) * 100;

     }


       ?>
<td>
<div id="container" >
    <div id="card" style="max-width: 200px;">
      <div id="box">
        <div id="percent">
        <?php


        if($final_score < 50) {
            $stroke_colour = "#ff0b00";
        }
        else if ($final_score >=50 && $final_score < 65) {
            $stroke_colour = "#ffd600";
        }
        else if ($final_score >=65 && $final_score < 80) {
            $stroke_colour = "#8cc13e";
        }
        else {
            $stroke_colour = "#00ff43";
        }


          echo "<style>
          
          #card:nth-child(1) #svg #circle:nth-child(2) {
            stroke-dashoffset: calc(440 - (440 *".round($final_score,2) .") / 100);
            stroke: ".$stroke_colour.";
          }
          </style>
          ";
          ?>
          <svg id='svg'> 
            <circle id="circle" cx="70" cy="70" r="70"></circle>
            <circle id="circle" cx="70" cy="70" r="70"></circle>
          </svg>
          <div id="number">
            <h2><?php echo round($final_score,2); ?><span>%</span></h2>
          </div>
        </div>
        <h2 id="text">Course Grade</h2>
      </div>
    </div>
    <div id="card" style="max-width: 200px;">
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
          
          #card:nth-child(2) #svg #circle:nth-child(2) {
            stroke-dashoffset: calc(440 - (440 *".round($final_score1,2) .") / 100);
            stroke: ".$stroke_colour.";
          }
          </style>
          ";
          ?>
          <svg id='svg'> 
            <circle id="circle" cx="70" cy="70" r="70"></circle>
            <circle id="circle" cx="70" cy="70" r="70"></circle>
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


          echo "<br><h1>Graded Assessments</h1><br>";
          $sql2 = "SELECT quiz.id as id, quiz.name, grades.grade, quiz_type, t1.avg FROM quiz LEFT JOIN grades ON quiz.id = grades.quiz_id LEFT JOIN (SELECT quiz_id, AVG(grade) as avg FROM grades GROUP BY quiz_id)as t1 ON t1.quiz_id = quiz.id WHERE course = ".$_SESSION['course_id']." and grades.user_id = ".$_SESSION['guestid'];
          //echo $sql2;
          $result2 = $conn->query($sql2);
          if ($result2->num_rows > 0) {   
              echo"<div id='dash_quiz_table' align='center'><table ><tr><th>Quiz Name</th><th>Your Grade</th><th>Class Average</th><th>Option</th></tr>";
            while($row = $result2->fetch_assoc()) {
                echo "<tr><td>".$row['name']."</td>";
                echo "<td>".$row['grade']."%</td>";
                echo "<td>".round($row['avg'],2)."%</td>";
                echo "<td><form method='POST'><input type='hidden' name='the_id' value=".$row['id']."><input type='hidden' name='the_quiz_type' value=".$row['quiz_type']."><input id='inner_button' type='submit' name='check_quiz_details' value='Assessment Details'></form></tr>";
      
            }
            echo "</table></div>";
        }
        else{
            echo "<label>You have not completed any assessments</label>";
        }


       echo "<br><br>";

    }

    else {
        header("Location: ../course_view");
    }
}

if(isset($_POST['check_quiz_details']))
{
  $_SESSION['quiz_results_id'] = $_POST['the_id'];
  $_SESSION['quiz_results_type'] = $_POST['the_quiz_type'];
  echo"<script>window.location.replace('../student_assessment_results')</script>";
   //header("Location: ../student_assessment_results");

  // exit();
}

?>
<?php 
echo "</body><br><br><div id='main_footer'><p id='footerinfo'>Evalumate Cloud Project</p><p id='ipinfo'>Server IP : ".file_get_contents( "https://allansantosh.com/cloud_project/getip.php" )."</p></div>";
?>