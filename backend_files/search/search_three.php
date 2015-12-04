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
    $cond1 = $_GET['cond1'];
    $cond2 = $_GET['cond2'];
    $cond3 = $_GET['cond3'];
} else {
	die("Enter proper parameters");
}

$sql = "SELECT * FROM event WHERE $cond1 AND $cond2 AND $cond3";

$result = $conn->query($sql);
$return_arr = array();

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