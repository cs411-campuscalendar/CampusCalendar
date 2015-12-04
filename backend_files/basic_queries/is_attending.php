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

$sql = "SELECT * FROM IsAttending WHERE IsAttending.facebook_id = $facebook_id AND IsAttending.event_id = $event_id";
$result = $conn->query($sql);
$num_rows = $result->num_rows + "\n";


if ($num_rows > 0) {
	$attending = true;
	echo json_encode(true);

}else{
	$num_rows = false;
	echo json_encode(false);
}


$conn->close();

?>