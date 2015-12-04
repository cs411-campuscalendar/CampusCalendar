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
   $id_a = $_GET['id_a'];
   $id_b =$_GET['id_b'];
} else {
   $id_a = $argv[1];
   $id_b = $argv[2];
}

$sql = "INSERT INTO FriendsWith (facebook_id_a, facebook_id_b)
VALUES ('$id_a', '$id_b')";

if ($conn->query($sql) === TRUE) {
   echo "relation", " id_a = ", $id_a, " id_b =", $id_b, "   inserted successfully"; 
} else {
   echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();

?>