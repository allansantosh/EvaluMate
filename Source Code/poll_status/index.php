<html>
<?php

require "../config.php";
session_start();
if(!isset($_SESSION["sess_user"])){
   header("Location: ../login");
}

else {

    if ($_SESSION['guesttype'] == "instructor"){
        header("Location: ../");


    }

    else {

        echo "<div id='navbar'> 
        <ul>
          <li id='link1' style='float:left'><a href='../' ><img style='padding: 0 0 0 10; max-height:30px;' src='../evalumate_logo_white.png'></a></li>
          <li id='link2' style='float:left'><a href='../'>EVALUMATE</a></li>
          <li style='float:right'><a href='../logout'>Log Out</a></li>
          <li style='float:right'><a href='../enroll'>Enroll</a></li>
          <li style='float:right'><a href='../grades'>Grades</a></li>
          <li style='float:right'><a  href='../'>Dashboard</a></li>
          <li style='float:right'><a id='demo' href=''></a></li>
          <li style='float:right; background-color:#253f52;' ><a style='padding: 14 20 13 5'><label style='font-size: 15; font-family:Play;'>".$_SESSION['guestname']."</label><br><label style='font-size: 12; font-family:Play;'>".ucfirst($_SESSION['guesttype'])."</label></a></li>
          <li id='link1' style='float:right; background-color:#253f52;'><img style='padding: 15 15 4 15; max-height:30px;' src='../user_profile.png'></li>
          
        </ul></div>
        ";


        $total_questions = 0;
        $current_score = 0;
        if ($_SESSION['poll_finish'] == "1"){

            echo "<br><br><br><br><h2>You have successfully finished the quiz!</h2><br>";

            $sql = "SELECT quiz_q_and_a.id, answer, selection FROM quiz_q_and_a LEFT JOIN student_answers ON student_answers.quiz_id = quiz_q_and_a.quiz_id 
            AND quiz_q_and_a.id = student_answers.question_id WHERE student_answers.quiz_id = '".$_SESSION['poll_id']."' and user_id = '".$_SESSION['guestid']."'";
                 $result = $conn->query($sql);
             if ($result->num_rows > 0) {    
                while($row = $result->fetch_assoc()) {
                    $total_questions = $total_questions + 1;
                    if($row['answer'] == $row['selection']) {
                        $current_score =  $current_score + 1;
                    }
                }
             }
             
             $final_score = ($current_score/$total_questions)*100;

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
          <svg id='svg'> 
            <circle id='circle' cx="70" cy="70" r="70"></circle>
            <circle id='circle' cx="70" cy="70" r="70"></circle>
          </svg>
          <div id="number">
            <h2><?php echo round($final_score,2); ?><span>%</span></h2>
          </div>
        </div>
        <h2 id="text">Your Score</h2>
      </div>
    </div>
  </div>


           <?php

            echo "<br><br><h1>Assessment Score:<br><br>" . $current_score . "<hr style='width:50px;'>" . $total_questions . "</h1>";


            $sqla = "INSERT INTO grades (id,user_id,quiz_id,grade) VALUES(NULL,'".$_SESSION['guestid']."','".$_SESSION['poll_id']."','".round($final_score,2)."')";
            $resulta = $conn->query($sqla);

            $email_link = "https://allansantosh.com/cloud_project/grade_mail.php?name_mail=".urlencode($_SESSION['guestname'])."&grade_mail=".urlencode(round($final_score,2))."&email_mail=".urlencode($_SESSION['guestemail']);
            include( $email_link );


           //echo $sql;
            echo "<label>Redirecting you to Dashboard in 5 seconds.</label><br><br>";
            $_SESSION['poll_finish'] = 0;
            header('Refresh: 5; URL=../');
        }

        else if ($_SESSION['poll_finish'] == "2"){
            echo "<br><br><br><br><h2>You have failed to complete the quiz in the given time.</h2>";
            echo "<label>Redirecting you to Dashboard in 5 seconds.</label><br><br>";
            header('Refresh: 5; URL=../');
        }
   
        else {
            $_SESSION['poll_finish'] = 0;
           header("Location: ../");
        }
    }

}

?>
<?php 
echo "</body><br><br><div id='main_footer'><p id='footerinfo'>Evalumate Cloud Project</p><p id='ipinfo'>Server IP : ".file_get_contents( "https://allansantosh.com/cloud_project/getip.php" )."</p></div>";
?>