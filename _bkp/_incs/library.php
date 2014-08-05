<?

session_start(); 

function clean($str) { return htmlentities(stripslashes($str)); } 

function getip() {
    $ipaddress = '';
    if ($_SERVER['HTTP_CLIENT_IP'])
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if($_SERVER['HTTP_X_FORWARDED_FOR'])
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if($_SERVER['HTTP_X_FORWARDED'])
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if($_SERVER['HTTP_FORWARDED_FOR'])
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if($_SERVER['HTTP_FORWARDED'])
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if($_SERVER['REMOTE_ADDR'])
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

class HTML {
	public function show_header() {
		?>
<!DOCTYPE>
<html><head>
 <title>Environ Test Tracker</title> 
 <link rel="stylesheet" type="text/css" href="_stys/global.css"> 
 <script type="text/javascript" src="_scrs/jquery.min.js"></script> 
 <script type="text/javascript" src="_scrs/d3.v3.min.js"></script> 
 <script src="_scrs/_visualization/constants.js"></script> 
 <script src="_scrs/_visualization/classes.js"></script> 
 <script src="_scrs/_visualization/display_data.js"></script> 
 <script src="_scrs/main.js"></script>
</head><body>
		<?
	}

	public function show_footer() {
		?>
</body></html>
		<?
	}
}

$dbhost = "localhost"; 
$dbname = "EnvironTestTracker"; 
$dbuser = "root"; 
$dbpass = "root"; 

class DATABASE {
	public function __construct($host,$name,$user,$pass) {
		$this->host = $host; 
		$this->name = $name; 
		$this->user = $user; 
		$this->pass = $pass; 
	}

	public function connect() { 
		try { 
			$dblink = mysqli_connect($this->host,$this->user,$this->pass,$this->name) or die(); 
		} catch (mysqli_sql_exception $e) {
			return false; 
		}

		return $dblink; 
	}
}

?>