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
    $id = (int)$_GET['id'];
    $facebook_id = $_GET['facebook_id'];
} else {
    $id = (int)$argv[1];
    $facebook_id = argv[2];
}

$sql_check = "SELECT * from user
	      WHERE facebook_id = '$facebook_id'
	      AND admin = 1";
     
$result = $conn->query($sql_check);
$num_rows = $result->num_rows;

if($num_rows == 0)
	return;
	
// sql to delete a record
$sql = "DELETE FROM event  WHERE id=$id";

if ($conn->query($sql) === TRUE) {
    echo "id = ", "$id", " deleted successfully";
} else {
    echo "Error deleting record: " . $conn->error;
}

$conn->close();
?>