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
			if(!$_GET['session'] || !$_GET['type'] || !$_GET['data']) {
				echo '{"error":504,"message":"Information missing"}'; 
				break; 
			}

			$session_id = clean($_GET['session']); 
			$datatype = clean($_GET['type']); 
			$jsondata = clean($_GET['data']); 

			require_once('../models/model.Session.php'); 
			$s = new Session($dblink); 

			if(clean($_GET['type'])=="end") {
				error_log('ending transmission'); 
				$s->save_temp_data($session_id); 
			} else $s->store_temp_data($session_id, $datatype, $jsondata); 

			break; 
		case 'pull': 
			if(!$_GET['id']) {
				echo '{"error":504,"message":"Information missing"}'; 
				break; 
			}

			$sessionid = clean($_GET['id']); 

			require_once('../models/model.Session.php'); 
			$s = new Session($dblink); 
			$s->instantiate($sessionid); 

			echo $s->toJson();  
			break; 
		case 'options': 
			require_once('../models/model.Session.php'); 
			$s = new Classcode($dblink); 
			$list = $s->getCodesForUser($_SESSION['DESuid']); 
			$result = '{"success":"success","message":"Entry found","data":{'; 
			foreach($list as $item) {
				$result .= '"'.$item->getClassCode().'":{"ccid":'.$item->getId().', "classcode":"'.$item->getClassCode().'", "user":'.$item->getUser()->getId().', "name":"'.$item->getName().'"},'; 
			} 
			$result = rtrim($result,',').'}}'; 
			echo $result; 
			break; 
		case 'sessions':
			require_once('../models/model.Session.php'); 
			$c = clean($_GET['ccid']); 
			$s = new Session($dblink); 
			$results = $s->find($_SESSION['DESuid'],$c); 

			if(count($results)==0) echo '{"error":"error","message":"No entries were found"}'; 
			else {
				$str = '{"success":"success","messages":"Entries found","data":['; 

				$i = 0; 
				foreach($results as $x) {
					$i++; 

					$str .= '{"id":'.$x->getId().','; 
					$str .= '"user":'; 
					if($x->getStudent() != '') $str .= '"'.$x->getStudent().'"'; 
					else $str .= '"'.$x->getSessionId().'"'; 
					$str .= '}'; 

					if($i<count($results)) $str .= ','; 
				}

				$str .= ']}'; 
				echo $str; 
			}

			break; 
		default:
			break; 
	} 
}


?> 