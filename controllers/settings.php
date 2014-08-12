<?

/*
 * Descartes PHP Framework
 * 	settings.php
 * 
 * @author: Samuel Acuna
 * @date: 08/2013
 * 
 * Settings config file for site.  
 * 
 */

// @TODO : change for production 
$sitename = 'EnvironCPI'; 	// The name of your company/brand/website
$siteurl = 'http://localhost:8888/'; 	// The domain of your website
$dbhost = 'localhost'; 	// The hosting server of your database
$dbuser = 'root'; 		// The username of your database
$dbpass = 'root'; 		// The password to your database
$dbname = 'EnvironCPI_development'; 	// The database name


// *********************************
// No need to edit beyond this point. 

// Connect to database
header("Access-Control-Allow-Origin: ".$siteurl); 
try {
	$dblink = mysqli_connect($dbhost,$dbuser,$dbpass,$dbname) or die(); 
} catch (mysqli_sql_exception $e) {
	echo "Database error. Please try again later."; 
}

// Start session
session_start(); 
$_SESSION['DESpath'] 	= '/'.$sitename.'/';  

?>