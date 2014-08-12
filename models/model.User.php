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
	protected function getLink() { return $this->dblink; }
	
	private function setId($int) { $this->id = clean($int); }
	public function setUname($str) { $this->uname = clean($str); } 
	public function setPassword($str) { $this->pword = encode(clean($str)); }  
	public function setType($int) { $this->type = new UserType($this->dblink, $int); }  
	
	public function save() {
		$id = $this->id; 
		$uname = $this->uname; 
		$pword = $this->pword; 
		$type = $this->type->getId(); 
		
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
					$this->setType($row['types_id']);  
				} 
				$_SESSION['DESlogged'] = true; 
				$_SESSION['DESuid'] = $this->id; 
				$_SESSION['DESutype'] = $this->type->getId(); 
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
	
	public function __toString() {
		return $this->getId().':'.$this->getUname().', '.$this->getType().', '.$this->logged; 
	}
}



 
class Admin extends User {
	
	private $dblink; 
	
	public function __construct($dblink) {
		parent::__construct($dblink);  
		$this->dblink = parent::getLink(); 
	}
	
	public function getMenu() {
		return "";  
	}
}



 
class Educator extends User {
	
	private $dblink; 
	
	public function __construct($dblink) {
		parent::__construct($dblink);  
		$this->dblink = parent::getLink(); 
	}
}



class UserType { 

	private $dblink; 
	private $id; 
	private $text; 

	public function __construct($dblink, $int) {
		$this->dblink = $dblink; 
		$this->id = $int; 

		$total_types = mysqli_query($this->dblink,"SELECT count(*) FROM types"); 
		$total_types = mysqli_fetch_array($total_types)[0]; 
		if($this->id > 0 && $this->id <= $total_types) {
			$result = mysqli_query($this->dblink,"SELECT * FROM types WHERE id='$int'"); 
			while($row = mysqli_fetch_array($result)) {
				$this->text = $row['tname']; 
			}
		}
	}

	public function getId() { return $this->id; } 
	public function getName() { return $this->text; } 
}

?>