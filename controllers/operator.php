<?

require_once('libs/functions.php'); 
require_once('settings.php'); 
require_once('../models/model.User.php'); 
require_once('../models/model.Session.php'); 

if(isset($_POST) || isset($_REQUEST)) {
	$action = (isset($_REQUEST)) ? clean($_REQUEST['action']) : clean($_POST['action']);
	switch($action) {
		case 'login':
			$uname = clean($_POST['uname']); 
			$pword = clean($_POST['pword']); 
			$admin = new User($dblink); 
			$admin->instantiate($uname,$pword); 
			if($admin->login()) echo 'success'; 
			else echo 'failure';  
			break; 
		case 'logout':
			$admin = new User($dblink); 
			$admin->instantiateById($_SESSION['DESuid']); 
			$admin->logout(); 
			break; 
		case 'edit':
			$id = clean($_POST['userid']); 
			$u = new User($dblink); 
			$u->instantiateById($id); 
			echo $u->getUserToEdit(); 
			break; 
		case 'delete':
			$id = clean($_POST['userid']); 
			$u = new User($dblink); 
			$u->instantiateById($id); 
			echo $u->getUserToDelete(); 
			break; 
		case 'create':
			$u = new User($dblink); 
			echo $u->getUserToEdit(); 
			break; 
		case 'addcode':
			$c = new ClassCode($dblink); 
			echo $c->getCodeToEdit($_SESSION['DESuid']); 
			break; 
		case 'delcode':
			$id = clean($_POST['userid']); 
			$c = new ClassCode($dblink); 
			$c->instantiate($id); 
			echo $c->getCodeToDelete(); 
			break; 


		case 'confirm_user':
			$u = new User($dblink); 
			$u->instantiateById(clean($_POST['id'])); 
			echo $u->find('uname',clean($_POST['uname'])); 
			break; 
		case 'confirm_email':
			$u = new User($dblink); 
			$u->instantiateById(clean($_POST['id'])); 
			echo $u->find('email',clean($_POST['email'])); 
			break; 
		case 'confirm_coursecode':
			$u = new User($dblink); 
			$u->instantiateById(clean($_POST['uid'])); 
			echo $u->find('cname',clean($_POST['cname'])); 
			break; 
		case 'confirm_coursecodecode': 
			$u = new User($dblink); 
			echo $u->find('classcode',clean($_POST['ccode'])); 
			break; 

		case 'createsubmit':
			$u = new User($dblink); 
			if($_POST['pword']==$_POST['cword']) {
				$u->setUname($_POST['uname']); 
				$u->setEmail($_POST['email']); 
				$u->setSchool($_POST['school']); 
				$u->setPassword($_POST['pword']); 
				$u->setType($_POST['type']); 
				$u->save(); 
			} else echo 'error'; 
			break; 
		case 'createedit':
			$u = new User($dblink); 
			$u->instantiateById($_POST['id']); 
			if($_POST['pword']==$_POST['cword']) {
				$u->setUname($_POST['uname']); 
				$u->setEmail($_POST['email']); 
				$u->setSchool($_POST['school']); 
				if($_POST['pword']!='')
					$u->setPassword($_POST['pword']); 
				$u->setType($_POST['type']); 
				$u->save(); 
			} else echo 'error'; 
			break; 
		case 'createdelete':
			$u = new User($dblink); 
			$u->instantiateById($_POST['id']); 
			$u->delete();  
			break; 
		case 'createclasscode':
			$c = new ClassCode($dblink); 
			$cname = clean($_POST['cname']); 
			$uid = clean($_POST['uid']); 
			if(isset($_POST['uid']) && isset($_POST['cname'])) {
				$c->setName($cname); 
				$c->setUser($uid); 
				$c->setClassCode($uid.$cname.now()); 
				if($c->save()) echo '{"status":"success", "message": "Entry saved successfully"}'; 
				else echo '{"status":"error", "message": "Entry was not saved successfully"}'; 
			}
			break; 
		case 'createdeletecourse':
			$c = new ClassCode($dblink); 
			$c->instantiate($_POST['id']); 
			$c->delete(); 
			break; 

		default:
			break; 
	} 
}

?>