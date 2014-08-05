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

require_once('model.User.php'); 
 
class Admin extends User {
	
	private $dblink; 
	
	public function __construct($dblink) {
		parent::__construct($dblink);  
		$this->dblink = parent::getLink(); 
	}
	
	public function getMenu() {
		$menu = '<li class="curtainOpen" id="addProject">Add a project</li>'; 
		$result = mysqli_query($this->dblink,"SELECT * FROM projects ORDER BY id DESC"); 
		while($row = mysqli_fetch_array($result)) {
			$menu .= '
  <li id="proj'.$row['id'].'">'.$row['projname'].'</li>'; 
		}
		return $menu;  
	}
}
?> 