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

    else if ($_SESSION["quiz_grades_id"] == NULL) {
        header("Location: ../course_view");
    }

    else {

        
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
      </ul></div><br><br><br><br><h1>Set Quiz Grades</h1>
      ";

    $quizid = $_SESSION['quiz_grades_id'] ;

      $sql = "SELECT student_attempt.user_id, IFNULL(grade, 'No') as grade2, users.name FROM student_attempt LEFT JOIN grades ON student_attempt.user_id = grades.user_id and grades.quiz_id = $quizid LEFT JOIN users ON student_attempt.user_id = users.id where student_attempt.quiz_id = " .$quizid ." and completed = 1";
      $result = $conn->query($sql);
      if ($result->num_rows > 0) {
        echo"<div id='dash_quiz_table' align='center'><table ><tr><th>Quiz Name</th><th>Grade</th><th>Option</th></tr>";
        while($row = $result->fetch_assoc()) {
                    echo "<td>".$row['name']."</td>";
                    if ($row['grade2']!='No') {
                       echo "<td>".$row['grade2']."</td>";
                    }
                    else {
                        echo "<td><img style='max-height: 25px;' src='../xmark.png' /></td>";
                    }
                    echo "<td><form method='POST'><input type='hidden' name='the_id' value=".$row['user_id']."><input id='inner_button' type='submit' name='grade' value='Grade'></form></tr>";
        }
    }

    else {
                echo "No students have attempted this quiz yet. Please try again later.";
    }


    if(isset($_POST["grade"])){

        $_SESSION['current_student_edit'] = $_POST["the_id"];
        echo "<script>window.location.replace('../grade_student');</script>";
    }


    }

}
    ?>
<?php 
echo "</body><br><br><div id='main_footer'><p id='footerinfo'>Evalumate Cloud Project</p><p id='ipinfo'>Server IP : ".file_get_contents( "https://allansantosh.com/cloud_project/getip.php" )."</p></div>";
?>