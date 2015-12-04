<?php

function Parsing($url, $description, $type){
  $agent= 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)';

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_VERBOSE, true);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_USERAGENT, $agent);
  curl_setopt($ch, CURLOPT_URL,$url);
  $output =curl_exec($ch);
  // var_dump($result);
  $dom = new DOMDocument();
  @$dom->loadHTML($output);

  $finder = new DomXPath($dom);
  
  
  

  //name of the game
  $name_obj = $finder->query('//div[contains(@class,"schedule_game_opponent_name")]');
  $name_arr = array();

  //printing the names of the objects inside (each opponent)
  foreach ($name_obj as $entry) {
      array_push($name_arr, trim($entry->nodeValue) );

  }


  //location of the game
  $location_obj = $finder->query('//div[contains(@class,"schedule_game_location")]');
  $location_arr = array();

  foreach ($location_obj as $entry) {
      array_push($location_arr, trim($entry->nodeValue) );
  }

  //date
  $date_obj = $finder->query('//div[contains(@class,"schedule_game_opponent_date")]');
  $date_arr = array();

  foreach ($date_obj as $entry) {
      $datevalue = trim($entry->nodeValue);
      $datelen = strlen($datevalue);
      
      $datearray = explode('.', $datevalue);
      $year = "20$datearray[2]";
      $day = "$datearray[1]";
      $month = "$datearray[0]";
      if(strlen($day) < 2){
           $day = "0$day";
      }
      if(strlen($month) < 2){
           $month = "0$month";
      }
      
      array_push($date_arr, "$year.$month.$day");
  }

  //time
  $time_obj = $finder->query('//div[contains(@class,"schedule_game_opponent_time")]');
  $time_arr = array();

  foreach ($time_obj as $entry) {
      array_push($time_arr, $entry->nodeValue );
  }



  //number of times the description should be repeated in the array for mapping
  $repeat =  count($name_arr);
  //making duplicates of description to map
  $description_arr = array();
  for ($i = 0; $i < $repeat; $i++){
    array_push($description_arr, $description);
  }

  $type_arr = array();
  for ($i = 0; $i < $repeat; $i++){
    array_push($type_arr, $type);
  }

  $final_array = array_map(null, $name_arr, $description_arr, $date_arr, $location_arr, $type_arr);

  return $final_array;
}

$all_sports_sites =array(
array("http://fightingillini.com/schedule.aspx?path=baseball", "Illini Baseball Game", "Baseball"),
array("http://fightingillini.com/schedule.aspx?path=mbball&", "Illini Mens Basketball Game", "Mens Basketball"),
array("http://fightingillini.com/schedule.aspx?path=mcross&", "Illini Mens Cross Country Game", "Cross Country"),
array("http://fightingillini.com/schedule.aspx?path=football&", "Illini Football Game", "Football"),
array("http://fightingillini.com/schedule.aspx?path=mgolf&", "Illini Mens Golf Game", "Mens Golf"),
array("http://fightingillini.com/schedule.aspx?path=mgym&", "Illini Mens Gymnastics Game", "Mens Gymnastics"),
array("http://fightingillini.com/schedule.aspx?path=mten&", "Illini Mens Tennis Game", "Mens Tennis"),
array("http://fightingillini.com/schedule.aspx?path=mtrack&", "Illini Mens Track Game", "Mens Track"),
array("http://fightingillini.com/schedule.aspx?path=wrestling&", "Illini Womens Wrestling Game", "Womens Wrestling"),
array("http://fightingillini.com/schedule.aspx?path=wbball&", "Illini Womens Baseball Game", "Womens Baseball"),
array("http://fightingillini.com/schedule.aspx?path=wcross&", "Illini Womens Cross Country Game", "Womens Cross Country"),
array("http://fightingillini.com/schedule.aspx?path=wgolf&", "Illini Womens Golf Game", "Womens Golf"),
array("http://fightingillini.com/schedule.aspx?path=wgym&", "Illini Womens Gymnastics Game", "Womens Gymnastics"),
array("http://fightingillini.com/schedule.aspx?path=wsoc&", "Illini Womens Soccer Game", "Womens Soccer"),
array("http://fightingillini.com/schedule.aspx?path=softball&", "Illini Softball Game", "Softball"),
array("http://fightingillini.com/schedule.aspx?path=wswim&", "Illini Womens Swimming Game", "Womens Swimming"),
array("http://fightingillini.com/schedule.aspx?path=wten&", "Illini Womens Tennis Game", "Womens Tennis"),
array("http://fightingillini.com/schedule.aspx?path=wtrack&", "Illini Womens Track Game", "Womens Track"),
array("http://fightingillini.com/schedule.aspx?path=wvball&", "Illini Womens Volleyball Game", "Womens Volleyball"));

$sports_events = [];
foreach ($all_sports_sites as $sport ) {
     $sports_events = array_merge($sports_events, Parsing($sport[0], $sport[1], $sport[2]));
}

$num_events = count($sports_events);


$servername = "campuscalendar.web.engr.illinois.edu";
$username = "campusca_admin";
$password = "adminpassword";
$dbname = "campusca_events";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

if(mysqli_connect_error()){
    die("Databse failed: ".mysqli_connect_error());
}

function isInDB($connection, $name, $description, $date, $location){
    $sql = "SELECT * FROM event WHERE name='$name' and date='$date' and location='$location'";
    //echo $sql."\n";

    $result = $connection->query($sql);
    //echo $result->num_rows."\n";
    return $result->num_rows;
}

for($i = 0; $i < $num_events; $i++){
    $current = $sports_events[$i];
    
    $name = $current[0];
    $description = $current[1];
    $date = $current[2];
    $location = $current[3];
    $type = $current[4];

    $datelen = strlen($date);
    if($datelen < 9 || $datelen > 11 || $date[3] == "5"){
      	continue;
    }	

    $inDb = isInDB($conn, "$type - $name vs UIUC", $description, $date, $location);
	
    if($inDb < 1){
        #add to events db
        $sql = "INSERT INTO event (name, description, date, location) VALUES ('$type - $name vs UIUC', '$description', '$date', '$location')";

	if ($conn->query($sql) === TRUE) {
		$id = $conn->insert_id;
   		echo "event", "id=", $id, "     inserted successfully"."\n\n"; 
   		
   		$sports_sql = "INSERT INTO sport_event (id,type_of_sport,team,opponent) VALUES ('$id', '$type', 'Fighting Illini', '$name')";
   		
   		if ($conn->query($sports_sql) === TRUE) {
   			echo "sport_event", "id=", $id, " inserted successfully"; 
		} else {
   			echo "Error: " . $sports_sql . "<br>" . $conn->error;
		}
   		
	} else {
   		echo "Error: " . $sql . "<br>" . $conn->error;
	}
    }
}

/////CSParse

function CSParse($url){
   
  $source = file_get_contents($url);
  $doc = new DOMDocument;
  $doc->loadHTML($source);
  $finder = new DomXPath($doc);


  //getting the list of all URLs for each event at CS Department
    $url_list = array();

    foreach ($doc->getElementsByTagName('a') as $node)
    {
      $pos = strpos($node->getAttribute("href"), "/calendar/detail");

      if ( $pos  ===  0)
        array_push($url_list, $node->getAttribute("href"));

    }


  function each_event_detail($url){

    $illinois = "http://illinois.edu";
    $illinois .= $url;
    $source = file_get_contents($illinois);
    $doc = new DOMDocument;
    $doc->loadHTML($source);
    $finder = new DomXPath($doc);

    //name of the event
    $name_obj = $finder->query('//h2[contains(@class,"detail-title")]');
    $name_arr = array();

    //printing the names of the objects inside (each opponent)
    foreach ($name_obj as $entry) {
        array_push($name_arr, $entry->nodeValue );
    }


    //location of the event
    $date_obj = $finder->query('//*[contains(text(),"Date")]/following-sibling::span');
    $date_arr = array();

    foreach ($date_obj as $entry) {
        $date = $entry->nodeValue;
        if(strlen($date)>15)
          return null;
        $date = date_create($date);
        $date = date_format($date, 'Y.m.d');
        array_push($date_arr,  $date);

    }

    $location_obj = $finder->query('//*[contains(text(),"Location")]/following-sibling::span');
    $location_arr = array();

    foreach ($location_obj as $entry) {
        array_push($location_arr, $entry->nodeValue );

    }

    $sponsor_obj = $finder->query('//*[contains(text(),"Sponsor")]/following-sibling::span');
    $sponsor_arr = array();

    foreach ($sponsor_obj as $entry) {
        array_push($sponsor_arr, $entry->nodeValue );

    }

    $description_arr = array();
    $description_obj = $finder->query('//div[contains(@class,"description-row")]');
    foreach ($description_obj as $entry) {
      $each_desc = $entry->nodeValue ;
      if(empty($each_desc)){
        array_push($description_arr, null);
      }else{
        array_push($description_arr, $entry->nodeValue );
      }
    }

    $one_event = array_map(null, $name_arr, $description_arr, $date_arr, $location_arr, $sponsor_arr);

    return $one_event[0];
  }


  $final_array = array();
  foreach ($url_list as $key) {
    $each = each_event_detail($key);
    array_push($final_array, $each );
  }

return $final_array;
}
//print_r(CSParse());

////
$all_academic_sites =array(
array("http://illinois.edu/calendar/list/7") ,//general events,
array("http://illinois.edu/calendar/list/2654"), //cs dept,
array("http://illinois.edu/calendar/list/2568") ,//civil dept,
array("http://illinois.edu/calendar/list/3731") //mech dept,
);
//$academic_events = CSParse(); 

$academic_events = [];
foreach ($all_academic_sites as $academic ) {
     $academic_events = array_merge($academic_events, array_filter($academic));
}

$academic_events = array_filter(CSParse("http://illinois.edu/calendar/list/7"));
$num_academic_events = count($academic_events);

foreach($academic_events as $current){    
    $name = $current[0];
    $description = $current[1];
    $date = $current[2];
    $location = $current[3];
    $sponsor = $current[4];
	echo $current, "<br>";
	echo $name, $description, $date, $location, "<br>";
    $inDb = isInDB($conn, "$name", $description, $date, $location);
	
    if($inDb < 1){
        #add to events db
        $sql = "INSERT INTO event (name, description, date, location) VALUES ('$name', '$description', '$date', '$location')";

	if ($conn->query($sql) == TRUE) {
		$id = $conn->insert_id;
   		echo "event", "id=", $id, "     inserted successfully"."\n\n"; 
   		
   		$academic_sql = "INSERT INTO academic_event (id,sponsor,department) VALUES ('$id', '$sponsor', 'CS')";
   		
   		if ($conn->query($academic_sql) === TRUE) {
   			echo "academic_event", "id=", $id,  " inserted successfully"; 
		} else {
   			echo "Error: " . $academic_sql . "<br>" . $conn->error;
		}
   		
	} else {
   		echo "Error: " . $sql . "<br>" . $conn->error;
	}
    }
    
}

?>
