<?php
$servername = "campuscalendar.web.engr.illinois.edu";
$username = "campusca_admin";
$password = "adminpassword";
$dbname = "campusca_events";


function get_user_ids($connection)
{
	$sql = "SELECT facebook_id 
		FROM user";

	$result = $connection->query($sql);
	$id_array = array();

	if ($result->num_rows > 0) 
	{
		$i = 0;
	    	while($row = $result->fetch_assoc()) 
	    	{
		        $id_array[$i++] = $row['facebook_id'];
	    	}
	}
	
	return $id_array;
}

function get_events($connection)
{
	$sql = "SELECT id FROM event";
	
	$result = $connection->query($sql);
	$id_array = array();

	if ($result->num_rows > 0) 
	{
		$i = 0;
	    	while($row = $result->fetch_assoc()) 
	    	{
		        $id_array[$i++] = $row['id'];
	    	}
	}
	
	return $id_array;
}
function attends_event($event_id, $facebook_id, $connection)
{
	$sql = "INSERT INTO IsAttending (event_id, facebook_id)
		VALUES ($event_id, '$facebook_id')";		
		
	$result = $connection->query($sql);

}
$conn = new mysqli($servername, $username, $password, $dbname);

if(mysqli_connect_error())
{
	die("Databse failed: ".mysqli_connect_error());
}


$events_array = get_events($conn);
$length = count($events_array);
$user_ids_1 = get_user_ids($conn);
$user_ids_2 = get_user_ids($conn);

for($i = 0; $i < 30; $i++)
{
	shuffle($events_array);
	shuffle($user_ids_1);
	shuffle($user_ids_2);
	for($j = 0; $j < 100; $j++)
	{
		attends_event($events_array[$j], $user_ids_1[$i], $conn);	
		attends_event($events_array[$j], $user_ids_2[$i], $conn);	
	}
}


?>