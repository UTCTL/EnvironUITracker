<?

/*
 * Descartes PHP Framework
 * 	model.Session.php
 * 
 * @author: Samuel Acuna
 * @date: 08/2014
 * 
 * Model - Session object that holds
 * information of gameplay sessions. 
 * 
 */

class Session { 
	
	private $dblink; 
	private $id; 
	public $session_id; 
	public $accessdatetime; 
	public $name; 
	public $spent_funds; 
	public $spent_polcap; 
	public $last_level; 
	public $playtime; 
	public $status; 
	public $completed; 
	public $clicks; 
	public $drag; 
	public $arrows; 
	public $wasdkeys; 
	public $minimap; 
	public $numkeys; 
	public $panel_time_open; 
	public $panel_time_close; 
	public $panel_clicks_open; 
	public $panel_clicks_close; 
	public $region_data; 
	public $classcode; 

	public function __construct($dblink) {
		$this->dblink = $dblink; 
	} 

	public function instantiate($sessionid) {
		$this->session_id = clean($sessionid); 
		$this->load('ses'); 
	} 
	
	public function instantiateById($id) {
		$this->setId($id); 
		$this->load('id'); 
	}

	public function getId() { return $this->id; } 
	private function setId($int) { $this->id = clean($int); }

	private function load($b) {
		if($b=='ses') $result = mysqli_query($this->dblink,"SELECT * FROM session WHERE session_id='$b'"); 
		else $result = mysqli_query($this->dblink,"SELECT * FROM session WHERE id='$b'"); 

		while($row = mysqli_fetch_array($result)) {
			$this->id 			= $row['id']; 
			$this->session_id 	= $row['session_id']; 
			$this->accessdatetime  = $row['accessdatetime']; 
			$this->name 		= $row['name']; 
			$this->spent_funds 	= $row['spent_funds']; 
			$this->spent_polcap = $row['spent_polcap']; 
			$this->last_level 	= $row['last_level']; 
			$this->playtime 	= $row['playtime']; 
			$this->status 		= $row['status']; 
			$this->completed 	= $row['completed']; 
			$this->clicks 		= $row['clicks']; 
			$this->drag 		= $row['drag']; 
			$this->arrows 		= $row['arrows']; 
			$this->wasdkeys 	= $row['wasdkeys']; 
			$this->minimap		= $row['minimap']; 
			$this->numkeys 		= $row['numkeys']; 
			$this->panel_time_open = $row['panel_time_open']; 
			$this->panel_time_close = $row['panel_time_close']; 
			$this->panel_clicks_open = $row['panel_clicks_open']; 
			$this->panel_clicks_close = $row['panel_clicks_close']; 
			$this->region_data 	= $row['region_data']; 
			$this->classcode 	= new Classcode($dblink,$row['classcodes_id']); 
		}
	} 

	public function process($str) {
		$j = json_decode($str); 
	}

	public function save() {
		return false; 
	}
} 