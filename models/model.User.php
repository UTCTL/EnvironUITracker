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
	private $email; 
	private $school; 
	private $type; 
	private $logged; 
	
	public function __construct($dblink) {
		$this->dblink = $dblink; 
		$this->clear(); 

	} 
	
	public function instantiate($uname,$pword) {
		$this->setUname($uname); 
		$this->setPassword($pword);  
		$this->load('unp'); 
	}
	
	public function instantiateById($id) {
		$this->setId($id); 
		$this->load('id'); 
	}
	
	public function getId() { return $this->id; } 
	public function getName() { return $this->uname; } 
	public function getType() { return $this->type->getName(); } 
	public function getTypeByType() { return $this->type->getId(); } 
	protected function getLink() { return $this->dblink; }
	public function getEmail() { return $this->email; } 
	public function getSchool() { return $this->school; } 
	
	private function setId($int) { $this->id = clean($int); }
	public function setUname($str) { $this->uname = clean($str); } 
	public function setPassword($str) { $this->pword = encode(clean($str)); }  
	public function setType($int) { $this->type = new UserType($this->dblink, $int); }  
	public function setEmail($str) { $this->email = clean($str); } 
	public function setSchool($str) { $this->school = clean($str); }
	
	public function load($b) {
		if(array_key_exists('DESlogged', $_SESSION)) {
			if(isset($_SESSION['DESlogged']) || $_SESSION['DESlogged']==1) {
				if($b=='unp') {
					$u = $this->uname; 
					$p = $this->pword; 
					$result = mysqli_query($this->dblink,"SELECT * FROM users WHERE uname='$u' AND pword='$p' AND active_state=true"); 
				} elseif($b=='id') { 
					$id = $this->id; 
					$result = mysqli_query($this->dblink,"SELECT * FROM users WHERE id='$id' AND active_state=true"); 
				} else return false; 

				while($row = mysqli_fetch_array($result)) { 
					$id = 0; 
					if($row['id']!=null) $this->setId($row['id']); 
					$this->setUname($row['uname']); 
					$this->setType($row['types_id']); 
					$this->setEmail($row['email']); 
					$this->setSchool($row['school']); 
					return true; 
				}
			}
		}

		return false; 
	}

	public function save() {
		$id = $this->id; 
		$uname = $this->uname; 
		$pword = $this->pword; 
		$email = $this->email; 
		$school = $this->school; 
		$type = $this->type->getId(); 
		$date = implode('-',explode('/',now())); 
		
		if($id==0) {
			try {
				error_log("saving ".$id.' u:'.$uname.' p:'.$pword.' t:'.$this->type->getName().' now:'.$date); 
				mysqli_query($this->dblink,"INSERT INTO users (uname,pword,email,school,types_id,active_state,u_created) VALUES ('$uname','$pword','$email','$school','$type',true,'$date')"); 
			} catch(mysqli_sql_exception $e) {
				error_log("error while saving new users"); 
				return false; 
			}
			$result = mysqli_query($this->dblink,"SELECT * FROM users WHERE uname='$uname' AND pword='$pword' AND types_id='$type'");
			while($row=mysqli_fetch_array($result)) {
				$this->load('unp'); 
			} 
		} else {
			try {
				mysqli_query($this->dblink,"UPDATE users SET uname='$uname', pword='$pword', email='$email', school='$school', types_id='$type' WHERE id='$id'"); 
			} catch(mysqli_sql_exception $e) {
				return false; 
			}
		}
		
		return true; 
	} 

	public function delete() { 
		$id = $this->id; 
		mysqli_query($this->dblink,"UPDATE users SET active_state=false WHERE id='$id'"); 
	} 
	
	public function login() {
		if(!isset($this->logged) || !$this->logged) {
			if(isset($this->id) && $this->id!=0) {
				$id = $this->id; 
				$result = mysqli_query($this->dblink, "SELECT * FROM users WHERE id='$id' AND active_state=true");
			} elseif(isset($this->uname) && isset($this->pword)) {
				$uname = $this->uname; 
				$pword = $this->pword; 
				$result = mysqli_query($this->dblink, "SELECT * FROM users WHERE uname='$uname' AND pword='$pword' AND active_state=true");
			} else return false; 
			 
			if(mysqli_num_rows($result) == 1) {
				while($row = mysqli_fetch_array($result)) {
					$this->setId($row['id']); 
					$this->setUname($row['uname']); 
					$this->setType($row['types_id']);  
					$this->setEmail($row['email']); 
					$this->setSchool($row['school']); 
				} 
				$_SESSION['DESlogged'] = true; 
				$_SESSION['DESuid'] = $this->id; 
				$_SESSION['DESutype'] = $this->type->getId(); 
				$this->logged = true; 
				return true;
			} else return false; 
		}
	}

	public function getAllUsersToEdit() { 
		$str = ''; 
		if($this->type->getId()==1) { 
			//error_log('this user can get all users'); 
			$result = mysqli_query($this->dblink, "SELECT * FROM users WHERE active_state=true"); 
			while($row = mysqli_fetch_array($result)) { 
				$t = new UserType($this->dblink, $row['types_id']); 
				$str .= '<tr><td>'.$row['uname'].'</td><td><a href="mailto:'.$row['email'].'">'.$row['email'].'</a></td><td>'.$row['school'].'</td><td>'.$t->getName().'</td><td align="right"><div class="button curtainOpen" id="edit" data="u'.$row['id'].'"></div></td><td align="right"><div class="button curtainOpen" id="delete" data="u'.$row['id'].'"></div></td></tr>'; 
			} 
		} //else error_log('error! you cannot get all users'); 

		return $str; 
	} 

	public function find($field,$value) { 
		$field = clean($field); 
		$value = clean($value); 
		$id = $this->id; 



		try {
			if($this->id==0) $result = mysqli_query($this->dblink, "SELECT count(*) FROM users WHERE $field='$value' AND active_state=true"); 
			else $result = mysqli_query($this->dblink, "SELECT count(*) FROM users WHERE id!=$id AND $field='$value' AND active_state=true"); 

			while($row = mysqli_fetch_array($result)) { 
				if($row[0]>0) return true; 
				else return false; 
			} 
		} catch (Exception $e) { 
			return false; 
		} 

		return false; 
	} 

	public function getUserToEdit() { 
		$str = ''; 

		if($this->id==0) $title = 'Create new user'; 
		else $title = 'Edit user'; 

		$str .= '<h2>'.$title.'</h2>'; 
		$str .= '<input type="text" class="value" id="uname" placeholder="Username" value="'.$this->uname.'">'; 
		$str .= '<input type="text" class="value" id="email" placeholder="Email" value="'.$this->email.'">'; 
		$str .= '<input type="text" class="value" id="school" placeholder="School/University" value="'.$this->school.'">'; 
		$str .= '<input type="password" class="value" id="pword" placeholder="Password">'; 
		$str .= '<input type="password" class="value" id="cword" placeholder="Confirm Password"><br>'; 
		$str .= '<input type="hidden" class="value" id="id" value="'.$this->id.'">'; 

		if($this->type==null || $this->type->getId()!=1) $str .= '<input type="radio" name="usertype" class="value" id="typeeadm" value="1">Administrator <input type="radio" name="usertype" class="value" id="typeedu" value="2" checked="checked">Educator'; 
		else $str .= '<input type="radio" name="usertype" class="value" id="typeadm" value="1" checked="checked">Administrator <input type="radio" name="usertype" class="value" id="typeedu" value="2">Educator'; 
		

		if($this->id==0) $str .= '<input type="button" class="button" id="createsubmit" value="Create">'; 
		else $str .= '<input type="button" class="button" id="createedit" value="Edit">'; 
		

		return $str; 
	}

	public function getUserToDelete() {
		$str = '<h2>Delete user</h2>'; 
		$str .= '<p>Are you sure you want to delete this user?</p>'; 
		$str .= '<input type="hidden" class="value" id="id" value="'.$this->id.'">'; 

		$str .= '<input type="button" class="button" id="createdelete" value="Delete">'; 		

		return $str; 
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