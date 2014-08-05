<?

require_once('_incs/library.php'); 

$views = explode('/',clean($_GET['view'])); 
// array_shift($views); 

$html = new HTML(); 
$html->show_header(); 

// $_SESSION['ENVuser'] = 0; 
// $_SESSION['ENVlogged'] = false; 
// error_log($_SESSION['ENVuser']); 
// if($_SESSION['ENVlogged']==null) error_log("no ENVlogged index"); 
// elseif($_SESSION['ENVlogged']==false) error_log("ENVlogged is false"); 
// else error_log("ENVlogged is true"); 

// error_log("ENVuser: ".isset($_SESSION['ENVuser']).' '.$_SESSION['ENVuser']); 
// error_log("ENVlogged: ".isset($_SESSION['ENVlogged']).' '.$_SESSION['ENVlogged']); 

switch($views[0]) {
	// case 'stats': 
	// 	if($_SESSION['ENVlogged']) {
	// 		require_once('_apps/Tracker/views.php'); 
	// 		$v = new View(); 
	// 		$v->showstats(); 
	// 		break; 
	// 	} 
	case 'play':
		require_once('_apps/Play/views.php'); 
		$v = new View(); 
		$v->play(); 
		break; 
	// case 'testip':
	// 	echo "Your ip address is: ".getip(); 
	// 	break; 
	default: 
		if($_SESSION['ENVlogged']) {
			require_once('_apps/Tracker/views.php'); 
			$v = new View(); 
			$v->showstats(); 
			// $v->viewstats(); 
		} else {
			require_once('_apps/User/views.php'); 
			$v = new View(); 
			$v->login(); 
		}
		break; 
}

$html->show_footer(); 

?>
