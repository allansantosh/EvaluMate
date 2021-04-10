<html>
<?php

require "../config.php";
session_start();
if(!isset($_SESSION["sess_user"])){
   header("Location: ../login");
}

else {

    if ($_SESSION['guesttype'] == "student"){
     //   header("Location: ../");
    }

    else if ($_SESSION["quiz_grades_id"] == NULL || $_SESSION["current_student_edit"] == NULL) {
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
        <li style='float:right'><a href='../set_grades'>Set Grade</a></li>
        <li style='float:right'><a href='../'>Dashboard</a></li>
        <li style='float:right; background-color:#253f52;' ><a style='padding: 14 20 13 5'><label style='font-size: 15; font-family:Play;'>".$_SESSION['guestname']."</label><br><label style='font-size: 12; font-family:Play;'>".ucfirst($_SESSION['guesttype'])."</label></a></li>
        <li id='link1' style='float:right; background-color:#253f52;'><img style='padding: 15 15 4 15; max-height:30px;' src='../user_profile.png'></li>
      </ul></div><br><br><br><br><h1>Grading Student</h1>
      ";

    $quizid = $_SESSION['quiz_grades_id'] ;
    $studentid = $_SESSION["current_student_edit"];

    $sql = "SELECT * FROM users WHERE id = " . $studentid;
    $result= $conn->query($sql);
    if($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $student_name = $row['name'];
            $student_email = $row['email'];
        }
    }



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

    
                ?>

                    <form action='' method='POST'>
                    <label>Enter Grade</label><br>
                    <input style='width:100px;' name='s_grade' type='text'></input><br>
                    <hr style='width:50px;'>
                    <h2>100</h2>
                    <input type='submit' value='Grade Student' name='grade_student_now'></input>
                </form>
                <?php


    if(isset($_POST['grade_student_now'])){
    $sql = "SELECT * FROM grades WHERE quiz_id =  $quizid and user_id = $studentid";
    $result= $conn->query($sql);
    if($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {

            $row_num = $row['id'];
            
        }

        $sql2 = "UPDATE grades SET grade = " .$_POST['s_grade']. " WHERE id = $row_num";
        $result2= $conn->query($sql2);

        $email_link = "https://allansantosh.com/cloud_project/file_mail.php?name_mail=".urlencode($student_name)."&grade_mail=".urlencode(round($_POST['s_grade'],2))."&email_mail=".urlencode($student_email);
        include( $email_link );
      echo "<script>window.location.replace('../set_grades');</script>";
    }

    else {

        $sql2 = "INSERT INTO grades (id,user_id,quiz_id,grade) VALUES (NULL,$studentid,$quizid,".$_POST['s_grade'].")";
        $result2= $conn->query($sql2);

        $email_link = "https://allansantosh.com/cloud_project/file_mail.php?name_mail=".urlencode($student_name)."&grade_mail=".urlencode(round($_POST['s_grade'],2))."&email_mail=".urlencode($student_email);
        include( $email_link );
         echo "<script>window.location.replace('../set_grades');</script>";

    }

}

    }

}
echo "<br><br>";
    ?>
<?php 
echo "</body><br><br><div id='main_footer'><p id='footerinfo'>Evalumate Cloud Project</p><p id='ipinfo'>Server IP : ".file_get_contents( "https://allansantosh.com/cloud_project/getip.php" )."</p></div>";
?>