<?php
$servername = "campuscalendar.web.engr.illinois.edu";
$username = "campusca_admin";
$password = "adminpassword";
$dbname = "campusca_events";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

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

// sql to delete a record
$sql = "DELETE FROM IsAttending WHERE IsAttending.facebook_id = $facebook_id AND IsAttending.event_id = $event_id";

if ($conn->query($sql) === TRUE) {
    echo "id = ", "$id", " deleted successfully";
} else {
    echo "Error deleting record: " . $conn->error;
}

$conn->close();
?>