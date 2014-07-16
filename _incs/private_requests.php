<?

//header('Access-Control-Allow-Origin: localhost:8888'); 

require_once('library.php'); 
require_once('../_apps/User/models.php'); 

$action = clean($_POST['action']); 

$db = new DATABASE($dbhost,$dbname,$dbuser,$dbpass); 
$dblink = $db->connect(); 
if($dblink==false) 
	$action = "dberror"; 

switch($action) {
	case 'login': 
		$n = clean($_POST['uname']); 
		$p = clean($_POST['pword']); 
		$u = new User($dblink); 
		if($u->login($n,$p)) echo '{"success":"success"}'; 
		else echo '{"error":606,"message":"invalid login credentials"}'; 
		break; 
	case 'logout': 
		$u = new User($dblink); 
		$u->get_user($_SESSION['ENVuser']); 
		$u->logout(); 
		break;
	case 'dberror': 
		echo '{"error":343,"message":"We cannot connect you to the database. Please try agian later. "}'; 
		break; 
	default: 
		echo '{"error":504,"message":"Information missing"}'; 
		break;

}

?>