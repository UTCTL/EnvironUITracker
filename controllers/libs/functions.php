<?

/*
 * Descartes PHP Framework
 * 	functions.php
 * 
 * @author: Samuel Acuna
 * @date: 08/2013
 * 
 * Library containing standard functions.   
 * 
 */
 
$test = 'scope'; 

function clean($str) { return htmlentities(stripslashes($str)); }
function cleanView($str) { return strtolower(clean($str)); }
function encode($str) { return hash('ripemd160',$str); } 
function now() { return date('Y\/m\/d H\:i\:s'); }
function projectPrefix($str) { return substr(strtoupper($str),0,3); }

function getAllProjects($dblink) {
	$list = array(); 
	$result = mysqli_query($dblink,"SELECT id FROM projects WHERE status='1' ORDER BY id DESC"); 
	while($row=mysqli_fetch_array($result)) {
		array_push($list,$row['id']); 
	}
	return $list; 
}

function getAllTypes($dblink) {
	$list = array(); 
	$result = mysqli_query($dblink,"SELECT * FROM types");
	while($row=mysqli_fetch_array($result)) {
		$list[$row['id']] = $row['tagname']; 
	} 
	return $list; 
}

function projectNameRepeat($dblink,$name) {
	$result = mysqli_query($dblink,"SELECT id FROM projects WHERE projname='$name'"); 
	if(mysqli_num_rows($result)>0) return true; 
	return false; 
}

function encodequotes($str) {
	$str = stripslashes($str); 
	$result = ""; 
	for($i = 0; $i < strlen($str); $i++) {
		if($str[$i]==chr(34)) 
			$result .= '&34;'; 
		elseif($str[$i]==chr(39))
			$result .= '&39;'; 
		else $result .= $str[$i]; 
	}
	
	return $result; 
}

function decodequotes($str) {
	$result = ""; 
	
	for($i = 0; $i < strlen($str); $i++) {
		if($str[$i] == '&') {
			$temp = ""; 
			$k = strlen($str); 
			for($j = $i; $j < $k; $j++) {
				if($str[$j]==';') {
					$temp .= $str[$j];  
					$nums = substr($temp,1,$k-1);
					if($nums[0]=='#') $result .= chr(intval(substr($nums,1))); 
					else {
						if(intval($nums)>0) $result .= chr(intval($nums)); 
						else $result .= html_entity_decode($temp); 
					}
					$i = $j; 
					$k = $j;  
				}
				elseif($str[$j]==' ') {
					$result .= $temp.' '; 
					$i = $j; 
					$k = $j; 
				}
				else $temp .= $str[$j]; 
			}
		} else $result .= $str[$i]; 
	}
	
	return html_entity_decode($result); 
}

function stringContains($haystack,$needle) {
	return (strpos($haystack,$needle)===false) ? false : true; 
}

?>