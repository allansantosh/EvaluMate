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

        if ($_SESSION["quiz_edit_id"] == NULL) {
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
          </ul></div><br><br><br><br>
          ";
          
          $x = 1;   
          $ar=array();
            $sql = "SELECT * FROM quiz_q_and_a  WHERE quiz_id = " . $_SESSION['quiz_edit_id'];
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {   
                echo "<h1>Edit Questions</h1><br><form method='POST'>";
              while($row = $result->fetch_assoc()) {
                array_push($ar,$row['id']);
                echo "<label>Question $x</label><br>";
                echo "<input id='question_input' value='".str_replace(chr(39),"&apos;",$row['question'])."' name='q$x' type='text'></input><br><br>";
                echo "<div style='text-align: center;'><table id='add_questions_table'><tr>";
                echo "<td><label id='answer_label' >Correct Answer<label><br><input value='".str_replace(chr(39),"&apos;",$row['option1'])."' id='answer_input' name='a_".$x."_1' type='text'></input><br></td>";
                echo "<td><label id='answer_label' >Other Answer<label><br><input value='".str_replace(chr(39),"&apos;",$row['option2'])."' id='answer_input' name='a_".$x."_2' type='text'></input><br></td>";
                echo "</tr>";
                echo "<td><label id='answer_label' >Other Answer<label><br><input value='".str_replace(chr(39),"&apos;",$row['option3'])."' id='answer_input' name='a_".$x."_3' type='text'></input><br></td>";
                echo "<td><label id='answer_label' >Other Answer<label><br><input value='".str_replace(chr(39),"&apos;",$row['option4'])."' id='answer_input' name='a_".$x."_4' type='text'></input><br></td>";
                echo "</tr></table></div><br><br><hr><br>";
                $x++;
              }
              echo "</select><br><br>";
              echo "<input type='submit' value='Edit Quiz' name='edit_quiz'></input>";
              echo "</form>";
          }
        }
        $quiz_id = $_SESSION['quiz_edit_id'];

        if(isset($_POST["edit_quiz"])){

            $sql4 = "DELETE FROM quiz_q_and_a WHERE quiz_id = " .  $_SESSION['quiz_edit_id'];
            $result4 = $conn->query($sql4);
            print_r($ar);

            $sql3 = "INSERT INTO quiz_q_and_a (id, quiz_id, question, option1, option2, option3, option4, answer) VALUES ";

                for ($x = 1; $x <= count($ar); $x++) {
                    $question = "q".$x;
                    $answer1 = "a_".$x."_1";
                    $answer2 = "a_".$x."_2";
                    $answer3 = "a_".$x."_3";
                    $answer4 = "a_".$x."_4";
                    //echo "<label>$_POST[$answer1]</label><br><br>";
                    //var_dump($_POST);
                    if($x == count($ar)) {
                        $sql3 .= "(".$ar[($x-1)].",'$quiz_id','".str_replace(chr(39),"\'",$_POST[$question])."','".str_replace(chr(39),"\'",$_POST[$answer1])."','".str_replace(chr(39),"\'",$_POST[$answer2])."','".str_replace(chr(39),"\'",$_POST[$answer3])."','".str_replace(chr(39),"\'",$_POST[$answer4])."','".str_replace(chr(39),"\'",$_POST[$answer1])."')";
                    }
                    else{
                        $sql3 .= "(".$ar[($x-1)].",'$quiz_id','".str_replace(chr(39),"\'",$_POST[$question])."','".str_replace(chr(39),"\'",$_POST[$answer1])."','".str_replace(chr(39),"\'",$_POST[$answer2])."','".str_replace(chr(39),"\'",$_POST[$answer3])."','".str_replace(chr(39),"\'",$_POST[$answer4])."','".str_replace(chr(39),'\'',$_POST[$answer1])."'),";
                  
                       //$sql3 .= "(NULL,'$quiz_id','$_POST[$question]','$_POST[$answer1]','$_POST[$answer2]','$_POST[$answer3]','$_POST[$answer4]','$_POST[$answer1]'),";
                    }
                }
                $sql3 = utf8_encode ($sql3);
                $result3=$conn->query($sql3);

                if($result3) {
                    echo "Successfully edited the quiz!";
                     $_SESSION['edit_quiz_status'] = "1";
                    echo "<script>window.location.replace('../edit_quiz_status');</script>";

                   //str_replace(chr(39),'\'',$_POST[$question])

                   //echo "<br><label>".$sql3."</label>";
                }

                else {
                   echo "Failed to edit the quiz!";
                    $_SESSION['edit_quiz_status'] = "2";
                    echo "<script>window.location.replace('../edit_quiz_status');</script>";
                  // echo "<br><label>".$sql3."</label>";
                }
        }

        //print_r($ar);
        //echo "<br><br><br><br><br>";
    }
}

?>
<?php 
echo "</body><br><br><div id='main_footer'><p id='footerinfo'>Evalumate Cloud Project</p><p id='ipinfo'>Server IP : ".file_get_contents( "https://allansantosh.com/cloud_project/getip.php" )."</p></div>";
?>