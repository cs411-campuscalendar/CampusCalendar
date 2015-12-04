<?php
$servername = "campuscalendar.web.engr.illinois.edu";
$username = "campusca_admin";
$password = "adminpassword";
$dbname = "campusca_events";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

if(mysqli_connect_error()){
    die("Databse failed: ".mysqli_connect_error());
}

if ($_GET) {
   $name = $_GET['name'];
   $description = $_GET['description'];
   $date =$_GET['date'];
   $location = $_GET['location'];
   $sponsor = $_GET['sponsor'];
   $department =$_GET['department'];
} else {
   $name = $argv[2];
   $description = $argv[2];
   $date = $argv[3];
   $location = $argv[4];
   $sponsor = $argv[5];
   $department = $argv[6];
}

$sql = "INSERT INTO event (name, description, date, location)
VALUES ('$name', '$description', $date, '$location')";

if ($conn->query($sql) === TRUE) {
   echo "event", "id=", $conn->insert_id, " name=", $name, " description=", $description, " date=", $date, " location=", $location, "     inserted successfully", "<br>"; 
} else {
   echo "Error: " . $sql . "<br>" . $conn->error;
}

$recent_id = $conn->insert_id;

$sql_academic = "INSERT INTO academic_event (id, sponsor, department)
VALUES ($recent_id, '$sponsor', '$deparment')";

if ($conn->query($sql_academic) === TRUE) {
   echo "academic_event", "id=", $recent_id, " sponsor=", $sponsor, " deparment=", $department, " inserted successfully"; 
} else {
   echo "Error: " . $sql . "<br>" . $conn->error;
}


$conn->close();
?>