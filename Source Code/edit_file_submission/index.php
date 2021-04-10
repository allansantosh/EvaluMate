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
          
          $ar=array();
            $sql = "SELECT * FROM quiz_q_and_a  WHERE quiz_id = " . $_SESSION['quiz_edit_id'];
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {   
                echo "<h1>Edit Questions</h1><br><form enctype='multipart/form-data' action='' method='POST'>";
              while($row = $result->fetch_assoc()) {
               // array_push($ar,$row['id']);
                echo "<label>Instructions</label><br>";
                echo "<input id='question_input' value='".str_replace(chr(39),"&apos;",$row['question'])."' name='instruction' type='text'></input><br><br>";
                echo "<label>Select PDF Document or leave it blank if there is no file update.</label><br><br>";
                echo "<div class='file-input'>
              <input type='file'  name='userfile' accept='.pdf' id='file' class='file'>
                <label for='file'>
                  Select file
                  <p class='file-name'><br></p>
                </label>
              </div><br><br>";
                //echo "<input id='question_input' value='".str_replace(chr(39),"&apos;",$row['question'])."' name='q$x' type='text'></input><br><br>";
               // echo "<div style='text-align: center;'><table id='add_questions_table'><tr>";
                //echo "<td><label id='answer_label' >Correct Answer<label><br><input value='".str_replace(chr(39),"&apos;",$row['option1'])."' id='answer_input' name='a_".$x."_1' type='text'></input><br></td>";
                //echo "<td><label id='answer_label' >Other Answer<label><br><input value='".str_replace(chr(39),"&apos;",$row['option2'])."' id='answer_input' name='a_".$x."_2' type='text'></input><br></td>";
                //echo "</tr>";
                //echo "<td><label id='answer_label' >Other Answer<label><br><input value='".str_replace(chr(39),"&apos;",$row['option3'])."' id='answer_input' name='a_".$x."_3' type='text'></input><br></td>";
                //echo "<td><label id='answer_label' >Other Answer<label><br><input value='".str_replace(chr(39),"&apos;",$row['option4'])."' id='answer_input' name='a_".$x."_4' type='text'></input><br></td>";
               // echo "</tr></table></div><br><br><hr><br>";
              //  $x++;
              }
             // echo "</select><br><br>";
              echo "<input type='submit' value='Edit Quiz' name='edit_quiz'></input>";
              echo "</form>";


              ?>

              <script>
  const file = document.querySelector('#file');
  file.addEventListener('change', (e) => {
    // Get the selected file
    const [file] = e.target.files;
    // Get the file name and size
    const { name: fileName, size } = file;
    // Convert size in bytes to kilo bytes
    const fileSize = (size / 1000).toFixed(2);
    // Set the text content
    const fileNameAndSize = `${fileName} - ${fileSize}KB`;
    document.querySelector('.file-name').textContent = fileNameAndSize;
  });
  </script>
  
  <?php
          }
        }
        $quiz_id = $_SESSION['quiz_edit_id'];

        if(isset($_POST["edit_quiz"])){

            $sql4 = "DELETE FROM quiz_q_and_a WHERE quiz_id = " .  $_SESSION['quiz_edit_id'];
            $result4 = $conn->query($sql4);

            $sql3 = "INSERT INTO quiz_q_and_a (id, quiz_id, question, option1, option2, option3, option4, answer) VALUES ";
            $sql3 .= "(NULL,'$quiz_id','".str_replace(chr(39),"\'",$_POST['instruction'])."',NULL,NULL,NULL,NULL,NULL)";
            $sql3 = utf8_encode ($sql3);
            $result3=$conn->query($sql3);

            if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['userfile']) && $_FILES['userfile']['error'] == UPLOAD_ERR_OK && is_uploaded_file($_FILES['userfile']['tmp_name'])) {

            require('../vendor/autoload.php');
            // this will simply read AWS_ACCESS_KEY_ID and AWS_SECRET_ACCESS_KEY from env vars
                $guestid = $_SESSION['guestid'];
                $s3 = new Aws\S3\S3Client([
                    'region'   => 'us-east-1',
                    'version'  => '2006-03-01',
                    'credentials' => [
                        'key'    => '',
                        'secret' => 'H/zWi2rjh2A/7HE3l6L9/OWuo3DISUPz6y/31Bjv',
                    ]
                ]);
                //$bucket = getenv('S3_BUCKET')?: die('No "S3_BUCKET" config var in found in env!');
               // $bucket = 'evalumate';
                $bucket = 'evalumate'?: die('No "S3_BUCKET" config var in found in env!');
                // FIXME: you should add more of your own validation here, e.g. using ext/fileinfo
                try {
                    $temp_file_name = explode(".", $_FILES["userfile"]["name"]);
                    $newfilename = $guestid . "_" . $quiz_id . '.' . end($temp_file_name);
                    //$sqlb="INSERT INTO videos(id,name,filename,views,owner,lattitude,longitude) VALUES(NULL,'".$_POST['videotitle']."','".$newfilename."',1,".$guestid.",".$_POST['lattitude'].",".$_POST['longitude'].")";
                   // $resultb=$conn->query($sqlb);
                   $upload = $s3->upload($bucket, $newfilename, fopen($_FILES['userfile']['tmp_name'], 'rb'), 'public-read', array('params' => array('ContentType' => 'application/pdf')));
                   // echo "<h3>New video upload success!</h3>";

                }
             catch (Exception $e) {
                echo $e; //Catch errors from Amazon SES.
            }

        }




                if($result3) {
                    echo "Successfully edited the quiz!";
                     $_SESSION['edit_quiz_status'] = "1";
                    echo "<script>window.location.replace('../edit_file_submission_status');</script>";

                   //str_replace(chr(39),'\'',$_POST[$question])

                   //echo "<br><label>".$sql3."</label>";
                }

                else {
                   echo "Failed to edit the quiz!";
                    $_SESSION['edit_quiz_status'] = "2";
                    echo "<script>window.location.replace('../edit_file_submission_status');</script>";
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