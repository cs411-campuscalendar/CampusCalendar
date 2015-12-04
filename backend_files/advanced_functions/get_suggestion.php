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
	$sql = "SELECT facebook_id
		FROM IsAttending
		WHERE facebook_id = '$user_id'";
	
	$result = $connection->query($sql);
	
	if($result->num_rows == 0)
		return 0;
	else
		return 1;

}

function get_event($event_id, $connection)
{
	$sql = "SELECT *
		FROM event
		WHERE id = $event_id";
	$result = $connection->query($sql);
	
	if ($result->num_rows == 1) 
	{
		while($row = $result->fetch_assoc()) 
   		{
	    		$row_array = array();
	        	$row_array['id'] = $row['id'];
	    		$row_array['name'] = $row['name'];
	    		$row_array['description'] = $row['description'];
	    		$row_array['date'] = $row['date'];
		    	$row_array['location'] = $row['location'];
    		}
    		return $row_array;
    	}
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

if(check_id_exists($facebook_id, $conn) == 0)
{
	$return_arr = array();
	$sql = "SELECT * FROM event ORDER by date ASC";
	$result = $conn->query($sql);
	$i = 0;
	
	if ($result->num_rows > 0) {
	    while($row = $result->fetch_assoc()) {
	    	if($i == 20)
	    		break;
	    		
	    	$row_array = array();
	        $row_array['id'] = $row['id'];
	    	$row_array['name'] = $row['name'];
	    	$row_array['description'] = $row['description'];
	    	$row_array['date'] = $row['date'];
	    	$row_array['location'] = $row['location'];
	    	array_push($return_arr,$row_array);
	    	$i++;
	    }
	}
	
	echo json_encode($return_arr);
	$conn->close(); 
	return;
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
$event_probablity = array();

foreach($event_ids as $event_id)
{
	//echo $event_id, " : ";

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
		$event_probablity["$event_id"] = floatval(($sum_similarity / count($users_attending)));
	}
	else
	{
		$event_probablity["$event_id"] = 0.0;
	}
	
	//echo "sum sim ", $sum_similarity, "count : ", count($users_attending), "<br>";
	//echo $event_probablity["$event_id"], "<br>";
}


//echo "-------SORTED ----- <br>";
arsort($event_probablity);


$return_arr = array();

$i = 0;
foreach ($event_probablity as $key => $value) 
{	
	if($i == 20)
	{
		break;
	}
	$i++;
	
	array_push($return_arr,get_event($key, $conn));
}

echo json_encode($return_arr);
$conn->close();
   
?>