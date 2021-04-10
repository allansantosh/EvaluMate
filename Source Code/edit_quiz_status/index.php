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

        if ($_SESSION['edit_quiz_status'] == '2') {
            header("Location: ../course_view");
        }

        else{

            echo "<div id='navbar'> 
            <ul>
            <li id='link1' style='float:left'><a href='../' ><img style='padding: 0 0 0 10; max-height:30px;' src='../evalumate_logo_white.png'></a></li>
            <li id='link2' style='float:left'><a href='../'>EVALUMATE</a></li>
            <li style='float:right'><a href='../logout'>Log Out</a></li>
            <li style='float:right'><a href='../stats'>Stats</a></li>
            <li style='float:right'><a href='../create_assessment'>Create Assessment</a></li>
            <li style='float:right'><a href='../add_course'>Add Course</a></li>
            <li style='float:right'><a href='../course_view'>Course</a></li>
            <li style='float:right'><a href='../'>Dashboard</a></li>
            <li style='float:right; background-color:#253f52;' ><a style='padding: 14 20 13 5'><label style='font-size: 15; font-family:Play;'>".$_SESSION['guestname']."</label><br><label style='font-size: 12; font-family:Play;'>".ucfirst($_SESSION['guesttype'])."</label></a></li>
            <li id='link1' style='float:right; background-color:#253f52;'><img style='padding: 15 15 4 15; max-height:30px;' src='../user_profile.png'></li>
          </ul></div>
          ";


          $sql = "SELECT * FROM grades WHERE quiz_id = " . $_SESSION['quiz_edit_id'];
          $result = $conn->query($sql);
          if ($result->num_rows > 0) {   
            while($row = $result->fetch_assoc()) {
                $total_score = 0;
                $num_questions = 0;
                $sql2 = "SELECT quiz_q_and_a.*, selection from quiz_q_and_a  LEFT JOIN student_answers ON quiz_q_and_a.id = student_answers.question_id WHERE quiz_q_and_a.quiz_id = ".$_SESSION['quiz_edit_id']." and user_id = " . $row['user_id'];
                $result2= $conn->query($sql2);
                if($result2->num_rows > 0) {
                    $total_score = 0;
                    $num_questions = 0;
                    while($row2 = $result2->fetch_assoc())
                    {
                        if ($row2['option1'] ==  $row2['selection']) {
                            $total_score ++;
                        }
                        $num_questions++;
                    }
                }

                $total_score = ($total_score/$num_questions)*100;
                $sql3 = "UPDATE grades SET grade = '".$total_score."' WHERE id = '" . $row['id'] . "'";
                $result3 = $conn->query($sql3);

            }
        }




          if ($_SESSION['edit_quiz_status'] == 1)
          {
            $_SESSION['edit_quiz_status'] = 2;
            echo "<br><br><br><br><h2>Edited Quiz Successfully!</h2>";
            echo "<label>Redirecting you to Course in 5 seconds.</label><br><br>";
           // header('Refresh: 5; URL=../course_view');
        }

        else {
            echo "<br><br><br><br><h2>Quiz creation failed!</h2>";
            echo "<label>Redirecting you to Course in 5 seconds.</label><br><br>";
          //  header('Refresh: 5; URL=../course_view');

        }

        }
    }
}


        ?>
<?php 
echo "</body><br><br><div id='main_footer'><p id='footerinfo'>Evalumate Cloud Project</p><p id='ipinfo'>Server IP : ".file_get_contents( "https://allansantosh.com/cloud_project/getip.php" )."</p></div>";
?>