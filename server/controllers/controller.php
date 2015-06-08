<?

/*
 * Descartes PHP Framework
 * 	controller.php
 * 
 * @author: Samuel Acuna
 * @date: 08/2013
 * 
 * Model builder and view selector.   
 * 
 */

$view = (isset($_GET['view'])) ? cleanView($_GET['view']) : 'home'; 
switch($view) {
	// unlogged users 
	case 'about':
		require_once('views/view.About.php'); 
		break; 
	case 'login':
		require_once('views/view.Login.php'); 
		break; 

	// student users 
	case 'play': 
		require_once('views/view.Play.php'); 
		break; 

	// educator users 
	case 'report': 
	case 'tracker':
		require_once('views/view.Tracker.php'); 
		break; 

	// home 
	case 'home':
	default:
		require_once('views/view.Home.php');  
		break; 
} 

?> 