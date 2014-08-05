<?

/*
 * Descartes PHP Framework
 * 	model.User.php
 * 
 * @author: Samuel Acuna
 * @date: 08/2013
 * 
 * Model - User object that holds
 * information of logged users. 
 * 
 */

class User {
	
	private $dblink; 
	private $id; 
	private $uname;
	private $pword;  
	private $type; 
	private $logged; 
	
	public function __construct($dblink) {
		$this->dblink = $dblink; 
		$this->clear(); 
	} 
	
	public function instantiate($uname,$pword) {
		$this->setUname($uname); 
		$this->setPassword($pword);  
	}
	
	public function instantiateById($id) {
		$this->setId($id); 
	}
	
	public function getId() { return $this->id; } 
	public function getUname() { return $this->uname; } 
	public function getType() { return $this->type; } 
	
	private function setId($int) { $this->id = clean($int); }
	public function setUname($str) { $this->uname = clean($str); } 
	public function setPassword($str) { $this->pword = encode(clean($str)); }  
	public function setType($str) { $this->type = clean($str); }  
	
	public function save() {
		$id = $this->id; 
		$uname = $this->uname; 
		$pword = $this->pword; 
		$type = $this->type; 
		
		if($id==0) {
			try {
				mysqli_query($this->dblink,"INSERT INTO users (uname,pword,type) VALUES ('$uname','$pword','$type')"); 
			} catch(mysqli_sql_exception $e) {
				return false; 
			}
			$result = mysqli_query($this->dblink,"SELECT * FROM users WHERE uname='$uname' AND pword='$pword' AND type='$type'");
			while($row=mysqli_fetch_array($result)) {
				$this->login($row['id']); 
			} 
		} else {
			try {
				mysqli_query($this->dblink,"UPDATE users SET uname='$uname' AND pword='$pword' AND type='$type'"); 
			} catch(mysqli_sql_exception $e) {
				return false; 
			}
		}
		
		return true; 
	} 
	
	public function login() {
		if(!isset($this->logged) || !$this->logged) {
			if(isset($this->id) && $this->id!=0) {
				$id = $this->id; 
				$result = mysqli_query($this->dblink, "SELECT * FROM users WHERE id='$id'");
			} elseif(isset($this->uname) && isset($this->pword)) {
				$uname = $this->uname; 
				$pword = $this->pword; 
				$result = mysqli_query($this->dblink, "SELECT * FROM users WHERE uname='$uname' AND pword='$pword'");
			} else return false; 
			 
			if(mysqli_num_rows($result) == 1) {
				while($row = mysqli_fetch_array($result)) {
					$this->setId($row['id']); 
					$this->setUname($row['uname']); 
					$this->setType($row['type']);  
				} 
				$_SESSION['DESlogged'] = true; 
				$_SESSION['DESuid'] = $this->id; 
				$_SESSION['DESutype'] = $this->type; 
				$this->logged = true; 
				return true;
			} else return false; 
		}
	}
	
	public function logout() {
		session_unset(); 
		session_destroy();  
		$this->clear(); 
	}
	
	public function clear() {
		$this->setId(0);   
		$this->setUname(''); 
		$this->setPassword(''); 
		$this->setType(0); 
		$this->logged = (isset($_SESSION['DESlogged'])) ? $_SESSION['DESlogged'] : false; 
	}
	
	protected function getLink() {
		return $this->dblink; 
	}
	
	public function __toString() {
		return $this->getId().':'.$this->getUname().', '.$this->getType().', '.$this->logged; 
	}
}

?>