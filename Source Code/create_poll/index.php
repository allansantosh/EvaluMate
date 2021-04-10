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

        if ($_SESSION["create_assessment_type"] == NULL || $_SESSION['create_assessment_type'] == '1') {
            header("Location: ../create_assessment");
        }


        else{

            echo "<div id='navbar'> 
            <ul>
            <li id='link1' style='float:left'><a href='../' ><img style='padding: 0 0 0 10; max-height:30px;' src='../evalumate_logo_white.png'></a></li>
            <li id='link2' style='float:left'><a href='../'>EVALUMATE</a></li>
            <li style='float:right'><a href='../logout'>Log Out</a></li>
            <li style='float:right'><a href='../stats'>Stats</a></li>
            <li style='float:right'><a class='active'  href='../create_assessment'>Create Assessment</a></li>
            <li style='float:right'><a href='../add_course'>Add Course</a></li>
            <li style='float:right'><a href='../'>Dashboard</a></li>
            <li style='float:right; background-color:#253f52;' ><a style='padding: 14 20 13 5'><label style='font-size: 15; font-family:Play;'>".$_SESSION['guestname']."</label><br><label style='font-size: 12; font-family:Play;'>".ucfirst($_SESSION['guesttype'])."</label></a></li>
            <li id='link1' style='float:right; background-color:#253f52;'><img style='padding: 15 15 4 15; max-height:30px;' src='../user_profile.png'></li>
          </ul></div>
          ";

            $num_question =  $_SESSION['create_assessment_num_ques'];

            echo "<br><br><br><br><h1>Add Questions</h1><br><form method='POST'>";
            
            for ($x = 1; $x <= $num_question; $x++) {
                echo "<label>Question $x</label><br>";
                echo "<input id='question_input' name='q$x' type='text'></input><br><br>";
                echo "<div style='text-align: center;'><table id='add_questions_table'><tr>";
                echo "<td><label id='answer_label' >Correct Answer<label><br><input id='answer_input' name='a_".$x."_1' type='text'></input><br></td>";
                echo "<td><label id='answer_label' >Other Answer<label><br><input id='answer_input' name='a_".$x."_2' type='text'></input><br></td>";
                echo "</tr>";
                echo "<td><label id='answer_label' >Other Answer<label><br><input id='answer_input' name='a_".$x."_3' type='text'></input><br></td>";
                echo "<td><label id='answer_label' >Other Answer<label><br><input id='answer_input' name='a_".$x."_4' type='text'></input><br></td>";
                echo "</tr></table></div><br><br><hr><br>";
              }
              echo "</select><br><br>";
              echo "<input type='submit' value='Create Quiz' name='create_quiz'></input>";
              echo "</form>";




              if(isset($_POST["create_quiz"])){

                $quiz_name = $_SESSION['create_assessment_name'];
                $user_id = $_SESSION['guestid'];
                $course_id = $_SESSION['create_assessment_course'];
                $pub_stat = $_SESSION['create_assessment_pub_stat'];
                $date_time = $_SESSION['create_assessment_date'];
                $duration = $_SESSION['create_assessment_duration'];
                $quiz_type = $_SESSION['create_assessment_type'];
                $sql = "INSERT INTO quiz (id, name, course, publish, proff, due, duration, quiz_type, poll_comp ) VALUES (NULL,'$quiz_name','$course_id','$pub_stat','$user_id','$date_time','$duration','$quiz_type', 0)";
                $result=$conn->query($sql);

                if($result){
                    echo "<label>Quiz successfully Created!</label><br><br>";
                    //header('Refresh: 5; URL=/login');
                      }

                      else {
                        echo "<label>Failed to add quiz!</label><br><br>";
                        }

                $sql2 = "SELECT MAX(id) as total_count FROM quiz";
                $result2= $conn->query($sql2);
                if($result2->num_rows > 0)
                {
                while($row = $result2->fetch_assoc())
                {
                    $quiz_id = $row['total_count'];
                }

                $sql3 = "INSERT INTO quiz_q_and_a (id, quiz_id, question, option1, option2, option3, option4, answer) VALUES ";

                for ($x = 1; $x <= $num_question; $x++) {
                    $question = "q".$x;
                    $answer1 = "a_".$x."_1";
                    $answer2 = "a_".$x."_2";
                    $answer3 = "a_".$x."_3";
                    $answer4 = "a_".$x."_4";
                    //echo "<label>$_POST[$answer1]</label><br><br>";
                    //var_dump($_POST);
                    if($x == $num_question) {
                        $sql3 .= "(NULL,'$quiz_id','".str_replace(chr(39),"\'",$_POST[$question])."','".str_replace(chr(39),"\'",$_POST[$answer1])."','".str_replace(chr(39),"\'",$_POST[$answer2])."','".str_replace(chr(39),"\'",$_POST[$answer3])."','".str_replace(chr(39),"\'",$_POST[$answer4])."','".str_replace(chr(39),"\'",$_POST[$answer1])."')";
                    }
                    else{
                        $sql3 .= "(NULL,'$quiz_id','".str_replace(chr(39),"\'",$_POST[$question])."','".str_replace(chr(39),"\'",$_POST[$answer1])."','".str_replace(chr(39),"\'",$_POST[$answer2])."','".str_replace(chr(39),"\'",$_POST[$answer3])."','".str_replace(chr(39),"\'",$_POST[$answer4])."','".str_replace(chr(39),'\'',$_POST[$answer1])."'),";
                  
                       //$sql3 .= "(NULL,'$quiz_id','$_POST[$question]','$_POST[$answer1]','$_POST[$answer2]','$_POST[$answer3]','$_POST[$answer4]','$_POST[$answer1]'),";
                    }
                }
                $sql3 = utf8_encode ($sql3);
                $result3=$conn->query($sql3);

                if($result3) {
                   // echo "Success";
                    $_SESSION['create_quiz_status'] = "1";
                    echo "<script>window.location.replace('../create_poll_status');</script>";

                   //str_replace(chr(39),'\'',$_POST[$question])

                   //echo "<br><label>".$sql3."</label>";
                }

                else {
                   // echo "didnt work";
                    $_SESSION['create_quiz_status'] = "2";
                    echo "<script>window.location.replace('../create_poll_status');</script>";
                  // echo "<br><label>".$sql3."</label>";
                }

                
               // header('Refresh: 5; URL=../');
               //echo "<script type='text/javascript'>location.href = '../';</script>";

            }
        }

          }


    }
}
?>
<?php 
echo "</body><br><br><div id='main_footer'><p id='footerinfo'>Evalumate Cloud Project</p><p id='ipinfo'>Server IP : ".file_get_contents( "https://allansantosh.com/cloud_project/getip.php" )."</p></div>";
?>