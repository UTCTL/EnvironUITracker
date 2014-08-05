<?

/*
 * Descartes PHP Framework
 * 	model.Image.php
 * 
 * @author: Samuel Acuna
 * @date: 08/2013
 * 
 * Model - Image object that represents  
 * an image store in the database. 
 *  
 */

class Image {
	
	private $dblink; 
	private $id; 
	private $name; 
	private $project; 
	private $datetime; 
	
	public function __construct($dblink) {
		$this->dblink = $dblink; 
		$this->clear(); 
	}
	
	public function instantiate($id) {
		$id = clean($id); 
		$result = mysqli_query($this->dblink,"SELECT * FROM images WHERE id='$id'"); 
		if(mysqli_num_rows($result)==1) {
			while($row=mysqli_fetch_array($result)) {
				$this->setId($row['id']); 
				$this->setName($row['imgurl']);
				$this->setProject($row['project']); 
				$this->setDate($row['datetime']);  
			}
			return true; 
		}
		return false; 
	}
	
	public function getId() { return $this->id; }
	public function getName() { return $this->name; }
	public function getProject() { return $this->project; }
	public function getDate() { return $this->datetime; }
	
	private function setId($int) { $this->id = clean($int); }
	public function setName($str) { $this->name = clean($str); } 
	public function setProject($int) { $this->project = clean($int); }
	public function setDate($date) { $this->datetime = clean($date); } 
	
	public function save() {
		$id = $this->id; 
		$name = $this->name;
		$proj = $this->project;  
		$date = now(); 
		
		if($id==0) {
			try {
				mysqli_query($this->dblink,"INSERT INTO images (imgurl,datetime,project) VALUES ('$name','$date','$proj')");
			} catch(mysqli_sql_exception $e) { return false; }
			
			$result = mysqli_query($this->dblink,"SELECT * FROM images WHERE imgurl='$name' AND datetime='$date' AND project='$proj'"); 
			while($row=mysqli_fetch_array($result)) {
				$this->instantiate($row['id']); 
			}
		} else {
			try {
				mysqli_query($this->dblink,"UPDATE images SET imgurl='$name', datetime='$date', project='$proj' WHERE id='$id'");
			} catch(mysqli_sql_exception $e) { return false; }
		}
		
		return true; 
	}
	
	public function delete() {
		$id = $this->id; 
		if($id!=0) {
			try {
				mysqli_query($this->dblink,"DELETE FROM images WHERE id='$id'"); 
			} catch(mysqli_sql_exception $e) { return false; }
			
			$imgfile = $_SESSION['DESpath'].'_views/_imgs/_uploads'.$this->name; 
			if(is_file($imgfile)) {
				unlink($imgfile); 
			}
			$this->clear(); 
			return true; 
		}
		return false; 
	}
	
	public function clear() {
		$this->setId(0); 
		$this->setName(''); 
		$this->setDate(now()); 
		$this->setProject(0); 
	} 
	
	public function show() {
		return '<img src="'.$_SESSION['DESpath'].'_views/_imgs/_uploads/'.$this->name.'" id="img'.$this->id.'">'; 
	}
	
	public function __toString() {
		return $this->id.':'.$this->name.', project '.$this->project.' ('.$this->datetime.')'; 
	}
}
 
?>