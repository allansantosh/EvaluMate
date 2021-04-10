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
        if ($_SESSION['file_sub_finish'] == "1"){

            echo "<br><br><br><br><h2>You have successfully submited the quiz! Your grade will be released later.</h2><br>";

             //echo $sql;
            echo "<label>Redirecting you to Dashboard in 5 seconds.</label><br><br>";
            $_SESSION['file_sub_finish'] = "5";
            $_SESSION['quiz_finish'] = 0;
            header('Refresh: 5; URL=../');
        }

        else if ($_SESSION['file_sub_finish'] == "2"){
            echo "<br><br><br><br><h2>You have failed to complete the quiz in the given time.</h2>";
            echo "<label>Redirecting you to Dashboard in 5 seconds.</label><br><br>";
            header('Refresh: 5; URL=../');
        }
   
        else {
        //    $_SESSION['quiz_finish'] = 0;
         //   header("Location: ../");
         header('Refresh: 5; URL=../');
        }
    }

}

?>
<?php 
echo "</body><br><br><div id='main_footer'><p id='footerinfo'>Evalumate Cloud Project</p><p id='ipinfo'>Server IP : ".file_get_contents( "https://allansantosh.com/cloud_project/getip.php" )."</p></div>";
?>