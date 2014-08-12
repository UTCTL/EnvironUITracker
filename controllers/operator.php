<?

require_once('libs/functions.php'); 
require_once('settings.php'); 

if(isset($_POST) || isset($_REQUEST)) {
	$action = (isset($_REQUEST)) ? clean($_REQUEST['action']) : clean($_POST['action']);
	switch($action) {
		case 'login':
			require_once('../models/model.User.php'); 
			$uname = clean($_POST['uname']); 
			$pword = clean($_POST['pword']); 
			$admin = new Admin($dblink); 
			$admin->instantiate($uname,$pword); 
			if($admin->login()) echo 'success'; 
			else echo 'failure';  
			break; 
		case 'logout':
			require_once('../models/model.User.php'); 
			$admin = new Admin($dblink); 
			$admin->instantiateById($_SESSION['DESuid']); 
			$admin->logout(); 
			break; 
		default:
			break; 
	} 
}

?>