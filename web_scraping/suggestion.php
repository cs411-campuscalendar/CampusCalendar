<?php

$servername = "campuscalendar.web.engr.illinois.edu";
$username = "campusca_admin";
$password = "adminpassword";
$dbname = "campusca_events";

//returns an array of event_ids
function get_events_attended($user_id, $connection)
{
	$sql = "SELECT DISTINCT event.id 
		FROM event, IsAttending
		WHERE event.id = IsAttending.event_id
		AND facebook_id ='$user_id'";

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

//returns an array of event_ids
function get_events_not_attended($user_id, $connection)
{
	$sql = "SELECT id FROM event
		WHERE event.id <> ALL (
				SELECT DISTINCT event.id as id
				FROM event, IsAttending
				WHERE event.id = IsAttending.event_id
				AND facebook_id ='$user_id')";
				
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

function get_user_ids($user_id, $connection)
{
	$sql = "SELECT facebook_id 
		FROM user
		WHERE facebook_id <> '$user_id'";

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

function calculate_similarity_index($A_perm, $N_perm, $A_2, $N_2)
{
	$index;
	settype($index, "float");
	$index = count(array_intersect($A_perm, $A_2)) + count(array_intersect($N_perm, $N_2)); 
	
	
	// - count(array_intersect($A_perm, $N_2)) - count(array_intersect($A_2, $N_perm))
	$index = $index / count(array_unique($A_perm + $N_perm + $A_2 + $N_2));
	return $index;
}

function get_attendance($event_id, $user_id, $connection)
{
	$sql = "SELECT facebook_id
		FROM IsAttending
		WHERE IsAttending.event_id = $event_id
		AND facebook_id <> '$user_id'";
	
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

function check_id_exists($user_id, $connection)
{
	$sql = "SELECT COUNT(facebook_id)
		FROM IsAttending
		WHERE facebook_id = '$user_id'";
	
	$result = $connection->query($sql);
	
	

}

$conn = new mysqli($servername, $username, $password, $dbname);

if(mysqli_connect_error())
{
	die("Databse failed: ".mysqli_connect_error());
}

//extract facebook_id
if ($_GET) {
   $facebook_id = $_GET['facebook_id'];
} else {
   $facebook_id = $argv[1];
}

$A_perm = get_events_attended($facebook_id, $conn);
$N_perm = get_events_not_attended($facebook_id, $conn);
$user_ids = get_user_ids($facebook_id, $conn);
//echo "count : ", count($user_ids), "<br>";
$similarity_indices = array();

foreach($user_ids as $id)
{
	$A_2 = get_events_attended($id, $conn);
	$N_2 = get_events_attended($id, $conn);
	$similarity_indices["$id"] = calculate_similarity_index($A_perm, $N_perm, $A_2, $N_2);
	//echo $id, " ------ ", $similarity_indices["$id"], "<br>";
}

$event_ids = get_events($conn);

foreach($event_ids as $event_id)
{
	echo $event_id, " : ";

	$event_probablity = array();

	$users_attending = get_attendance($event_id, $facebook_id, $conn);
	
	$sum_similarity;
	settype($sum_similarity, "float");
	
	$sum_similarity = 0.0;
	foreach($users_attending as $user)
	{
		//echo "sim of user_index(" , $user, ") is ---", $similarity_indices["$user"], "<br>";
		$sum_similarity += $similarity_indices["$user"];
	}
	
	if(count($users_attending) != 0)
	{
		$event_probablity['event_id'] = $sum_similarity / count($users_attending);
	}
	//echo "sum sim ", $sum_similarity, "count : ", count($users_attending), "<br>";
	echo $event_probablity['event_id'], "<br>";
}
?>