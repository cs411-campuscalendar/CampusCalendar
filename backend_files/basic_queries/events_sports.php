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

$return_arr = array();
$sql = "SELECT * FROM event, sport_event WHERE event.id = sport_event.id ORDER by date ASC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
    	$row_array = array();
        $row_array['id'] = $row['id'];
    	$row_array['name'] = $row['name'];
    	$row_array['description'] = $row['description'];
    	$row_array['date'] = $row['date'];
    	$row_array['location'] = $row['location'];
    	array_push($return_arr,$row_array);
    }
}

echo json_encode($return_arr);
$conn->close();    
?>