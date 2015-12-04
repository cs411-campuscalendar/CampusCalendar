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
echo $length, "<br>";
$user_ids = get_user_ids($conn);

for($i = 0; $i < 10; $i++)
{
	shuffle($events_array);
	$j = 0;
	foreach($user_ids as $user)
	{
		attends_event($events_array[ rand(0,100000) % $length ], $user, $conn);	
		$j++;
	}
}


?>