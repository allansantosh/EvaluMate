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
          <li style='float:right'><a href=''>Enroll</a></li>
          <li style='float:right'><a  href='../'>Dashboard</a></li>
          <li style='float:right'><a id='demo' href=''></a></li>
          <li style='float:right; background-color:#253f52;' ><a style='padding: 14 20 13 5'><label style='font-size: 15; font-family:Play;'>".$_SESSION['guestname']."</label><br><label style='font-size: 12; font-family:Play;'>".ucfirst($_SESSION['guesttype'])."</label></a></li>
          <li id='link1' style='float:right; background-color:#253f52;'><img style='padding: 15 15 4 15; max-height:30px;' src='../user_profile.png'></li>
          
        </ul></div>
        ";

        echo "<br><br><br><br><h1>Quiz</h1><br>";
        $quiz_id = $_SESSION['quiz_id'];
        $user_id = $_SESSION['guestid'];
        $start_time = 0;
        $duration = 0;

        $sql5 = "SELECT * FROM student_attempt WHERE quiz_id = '". $quiz_id . "' AND user_id = '". $user_id . "' and completed='0'";
        $result5= $conn->query($sql5);
        if($result5->num_rows > 0)
        {
            while($row = $result5->fetch_assoc())
            {
                $start_time = $row['start_time'];
                //$start_time = strtotime($start_time);
                //echo $start_time;
                //$start_time =  date_format($start_time,"M d, Y H:i:s");
            }
        }

        else{
            header("Location: ../");
        }

        $sql6 = "SELECT * FROM quiz WHERE id = '". $quiz_id . "'";
        $result6= $conn->query($sql6);
        if($result6->num_rows > 0)
        {
            while($row = $result6->fetch_assoc())
            {
                $duration = $row['duration'];
            }
        }

        //$start_time2 = gmdate("M d, Y H:i:s", $start_time);
        //echo $start_time2 . "<br>";

        $datenow = date_create();
        $datenow = date_timestamp_get($datenow) - 14400;

        $duration = 60*$duration;
        $start_time = $start_time - 14400 + $duration;

        $checktime  = ($start_time - $datenow)*1000 ;

        if($checktime < 0) {
            header("Location: ../");
        }
        $start_time = gmdate("M d, Y H:i:s", $start_time);
        echo "
        <script>
// Set the date we're counting down to
var start = '$start_time';
var countDownDate = new Date(start).getTime();

// Update the count down every 1 second
var x = setInterval(function() {

  // Get today's date and time
  var now = new Date().getTime();

  // Find the distance between now and the count down date
  var distance = countDownDate - now;

  // Time calculations for days, hours, minutes and seconds
  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  var seconds = Math.floor((distance % (1000 * 60)) / 1000);

  // Display the result in the element with id='demo'
  document.getElementById('demo').innerHTML = 'Time Remaining: ' + hours + 'h '
  + minutes + 'm ' + seconds + 's ';

  // If the count down is finished, write some text
  if (distance < 0) {
    clearInterval(x);
    document.getElementById('demo').innerHTML = 'Time\'s Up!';
  }
}, 1000);

setTimeout(() => connection_check(), $checktime);
    function connection_check(){ 
    window.location.replace('../quiz_status');
    console.log('dd');
}

</script>
        ";

        $sql = "SELECT * from quiz_q_and_a where quiz_id = '". $quiz_id . "' ORDER BY RAND()";
        $result= $conn->query($sql);
        $x = 1;
                if($result->num_rows > 0)
                {
                echo "<form id='quiz_form' method='post' action=''>";
                while($row = $result->fetch_assoc())
                {
                    $ans_array = array($row['option1'],$row['option2'],$row['option3'],$row['option4']);
                    shuffle($ans_array);
                    echo "<div style='text-align:left; padding:20;'><h3>Question ".$x. " : " . $row['question']."</h3> <input type='hidden' id='qid' name='question_id_$x' value=".$row['id'].">";
                    echo "<input type='radio' id='a' name='answer_id_$x' value='".$ans_array[0]."'>
                    <label for='male'>".$ans_array[0]."</label><br>";
                    echo "<input type='radio' id='a' name='answer_id_$x' value='".$ans_array[1]."'>
                    <label for='male'>".$ans_array[1]."</label><br>";
                    echo "<input type='radio' id='a' name='answer_id_$x' value='".$ans_array[2]."'>
                    <label for='male'>".$ans_array[2]."</label><br>";
                    echo "<input type='radio' id='a' name='answer_id_$x' value='".$ans_array[3]."'>
                    <label for='male'>".$ans_array[3]."</label><br>";
                    echo "<br></div>";
                    $x = $x + 1;
                }
                echo "<input type='submit' value='Submit Quiz' name='submit_quiz'></input></form>";
            }
        if(isset($_POST["submit_quiz"])){
            $_SESSION['quizid'] = $quiz_id;
            $_SESSION['quiz_finish'] = 1;
            $sql4 = "INSERT INTO student_answers (id, quiz_id, question_id, user_id, selection) VALUES "; 

            for ($a = 1; $a < $x; $a++) {
                $q = "question_id_$a";
                $ans = "answer_id_$a";
                if($x - $a == 1) {
                    $sql4 .= "(NULL,'$quiz_id','$_POST[$q]','$user_id','$_POST[$ans]')";
                }
                else {
                    $sql4 .= "(NULL,'$quiz_id','$_POST[$q]','$user_id','$_POST[$ans]'),";
                }
            }
            $result4=$conn->query($sql4);
            if($result4) {
                echo "<br><br><label>Quiz Submitted</label>";
            }
            else{
                echo "<br><br><label>Failed to Submit Quiz</label>";
            }

            
            $sql5 = "SELECT * FROM student_attempt WHERE quiz_id = '$quiz_id' and user_id = '$user_id'";
            $result5=$conn->query($sql5);
            $new_row = 0;
            if ($result5->num_rows > 0) {   
             while($row = $result5->fetch_assoc())
                {
                    $new_row = $row['id'];
                }
            }   

            $sql6 = "UPDATE student_attempt SET completed = 1 WHERE id ='$new_row'";
            $result6=$conn->query($sql6);

            //header("Location: ../quiz_status");
            echo "<script type='text/javascript'>location.href = '../quiz_status';</script>";

        }
    }
}


?>
<?php 
echo "</body><br><br><div id='main_footer'><p id='footerinfo'>Evalumate Cloud Project</p><p id='ipinfo'>Server IP : ".file_get_contents( "https://allansantosh.com/cloud_project/getip.php" )."</p></div>";
?>