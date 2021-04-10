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

    if ($_SESSION['guesttype'] == "student" && isset($_SESSION['quiz_results_id'])){

        echo "<div id='navbar'> 
        <ul>
          <li id='link1' style='float:left'><a href='../' ><img style='padding: 0 0 0 10; max-height:30px;' src='../evalumate_logo_white.png'></a></li>
          <li id='link2' style='float:left'><a href='../'>EVALUMATE</a></li>
          <li style='float:right'><a href='../logout'>Log Out</a></li>
          <li style='float:right'><a href='../enroll'>Enroll</a></li>
          <li style='float:right'><a href='../course_details'>Course</a></li>
          <li style='float:right'><a href='../'>Dashboard</a></li>
          <li style='float:right; background-color:#253f52;' ><a style='padding: 14 20 13 5'><label style='font-size: 15; font-family:Play;'>".$_SESSION['guestname']."</label><br><label style='font-size: 12; font-family:Play;'>".ucfirst($_SESSION['guesttype'])."</label></a></li>
          <li id='link1' style='float:right; background-color:#253f52;'><img style='padding: 15 15 4 15; max-height:30px;' src='../user_profile.png'></li>
        </ul></div><br><br><br><br>
        ";


        //echo "<br><br><br><br>";

          $sql2 = "SELECT quiz.id as id, quiz.name, grades.grade, t1.avg FROM quiz LEFT JOIN grades ON quiz.id = grades.quiz_id LEFT JOIN (SELECT quiz_id, AVG(grade) as avg FROM grades GROUP BY quiz_id)as t1 ON t1.quiz_id = quiz.id WHERE course = ".$_SESSION['course_id']." and quiz.id = '".$_SESSION['quiz_results_id']."' and grades.user_id = ".$_SESSION['guestid'];
          //echo $sql2;
          $result2 = $conn->query($sql2);
          if ($result2->num_rows > 0) {   
         while($row = $result2->fetch_assoc()) {
             $final_score = $row['grade'];
             $final_score1 = $row['avg'];
                ?>
<div id="container">
    <div id="card">
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
          <svg id="svg"> 
            <circle id="circle" cx="70" cy="70" r="70"></circle>
            <circle id="circle" cx="70" cy="70" r="70"></circle>
          </svg>
          <div id="number">
            <h2><?php echo round($final_score,2); ?><span>%</span></h2>
          </div>
        </div>
        <h2 id="text">Your Grade</h2>
      </div>
    </div>
    <div id="card">
      <div class="box" style="width:100%;">
        <?php echo "<h1>".$row['name']."</h1>";?>
        </div>
        </div>
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
          
          #card:nth-child(3) #svg #circle:nth-child(2) {
            stroke-dashoffset: calc(440 - (440 *".round($final_score1,2) .") / 100);
            stroke: ".$stroke_colour.";
          }
          </style>
          ";
          ?>
          <svg id="svg">  
            <circle id="circle"cx="70" cy="70" r="70"></circle>
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

                <?php

          if($_SESSION['quiz_results_type'] !=  '3') {
        $sql = "SELECT quiz_q_and_a.*, selection from quiz_q_and_a  LEFT JOIN student_answers ON quiz_q_and_a.id = student_answers.question_id WHERE quiz_q_and_a.quiz_id = ".$_SESSION['quiz_results_id']." and user_id = " . $_SESSION['guestid'];
        $result= $conn->query($sql);
        $x = 1;
        $mygrade = 0;
        if($result->num_rows > 0)
        {
        echo "";
        while($row = $result->fetch_assoc())
        {
            $ans_array = array($row['option1'],$row['option2'],$row['option3'],$row['option4']);
            shuffle($ans_array);
            echo "<div style='text-align:left; padding-left:50;'><h3>Question $x : ".$row['question']."</h3>";
            echo "<label>Your Answer: ".$row['selection']."</label>".str_repeat('&nbsp;', 2); 
                if ($row['option1'] == $row['selection']){
                    echo "<img style='max-height: 25px;' src='../check.png'/>";
                    $mygrade ++;
                }
                else {
                    echo "<img style='max-height: 20px;' src='../xmark.png'/>";
                }
            echo "<br><label>Correct Answer: ".$row['option1']."</label><br><br>";
            echo "<label for='male'>Option 1:".str_repeat('&nbsp;', 2) . $ans_array[0]."</label><br>";
            echo "<label for='male'>Option 2:".str_repeat('&nbsp;', 2) . $ans_array[1]."</label><br>";
            echo "<label for='male'>Option 3:".str_repeat('&nbsp;', 2) . $ans_array[2]."</label><br>";
            echo "<label for='male'>Option 4:".str_repeat('&nbsp;', 2) . $ans_array[3]."</label><br>";
            echo "<br></div>";
            $x = $x + 1;
                      }
    
                 }
        echo "<h1>Assessment Score:<br><br>" . $mygrade . "<hr style='width:50px;'>" . ($x-1) . "</h1><br>";
                }

                else {


                                      $quizid = $_SESSION['quiz_results_id'] ;
                    $studentid = $_SESSION["guestid"];

                    require '../vendor/autoload.php';
                    $s3 = new Aws\S3\S3Client([
                    'region'  => 'us-east-1',
                    'version' => 'latest',
                    'credentials' => [
                        'key'    => '',
                        'secret' => 'H/zWi2rjh2A/7HE3l6L9/OWuo3DISUPz6y/31Bjv',
                    ]
                    ]);

                    $cmd = $s3->getCommand('GetObject', [
                        'Bucket' => 'evalumate',
                        'Key' => $studentid . "_" .  $quizid . ".pdf"
                    ]);

                    $request1111 = $s3->createPresignedRequest($cmd, '+20 minutes');
                    $presignedUrl = (string)$request1111->getUri();

                    echo "<embed src='$presignedUrl' width='80%' style='height:100%'/> <br><br>";


                  echo "<h1>Assessment Score:<br><br>" . $final_score . "<hr style='width:75px;'>100</h1><br>";
                }





            }
        }
    }

    else{

        header("Location: ../course_details");

    }
}
?>
<?php 
echo "</body><br><br><div id='main_footer'><p id='footerinfo'>Evalumate Cloud Project</p><p id='ipinfo'>Server IP : ".file_get_contents( "https://allansantosh.com/cloud_project/getip.php" )."</p></div>";
?>





