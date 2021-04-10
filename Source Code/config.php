<head>
  <title>EvaluMate</title>
  <link rel="stylesheet" href="../style.css">
  <link rel="stylesheet" href="../graph.css">
  <link rel="icon" type="image/x-icon" href="../favicon.png">
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Permanent+Marker&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Bree+Serif&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Play&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Cairo&display=swap" rel="stylesheet">

  <script>
window.onscroll = function() {myFunction()};

var navbar = document.getElementById("navbar");
var sticky = navbar.offsetTop;

function myFunction() {
  if (window.pageYOffset >= sticky) {
    navbar.classList.add("sticky")
  } else {
    navbar.classList.remove("sticky");
  }
}
    </script>
<?php
//$hostname = "oombo-capstone-database.cluster-cgsoknk0aocd.us-east-2.rds.amazonaws.com";
ini_set('display_errors', 1);
$hostname = "cloud-project-database.csm8lv8xyxx3.us-east-1.rds.amazonaws.com";
$username = "admin";
$password = "GSpGvuDVjQ6gFZUdP4gGvP";
$database = "cloud-project-database";

$conn = new mysqli($hostname, $username, $password, $database);
if ($conn->connect_error) {
    echo "error";
    die("Connection failed: " . $conn->connect_error);
  }
  else{
  }

date_default_timezone_set('America/Toronto');

//srequire 'vendor/autoload.php';

# Create your MySQL database connection
//$db = new mysqli($hostname, $username, $password, $database);

# Create the session handler using that connection and pass it the name of the table
# The handler will try to create it if it doesn't already exist.
//$handler = new \Programster\SessionHandler\SessionHandler($db, 'sessions');

 ?>
</head>