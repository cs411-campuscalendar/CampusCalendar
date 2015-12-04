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
   $picture = $_GET['picture'];
   $first_name = $_GET['first_name'];
   $university = $_GET['university'];
   $address = $_GET['address'];
   $gender = $_GET['gender'];
   $facebook_id =(int) $_GET['facebook_id'];
   $email = $_GET['email'];
   $last_name = $_GET['last_name'];
} else {
   $picture = $argv[1];
   $first_name = $argv[2];
   $university = $argv[3];
   $address = $argv[4];
   $gender = $argv[5];
   $facebook_id = $argv[6];
   $email = $argv[7];
   $last_name = $argv[8];
}

$sql = "INSERT INTO user (first_name, last_name, email, facebook_id, address, gender, university, picture)
VALUES ('$first_name', '$last_name', '$email', '$facebook_id', '$address', '$gender', '$university', '$picture')";

if ($conn->query($sql) === TRUE) {
   echo "user", "picture=", $picture, " first_name=", $first_name, " university=", $university, " address=", $address, " gender=", $gender," facebook_id=", $facebook_id ," email=", $email," last_name=" , $last_name, "  inserted successfully"; 
} else {
   echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();

?>