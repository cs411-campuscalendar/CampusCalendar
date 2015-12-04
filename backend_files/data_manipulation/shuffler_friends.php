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

function befriends($id_a, $id_b, $connection)
{
	$sql = "INSERT INTO FriendsWith (facebook_id_a, facebook_id_b)
		VALUES ('$id_a', '$id_b')";		
		
	$result = $connection->query($sql);

}
$conn = new mysqli($servername, $username, $password, $dbname);

if(mysqli_connect_error())
{
	die("Databse failed: ".mysqli_connect_error());
}

$user_ids_1 = get_user_ids($conn);
$user_ids_2 = get_user_ids($conn);

for($i = 0; $i < 10; $i++)
{
	shuffle($user_ids_1);
	shuffle($user_ids_2);
	$j = 0;
	foreach($user_ids_1 as $user)
	{
		befriends($user, $user_ids_2[$j], $conn);	
		$j++;
	}
}
?>