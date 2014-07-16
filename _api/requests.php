<?php

require_once('../_incs/library.php'); 

$action = clean($_GET['action']); 

$db = new DATABASE($dbhost,$dbname,$dbuser,$dbpass); 
$dblink = $db->connect(); 
if($dblink==false) 
	$action = "dberror"; 

switch($action) {
	case 'session': 
		session_start(); 
		session_regenerate_id(); 
		echo '{"success":"success","message":"","data":{"session":"'.session_id().'"}}'; 
		//session_destroy(); 
		break; 
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
	case 'options': 
		$result = mysqli_query($dblink,"SELECT session FROM sessions"); 
		$out = '{"success":"success","message":"Entry found","data":['; 
		while($row = mysqli_fetch_array($result)) {
			$out .= '"'.$row['session'].'",'; 
		}
		$out = substr($out, 0, strlen($out)-1); 
		$out .= ']}'; 
		echo $out; 
		break; 
	case 'dberror': 
		echo '{"error":343,"message":"We cannot connect you to the database. Please try agian later. "}'; 
		break; 
	default: 
		echo '{"error":504,"message":"Information missing"}'; 
		break; 
}

?>