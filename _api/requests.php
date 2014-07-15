<?php

function clean($str) { return htmlentities(stripslashes($str)); } 

$action = clean($_GET['action']); 
$dbhost = "localhost"; 
$dbname = "EnvironTestTracker"; 
$dbuser = "root"; 
$dbpass = "root"; 
try { 
	$dblink = mysqli_connect($dbhost,$dbuser,$dbpass,$dbname) or die(); 
} catch (mysqli_sql_exception $e) {
	echo '{"error":343,"message":"We cannot connect you to the database. Please try agian later. "}'; 
}

switch($action) {
	case 'push': 
		if(!$_GET['id']||!$_GET['data']) {
			echo '{"error":504,"message":"Information missing"}'; 
			break; 
		}

		$sessionid = clean($_GET['id']); 
		$jsondata = clean($_GET['data']); 
		$result = mysqli_query($dblink,"INSERT INTO sessions (session, jsondata) VALUES ('$sessionid','$jsondata');"); 
		if($result) echo '{"success":"success","message":"Entry added"}'; 
		else echo '{"error":506,"message":"Data not found."}'; 
		break; 
	case 'pull': 
		if(!$_GET['id']) {
			echo '{"error":504,"message":"Information missing"}'; 
			break; 
		}

		$sessionid = clean($_GET['id']); 
		$result = mysqli_query($dblink,"SELECT * FROM sessions WHERE session='$sessionid'"); 
		$found = false; 
		while($row = mysqli_fetch_array($result)) {
			echo '{"success":"success","message":"Entry found","data":'.$row[2].'}'; 
			$found = true; 
			// $str = $row[2]; //json_encode($row[2]); 
			// echo $row[2]; //str_replace("\\","",$str); 
			break; 
		}

		if(!$found) echo '{"error":506,"message":"Data not found."}'; 
		break; 
	default: 
		echo '{"error":504,"message":"Information missing"}'; 
		break; 
}

?>