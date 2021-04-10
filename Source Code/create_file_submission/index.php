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

            echo "<br><br><br><br><h1>File Submission Assessment</h1><br><form enctype='multipart/form-data' action='' method='POST'>";
            
            echo "<label>Instructions</label><br>";
            echo "<input id='question_input' name='instruction' type='text'></input><br><br>";
            echo "<label>Select PDF Document</label><br><br>";
                    echo "<div class='file-input'>
                  <input type='file'  name='userfile' accept='.pdf' id='file' class='file'>
                    <label for='file'>
                      Select file
                      <p class='file-name'><br></p>
                    </label>
                  </div><br><br>";

          //  echo "<input type='file'  name='userfile' accept='.pdf'>";
            echo "<input type='submit' value='Create Quiz' name='create_quiz'></input>";
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
                $sql3 .= "(NULL,'$quiz_id','".str_replace(chr(39),"\'",$_POST['instruction'])."',NULL,NULL,NULL,NULL,NULL)";
                $sql3 = utf8_encode ($sql3);
                $result3=$conn->query($sql3);

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

                if($result3) {
                   // echo "Success";
                    $_SESSION['create_file_sub_status'] = "1";
                    echo "<script>window.location.replace('../create_file_submission_status');</script>";

                   //str_replace(chr(39),'\'',$_POST[$question])

                   //echo "<br><label>".$sql3."</label>";
             }

               else {
                   // echo "didnt work";
                 $_SESSION['create_file_sub_status'] = "2";
                    echo "<script>window.location.replace('../create_file_submission_status');</script>";
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