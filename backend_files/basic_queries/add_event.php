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
   $facebook_id = $_GET['facebook_id'];
} else {
   $name = $argv[1];
   $description = $argv[2];
   $date = $argv[3];
   $location = $argv[4];
  $facebook_id = $argv[5];

}

$sql_check = "SELECT * from user
	      WHERE facebook_id = '$facebook_id'
	      AND admin = 1";
     
$result = $conn->query($sql_check);
$num_rows = $result->num_rows;

if($num_rows == 0)
	return;


$sql = "INSERT INTO event (name, description, date, location)
VALUES ('$name', '$description', '$date', '$location')";

if ($conn->query($sql) === TRUE) {
   echo "event", "id=", $id, " name=", $name, " description=", $description, " date=", $date, " location=", $location, "     inserted successfully"; 
} else {
   echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>