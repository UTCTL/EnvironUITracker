<?

require_once('../_incs/library.php'); 

class User {
	public function __construct($dblink) {
		$this->dblink = $dblink; 
	}

	public function login($uname,$pword) { 
		$u = clean($uname); 
		$p = hash("md5",clean($pword)); 
		error_log($p); 
		$result = mysqli_query($this->dblink, "SELECT * FROM users WHERE uname='$u' AND pword='$p'"); 
		while($row = mysqli_fetch_array($result)) {
			$this->id = $row['id']; 
			$this->uname = $row['uname']; 

			$_SESSION['ENVlogged'] = true; 
			$_SESSION['ENVuser'] = $this->id; 

			return true; 
		} 

		$_SESSION['ENVlogged'] = false; 
		return false; 
	} 

	public function get_user($id) {
		if($_SESSION['ENVlogged']) {
			$id = clean($id); 
			$result = mysqli_query($this->dblink, "SELECT * FROM users WHERE id='$id"); 
			while($row = mysqli_fetch_array($result)) {
				$this->id = $row['id']; 
				$this->uname = $row['uname']; 
			}
		}
	}

	public function logout() { 
		$_SESSION['ENVlogged'] = false; 
		//session_destroy(); 
	} 
} 

?> 