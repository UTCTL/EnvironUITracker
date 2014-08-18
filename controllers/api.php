<?

// @TODO : change for production
header('Access-Control-Allow-Origin: *'); 

require_once('libs/functions.php'); 
require_once('settings.php'); 

if(isset($_GET) || isset($_REQUEST)) {
	$action = (isset($_REQUEST)) ? clean($_REQUEST['action']) : clean($_GET['action']); 
	switch($action) {
		case 'session': 
			session_start(); 
			session_regenerate_id(); 
			echo '{"status":"success","message":"","data":{"session":"'.session_id().'"}}'; 
			//session_destroy(); 
			break;
		case 'push': 
			if(!$_GET['data']) {
				echo '{"error":504,"message":"Information missing"}'; 
				break; 
			}

			$jsondata = clean($_GET['data']); 

			require_once('../models/model.Session.php'); 
			$s = new Session($dblink); 
			$s->instantiateByString($jsondata); 

			if($s->save()) echo '{"status":"success","message":"Entry added"}'; 
			else echo '{"error":506,"message":"Data not found."}'; 
			break; 
		case 'pull': 
			if(!$_GET['id']) {
				echo '{"error":504,"message":"Information missing"}'; 
				break; 
			}

			$sessionid = clean($_GET['id']); 

			require_once('../models/model.Session.php'); 
			$s = new Session($dblink); 
			$s->instantiate($jsondata); 

			echo $s->toJson();  
			break; 
		case 'options': 
			require_once('../models/model.Session.php'); 
			$s = new Classcode($dblink); 
			$list = $s->getCodesForUser($_SESSION['DESuid']); 
			$result = '{"status":"success","message":"Entry found","data":{'; 
			foreach($list as $item) {
				$result .= '"'.$item->getClassCode().'":{"ccid":'.$item->getId().', "classcode":"'.$item->getClassCode().'", "user":'.$item->getUser()->getId().', "name":"'.$item->getName().'"},'; 
			} 
			$result = rtrim($result,',').'}}'; 
			echo $result; 
			break; 
		default:
			break; 
	} 
}


?> 