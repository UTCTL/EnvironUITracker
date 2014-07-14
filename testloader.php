<?php

$id = $_GET['id']; 

$file = file_get_contents("data.json"); 
$json = json_decode($file); 
foreach($json as $k => $v) { 
	if($k==$id) { 
		echo json_encode($v); 
		break; 
	} 
}


?> 