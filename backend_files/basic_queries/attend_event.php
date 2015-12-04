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
   $event_id =(int) $_GET['event_id'];
   $facebook_id =(int) $_GET['facebook_id'];
} else {
   $event_id = (int)$argv[1];
   $facebook_id =(int) $argv[2];
}

$sql = "INSERT INTO IsAttending (event_id, facebook_id)
VALUES ($event_id, '$facebook_id')";

if ($conn->query($sql) === TRUE) {
   echo "relation", " event_id = ", $event_id, " facebook_id=", $facebook_id, "   inserted successfully"; 
} else {
   echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();

?>