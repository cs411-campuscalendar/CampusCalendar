<?php
$servername = "campuscalendar.web.engr.illinois.edu";
$username = "campusca_admin";
$password = "adminpassword";
$dbname = "campusca_events";

$conn = new mysqli($servername, $username, $password, $dbname);

if(mysqli_connect_error()){
    die("Databse failed: ".mysqli_connect_error());
}

if ($_GET) {
   $facebook_id = $_GET['facebook_id'];
} else {
   $facebook_id = $facebook_id[1];
}



$sql = " 
    SELECT *
    FROM user
    WHERE facebook_id = '$facebook_id'
";

$result = $conn->query($sql);
 
if ($result->num_rows == 1) {
	echo 'true';
} else {
	echo 'false';
}

$conn->close();
?>