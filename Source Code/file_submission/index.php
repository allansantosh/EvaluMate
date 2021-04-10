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
          <li style='float:right; background-color:#253f52;'><a id='demo' href=''></a></li>
          <li style='float:right; background-color:#253f52;' ><a style='padding: 14 20 13 5'><label style='font-size: 15; font-family:Play;'>".$_SESSION['guestname']."</label><br><label style='font-size: 12; font-family:Play;'>".ucfirst($_SESSION['guesttype'])."</label></a></li>
          <li id='link1' style='float:right; background-color:#253f52;'><img style='padding: 15 15 4 15; max-height:30px;' src='../user_profile.png'></li>
          
        </ul></div>
        ";

        echo "<br><br><br><br>";
        $quiz_id = $_SESSION['file_sub_id'];
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

        $sql = "SELECT * from quiz_q_and_a LEFT JOIN quiz ON quiz.id = quiz_id where quiz_id = '". $quiz_id . "' ORDER BY RAND()";
        $result= $conn->query($sql);
                if($result->num_rows > 0)
                {
                echo "<form enctype='multipart/form-data' action='' method='POST'>";
                while($row = $result->fetch_assoc())
                {
                    echo "<h1>File Submission</h1><br>";
                    echo "<label>".$row['question']."</label><br><br>";

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
                        'Key' => $row['proff'] . "_" .  $quiz_id . ".pdf"
                    ]);

                    $request1111 = $s3->createPresignedRequest($cmd, '+20 minutes');
                    $presignedUrl = (string)$request1111->getUri();

                    echo "<embed src='$presignedUrl' width='80%' style='height:100%'/> <br><br>";

                }

                echo "<label>Select PDF Document</label><br><br>";
                echo "<div class='file-input'>
              <input type='file'  name='userfile' accept='.pdf' id='file' class='file'>
                <label for='file'>
                  Select file
                  <p class='file-name'><br></p>
                </label>
              </div><br><br>";
                echo "<input type='submit' value='Submit Quiz' name='submit_quiz'></input></form>";
            }


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

        if(isset($_POST["submit_quiz"])){
            
            $_SESSION['file_sub_finish'] = 1;
            
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



            //header("Location: ../quiz_status");
            echo "<script type='text/javascript'>location.href = '../file_submission_status';</script>";

        }
    }
}

echo "<br><br>";
?>
<?php 
echo "</body><br><br><div id='main_footer'><p id='footerinfo'>Evalumate Cloud Project</p><p id='ipinfo'>Server IP : ".file_get_contents( "https://allansantosh.com/cloud_project/getip.php" )."</p></div>";
?>