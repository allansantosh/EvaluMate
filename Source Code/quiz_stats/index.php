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

        if ($_SESSION["quiz_stats_id"] == NULL) {
           // header("Location: ../course_view");
        }


        else{

            echo "<div id='navbar'> 
            <ul>
            <li id='link1' style='float:left'><a href='../' ><img style='padding: 0 0 0 10; max-height:30px;' src='../evalumate_logo_white.png'></a></li>
            <li id='link2' style='float:left'><a href='../'>EVALUMATE</a></li>
            <li style='float:right'><a href='../logout'>Log Out</a></li>
            <li style='float:right'><a class='active' href=''>Stats</a></li>
            <li style='float:right'><a href='../create_assessment'>Create Assessment</a></li>
            <li style='float:right'><a href='../add_course'>Add Course</a></li>
            <li style='float:right'><a href='../course_view'>Course</a></li>
            <li style='float:right'><a href='../'>Dashboard</a></li>
            <li style='float:right; background-color:#253f52;' ><a style='padding: 14 20 13 5'><label style='font-size: 15; font-family:Play;'>".$_SESSION['guestname']."</label><br><label style='font-size: 12; font-family:Play;'>".ucfirst($_SESSION['guesttype'])."</label></a></li>
            <li id='link1' style='float:right; background-color:#253f52;'><img style='padding: 15 15 4 15; max-height:30px;' src='../user_profile.png'></li>
          </ul></div><br><br><br><br>
          ";
          $sql2 = "SELECT * FROM (SELECT * FROM (SELECT quiz_id, AVG(grade) as avg FROM grades GROUP BY quiz_id)as t1 WHERE quiz_id = '". $_SESSION['quiz_stats_id']. "') t2 LEFT JOIN quiz ON quiz.id = quiz_id";
          //echo $sql2;
          $yes_results = False;
          $result2 = $conn->query($sql2);
          if ($result2->num_rows > 0) {   
            $yes_results= True;
         while($row = $result2->fetch_assoc()) {
             $final_score1 = $row['avg'];

             echo "<h1>".$row['name']."</h1>";
                ?>
 
<div id="container">
    <div id="card">
    <?php

            $range1 = 0; $range2 = 0; $range3 = 0; $range4 = 0; $range5 = 0;

            $sql11 = "SELECT COUNT(id) as c FROM grades where grade >= 0 and grade < 20 and quiz_id  = " . $_SESSION['quiz_stats_id'];
            $result11 = $conn->query($sql11);
            if ($result11->num_rows > 0) { 
                while($row = $result11->fetch_assoc()) {
                        $range1 = $row['c'];
                }
            }  

            $sql12 = "SELECT COUNT(id) as c FROM grades where grade >= 20 and grade < 40 and quiz_id  = " . $_SESSION['quiz_stats_id'];
            $result12 = $conn->query($sql12);
            if ($result12->num_rows > 0) { 
                while($row = $result12->fetch_assoc()) {
                        $range2 = $row['c'];
                }
            }  

            $sql13 = "SELECT COUNT(id) as c FROM grades where grade >= 40 and grade < 60 and quiz_id  = " . $_SESSION['quiz_stats_id'];
            $result13 = $conn->query($sql13);
            if ($result13->num_rows > 0) { 
                while($row = $result13->fetch_assoc()) {
                        $range3 = $row['c'];
                }
            }  

            $sql14 = "SELECT COUNT(id) as c FROM grades where grade >= 60 and grade < 80 and quiz_id  = " . $_SESSION['quiz_stats_id'];
            $result14 = $conn->query($sql14);
            if ($result14->num_rows > 0) { 
                while($row = $result14->fetch_assoc()) {
                        $range4 = $row['c'];
                }
            }  

            $sql15 = "SELECT COUNT(id) as c FROM grades where grade >= 80 and grade <= 100 and quiz_id  = " . $_SESSION['quiz_stats_id'];
            $result15 = $conn->query($sql15);
            if ($result11->num_rows > 0) { 
                while($row = $result15->fetch_assoc()) {
                        $range5 = $row['c'];
                }
            }  

    ?>
      <div id="box">
        <div id="percent">
        <?php


        if($final_score1 < 50) {
            $stroke_colour = "#ff0b00";
        }
        else if ($final_score1 >=50 && $final_score1 < 65) {
            $stroke_colour = "#ffd600";
        }
        else if ($final_score1 >=65 && $final_score1 < 80) {
            $stroke_colour = "#8cc13e";
        }
        else {
            $stroke_colour = "#00ff43";
        }


          echo "<style>
          
          #card:nth-child(1) #svg #circle:nth-child(2) {
            stroke-dashoffset: calc(440 - (440 *".round($final_score1,2) .") / 100);
            stroke: ".$stroke_colour.";
          }
          </style>
          ";
          ?>
          <svg id='svg'> 
            <circle id='circle' cx="70" cy="70" r="70"></circle>
            <circle id='circle' cx="70" cy="70" r="70"></circle>
          </svg>
          <div id="number">
            <h2><?php echo round($final_score1,2); ?><span>%</span></h2>
          </div>
        </div>
        <h2 id="text">Class Average</h2>
      </div>
  </div>
        </div>
        
    <?php
         }
         }
         if ($yes_results){
         echo "<script type='text/javascript' src='https://www.gstatic.com/charts/loader.js'></script>
         <script type='text/javascript'>
           google.charts.load('current', {packages:['corechart']});
           google.charts.setOnLoadCallback(drawChart);
           function drawChart() {
             var data = google.visualization.arrayToDataTable([
               ['0 - 20', '$range1', { role: 'style' } ],
               ['20 - 40', $range2, '#18b54f'],
               ['40 - 60', $range3, '#18b54f'],
               ['60 - 80', $range4, '#18b54f'],
               ['80 - 100', $range5, '#18b54f']
             ]);
       
             var view = new google.visualization.DataView(data);
             view.setColumns([0, 1,
                              { calc: 'stringify',
                                sourceColumn: 1,
                                type: 'string',
                                role: 'annotation' },
                              2]);
       
             var options = {
               height: 300,
               backgroundColor: 'transparent',
               bar: {
                   groupWidth: '50%'
               },
               legend: { 
                   position: 'none'
               },
               hAxis: { 
                   title: 'Grade Range',
                   titleTextStyle: {
                       color: '#ccc'
                   },
                   textStyle:{color: '#CCC'},
               },
               vAxis: {
                   title: 'Number of Students',
                   titleTextStyle: {
                       color: '#ccc'
                   },
                   textStyle: {
                       color: '#CCC'
                   }
               },
               titleTextStyle: {
                   color: '#ccc'
               },
               legendTextStyle: {
                   color: '#ccc'
               }
             };
             var chart = new google.visualization.ColumnChart(document.getElementById('columnchart_values'));
             chart.draw(view, options);
             var chart = new google.visualization.ColumnChart(document.getElementById('columnchart_values2'));
             chart.draw(view, options);
         }


         window.onresize = drawChart;

         </script>
  
    <div style='padding-left: 150px;' id='columnchart_values'></div><br><br>
      ";
        }

         $sql2 = "SELECT * FROM grades LEFT JOIN users ON grades.user_id = users.id WHERE grades.quiz_id = " . $_SESSION['quiz_stats_id'];
         //echo $sql2;
         $result2 = $conn->query($sql2);
         if ($result2->num_rows > 0) {   
             echo"<div id='dash_quiz_table' align='center'><table ><tr><th>Student</th><th>Grade</th></tr>";
           while($row = $result2->fetch_assoc()) {
               echo "<tr><td>".$row['name']."</td>";
               echo "<td>".$row['grade']."%</td>";
             //  echo "<td>".round($row['avg'],2)."%</td>";
               echo "</tr>";
     
           }
           echo "</table></div>";
       }
       else{
           echo "<label>No one has attempted the quiz yet. Check back later.</label>";
       }

       



        }
    }
}

?>

<br><br>

<?php 
echo "</body><br><br><div id='main_footer'><p id='footerinfo'>Evalumate Cloud Project</p><p id='ipinfo'>Server IP : ".file_get_contents( "https://allansantosh.com/cloud_project/getip.php" )."</p></div>";
?>