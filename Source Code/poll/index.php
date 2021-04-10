<?php
require "../config.php";
session_start();
if(!isset($_SESSION["sess_user"])){
    header("Location: ../login");
 }
 
else{
    
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
          <li style='float:right'><a href=''>Course</a></li>
          <li style='float:right'><a href='../'>Dashboard</a></li>
          <li style='float:right; background-color:#253f52;' ><a style='padding: 14 20 13 5'><label style='font-size: 15; font-family:Play;'>".$_SESSION['guestname']."</label><br><label style='font-size: 12; font-family:Play;'>".ucfirst($_SESSION['guesttype'])."</label></a></li>
          <li id='link1' style='float:right; background-color:#253f52;'><img style='padding: 15 15 4 15; max-height:30px;' src='../user_profile.png'></li>
        </ul></div><br><br><br><br>
        ";
        echo "<input id='poll_id' style='display: none;' value='".$_SESSION['poll_id']."'>";
        echo "<input id='server_ip_save' style='display: none;' value='52.15.230.146'>";
  
  
  ?>
  <!DOCTYPE html>
  
  <!--
  Copyright 2013 dc-square GmbH
  
  Licensed under the Apache License, Version 2.0 (the "License");
  you may not use this file except in compliance with the License.
  You may obtain a copy of the License at
  
      http://www.apache.org/licenses/LICENSE-2.0
  
  Unless required by applicable law or agreed to in writing, software
  distributed under the License is distributed on an "AS IS" BASIS,
  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
  See the License for the specific language governing permissions and
  limitations under the License.
  
  @author: Christoph Schäbel
  
  -->
  <!--[if IE 8]>
  <html class="no-js lt-ie9" lang="en"> <![endif]-->
  <!--[if gt IE 8]><!-->
  <html class="no-js" lang="en"> <!--<![endif]-->
  
  <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width">
  </head>
  
  <body class="notconnected" id='body'>
  <div  style="display: none;" id="content" class="row">
  <div id="connection" class="row large-12 columns">
  
      <div class="large-8 columns conniTop">
          <h3>Connection</h3>
      </div>
  
      <div class="large-1 columns conniStatus">
          <div id="connectionStatus"></div>
      </div>
  
      <div class="large-2 columns conniArrow">
          <a class="small bottom conniArrow" onclick="websocketclient.render.toggle('conni');">
              <div class="icon-arrow-chevron"></div>
          </a>
      </div>
      <div class="large-12 columns" id="conniMain">
          <div class="panel">
              <div class="row">
                  <form class="custom">
                      <div class="large-5 columns">
                          <label>Host *</label>
                          <input id="urlInput" type="text" value="broker.mqttdashboard.com">
                      </div>
  
                      <div class="large-1 columns">
                          <label>Port *</label>
                          <input id="portInput" type="text" value="9001"/>
                      </div>
  
                      <div class="large-4 columns">
                          <label>ClientID *</label>
                          <input id="clientIdInput" type="text"/>
                      </div>
  
                      <div class="large-2 columns">
                          <a id="connectButton" class="small button" onclick="websocketclient.connect();">Connect</a>
                      </div>
  
                      <div class="large-2 columns">
                          <a id="disconnectButton" class="small button"
                             onclick="websocketclient.disconnect();">Disconnect</a>
                      </div>
  
                      <div class="large-4 columns">
                          <label>Username</label>
                          <input id="userInput" type="text"/>
                      </div>
  
                      <div class="large-3 columns">
                          <label>Password</label>
                          <input id="pwInput" type="password"/>
                      </div>
  
                      <div class="large-2 columns">
                          <label>Keep Alive</label>
                          <input id="keepAliveInput" type="text" value="60"/>
                      </div>
  
                      <div class="large-1 columns">
                          <label>SSL</label>
                          <input id="sslInput" type="checkbox"/>
                      </div>
  
                      <div class="large-2 columns">
                          <label>Clean Session</label>
                          <input class="checky" id="cleanSessionInput" type="checkbox" checked="checked"
                                 disabled="disabled"/>
                      </div>
  
                      <div class="large-8 columns">
                          <label>Last-Will Topic</label>
                          <input id="lwTopicInput" type="text" value="cloud_project/<?php echo $_SESSION['poll_id']; ?>/att"/>
                      </div>
  
                      <div class="large-2 columns">
                          <label>Last-Will QoS</label>
                          <select id="lwQosInput">
                              <option>0</option>
                              <option>1</option>
                              <option>2</option>
                          </select>
                      </div>
  
                      <div class="large-2 columns">
                          <label>Last-Will Retain</label>
                          <input class="checky" id="LWRInput" type="checkbox"/>
                      </div>
  
                      <div class="large-12 columns">
                          <label>Last-Will Messsage</label>
                          <textarea id="LWMInput">absent</textarea>
                      </div>
                  </form>
              </div>
          </div>
      </div>
  </div>
  <div style="display: none;" class="empty"></div>
  <div id="publish-sub" class="row large-12 columns">
      <div class="columns large-8">
          <div class="large-9 columns publishTop">
              <h3>Publish</h3>
          </div>
  
          <div class="large-3 columns publishArrow">
              <a class="small bottom publishArrow" onclick="websocketclient.render.toggle('publish');">
                  <div class="icon-arrow-chevron"></div>
              </a>
          </div>
  
          <div class="large-12 columns" id="publishMain">
  
              <!-- Grid Example -->
              <div class="row panel" id="publishPanel">
                  <div class="large-12 columns">
                      <form class="custom">
                          <div class="row">
                              <div class="large-6 columns">
                                  <label>Topic</label>
                                  <input id="publishTopic" type="text" value="testtopic/1">
                              </div>
                              <div class="large-2 columns">
                                  <label for="publishQoSInput">QoS</label>
                                  <select id="publishQoSInput" class="small">
                                      <option>0</option>
                                      <option>1</option>
                                      <option>2</option>
                                  </select>
                              </div>
                              <div class="large-2 columns">
                                  <label>Retain</label>
                                  <input id="publishRetain" type="checkbox">
                              </div>
                              <div class="large-2 columns">
                                  <a class="small button" id="publishButton"
                                     onclick="websocketclient.publish($('#publishTopic').val(),$('#publishPayload').val(),parseInt($('#publishQoSInput').val(),10),$('#publishRetain').is(':checked'))">Publish</a>
                              </div>
                          </div>
                          <div class="row">
                              <div class="large-12 columns">
                                  <label>Message</label>
                                  <textarea id="publishPayload"></textarea>
                              </div>
                          </div>
                      </form>
                  </div>
              </div>
          </div>
          <div class="empty"></div>
          <div class="large-9 columns messagesTop">
              <h3>Messages</h3>
          </div>
  
          <div class="large-3 columns messagesArrow">
              <a class="small bottom messagesArrow" onclick="websocketclient.render.toggle('messages');">
                  <div class="icon-arrow-chevron"></div>
              </a>
          </div>
  
          <div class="large-12 columns" id="messagesMain">
  
              <!-- Grid Example -->
              <div class="row panel">
                  <div class="large-12 columns">
                      <form class="custom">
                          <!--<div class="row">-->
                          <!--<div class="large-10 columns">-->
                          <!--<label>Filter</label>-->
                          <!--<input id="filterString" type="text">-->
                          <!--</div>-->
  
                          <!--<div class="large-2 columns">-->
                          <!--<a class="small button" id="filterButton"-->
                          <!--onclick="websocketclient.filter($('#filterString').val())">Filter</a>-->
                          <!--</div>-->
                          <!--</div>-->
  
                      </form>
                      <div class="row">
                          <ul id="messEdit" class="disc">
  
                          </ul>
                      </div>
                  </div>
              </div>
  
          </div>
      </div>
  
      <div style="display: none;"class="columns large-4">
  
          <div class="large-8 columns subTop">
              <h3>Subscriptions</h3>
          </div>
  
          <div class="large-3 columns subArrow">
              <a class="small bottom subArrow" onclick="websocketclient.render.toggle('sub');">
                  <div class="icon-arrow-chevron"></div>
              </a>
          </div>
          <div class="large-12 columns" id="subMain">
              <div class="row panel">
                  <div class="large-12 columns">
  
                      <a id="addSubButton" href="#data" class="small button addSubButton">Add New Topic Subscription</a>
  
                      <div style="display:none">
                          <div id="data">
                              <form class="custom">
                                  <div class="row large-12 columns">
                                      <div class="large-4 columns">
                                          <label>Color</label>
                                          <input class="color" id="colorChooser" type="hidden">
                                      </div>
                                      <div class="large-5 columns">
                                          <label for="QoSInput">QoS</label>
                                          <select id="QoSInput" class="small">
                                              <option>2</option>
                                              <option>1</option>
                                              <option>0</option>
                                          </select>
                                      </div>
                                      <div class="large-3 columns">
                                          <a class="small button" id="subscribeButton"
                                             onclick="if(websocketclient.subscribe($('#subscribeTopic').val(),parseInt($('#QoSInput').val()),$('#colorChooser').val().substring(1))){$.fancybox.close();}">Subscribe</a>
                                      </div>
                                  </div>
                                  <div class="row large-12 columns">
                                      <div class="large-12 columns">
                                          <label>Topic</label>
                                          <input id="subscribeTopic" type="text" value="testtopic/#">
                                      </div>
                                  </div>
                              </form>
                          </div>
                      </div>
  
                      <ul id="innerEdit" class="disc">
  
                      </ul>
                  </div>
              </div>
          </div>
      </div>
  </div>
  </div><br><br>
  <h1>Assessment Status: <span id="connection_status"><span id='connection_status_inner' style='color: #ccc;'>Waiting...</span></span></h1>
  <div id = "current_question">Please wait for the instructor to connect.</div>
  <div id= "poll_loading_image" ><img style="width:100px;" src="../poll_loading.gif" /></div>
  <!--<img id='bulb_image' style='max-height: 400px;' src='../bulb_off.png'><br><br>
  <button id="led_status" onclick="led_publish();" disabled>LED Status: Unknown</button><br>
  <button id="record_event_1" onclick="capture_now_1();" disabled>Accident Capture Not Avaliable Now</button><br>
  <button id="record_event_2" onclick="capture_now_2();" disabled>Crowd Control Capture Not Avaliable Now</button> -->
  <?php


$sql = "SELECT * from quiz_q_and_a where quiz_id = '". $_SESSION['poll_id'] . "'";
$result= $conn->query($sql);
$x = 1;
        if($result->num_rows > 0)
        {
        echo "<div style='text-align:left; padding:20;'>";
        echo "<form id='quiz_form' name = 'quiz_form' method='post' action=''>";
        while($row = $result->fetch_assoc())
        {
            $ans_array = array($row['option1'],$row['option2'],$row['option3'],$row['option4']);
            shuffle($ans_array);
            echo "<div id='question_$x'style='text-align:left; padding:20; display:none;'>";
            echo "<h2>Question : " . $row['question']."</h2> <input type='hidden' id='qid' name='question_id_$x' value=".$row['id'].">";
            echo "<input type='radio' id='a' name='answer_id_$x' value='".$ans_array[0]."'>
            <label for='male'>".$ans_array[0]."</label><br>";
            echo "<input type='radio' id='a' name='answer_id_$x' value='".$ans_array[1]."'>
            <label for='male'>".$ans_array[1]."</label><br>";
            echo "<input type='radio' id='a' name='answer_id_$x' value='".$ans_array[2]."'>
            <label for='male'>".$ans_array[2]."</label><br>";
            echo "<input type='radio' id='a' name='answer_id_$x' value='".$ans_array[3]."'>
            <label for='male'>".$ans_array[3]."</label><br>";
            echo "</div>";
            $x = $x + 1;
        }

        echo "<div id ='submit_poll' style='display:none;'><input type='submit' value='Submit Answer' name='submit_quiz'></input></div></form>";
    }

    $x = $x - 1;
    echo "<input id='number_of_questions' style='display: none;' value=' $x'>";
?>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/1.3.1/lodash.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.js"></script>
  
  <script src="https://cdnjs.cloudflare.com/ajax/libs/foundation/4.2.3/js/foundation.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/foundation/4.2.3/js/foundation/foundation.forms.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.6.2/modernizr.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.1.0/moment.min.js"></script>
  <script src="js/jquery.minicolors.min.js"></script>
  <script src="js/mqttws31.js"></script>
  <script src="js/encoder.js"></script>
  <script src="js/app.js"></script>
  <script src="config.js"></script>
  
  <script>
  
      setTimeout(() => connection_check(), 2000);
  
 
      function connection_check(){
  
          if (document.getElementById("connection_status_inner").innerHTML == "Waiting...") { 
              window.location.reload(); 
          }
  
      }
  
  
      function randomString(length) {
          var text = "";
          var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
          for (var i = 0; i < length; i++)
              text += possible.charAt(Math.floor(Math.random() * possible.length));
          return text;
      }
  
      $(document).foundation();
      $(document).ready(function () {
  
          $('#urlInput').val(websocketserver);
          $('#portInput').val(websocketport);
          $('#clientIdInput').val('clientId-' + randomString(10));
  
          $('#colorChooser').minicolors();
  
          $("#addSubButton").fancybox({
              'afterShow': function () {
                  var rndColor = websocketclient.getRandomColor();
                  $("#colorChooser").minicolors('value', rndColor);
              }
          });
  
          websocketclient.render.toggle('publish');
          websocketclient.render.toggle('messages');
          websocketclient.render.toggle('sub');
          websocketclient.connect();
          //websocketclient.subscribe('capstone_project/JUXM12873/connection', '1', '999999');
          //websocketclient.subscribe('capstone_project/JUXM12873/LED', '1' ,'999999');
          //websocketclient.subscribe('capstone_project/JUXM12873/event_capture_progress', '1' , '999999');

          //for(radio in radios) {
             //   radios[radio].onclick = function() {
                //        websocketclient.publish('cloud_project/<?php echo $_SESSION['poll_id']; ?>/selection', radios[radio].value, 1, false);
                //    }
               // }
            //    var radios = document.forms["quiz_form"].elements["a"];
            //        for(var i = 0; i < radios.length; i++) {
              //      radios[i].onclick = function() {
             //           console.log( radios[i].value);
              //          websocketclient.publish('cloud_project/<?php echo $_SESSION['poll_id']; ?>/selection', 'ascascasc', 1, false);
              //  }
             //   }






          for (i = 1; i <= <?php echo $x ?>; i++) {
            var question = "question_" + i;
            document.getElementById(question).style.display = "none";
            console.log("aaaaa");
          }

      });
  </script>
  </body>
  </html>
  <?php

if(isset($_POST["submit_quiz"])){
    $_SESSION['poll_finish'] = 1;
    $quiz_id = $_SESSION['poll_id'];
    $user_id = $_SESSION['guestid'];


    $sql4 = "INSERT INTO student_answers (id, quiz_id, question_id, user_id, selection) VALUES "; 
    for ($a = 1; $a <= $x; $a++) {
        $q = "question_id_$a";
        $ans = "answer_id_$a";
        if($x == $a) {
            $sql4 .= "(NULL,'$quiz_id','$_POST[$q]','$user_id','$_POST[$ans]')";
        }
        else {
            $sql4 .= "(NULL,'$quiz_id','$_POST[$q]','$user_id','$_POST[$ans]'),";
        }
    }

    echo $sql4;
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
    echo "<script type='text/javascript'>location.href = '../poll_status';</script>";

}







  }
  }
?>
<?php 
echo "</body><br><br><div id='main_footer'><p id='footerinfo'>Evalumate Cloud Project</p><p id='ipinfo'>Server IP : ".file_get_contents( "https://allansantosh.com/cloud_project/getip.php" )."</p></div>";
?>