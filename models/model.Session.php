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
	public $ipaddress; 
	public $citystate; 
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

	public function instantiateByString($str) {
		$j = json_decode($str,true); 
		$id = ''; 
		for($j as $a => $b) { $id = $a; }

		$this->id 			= $row['id']; 
		$this->session_id 	= $id; 
		$this->accessdatetime  = now(); 
		$this->name 		= $j[$id]["meta"]["name"]; 
		$this->classcode 	= new Classcode($dblink,intval($row["meta"]['classcodes_id'])); 
		$this->ipaddress 	= $j[$id]["meta"]["ipaddr"]; 
		$this->citystate 	= $j[$id]["meta"]["citystate"]; 
		$this->spent_funds 	= $j[$id]["details"]["spent_funds"]; 
		$this->spent_polcap = $j[$id]["details"]["spent_polcap"]; 
		$this->last_level 	= $j[$id]["details"]["level"]; 
		$this->playtime 	= $j[$id]["details"]["playtime"]; 
		$this->status 		= $j[$id]["details"]["status"]; 
		$this->completed 	= $j[$id]["details"]["completed"]; 

		$c = ''; 
		foreach($j[$id]["input"]["screen_usage"] as $a) {
			foreach($a as $b) {
				$c .= '['.$b[0].','.$b[1].'],'; 
			}
		}
		$this->clicks = rtrim($c,','); 

		$this->drag = $j[$id]["input"]["camera_movement"]["drag"]; 
		$this->arrows = $j[$id]["input"]["camera_movement"]["arrows"]; 
		$this->wasdkeys = $j[$id]["input"]["camera_movement"]["keys"]; 
		$this->minimap = $j[$id]["input"] ["navigation"]["minimap"]; 
		$this->numkeys = $j[$id]["input"] ["navigation"]["keys"]; 
		$this->panel_time_open = $j[$id]["input"]["panel_time"]["open"]; 
		$this->panel_time_close = $j[$id]["input"]["panel_time"]["closed"]; 
		$this->panel_clicks_open = $j[$id]["input"]["panel_time"]["click_open"]; 
		$this->panel_clicks_close = $j[$id]["input"]["panel_time"]["click_close"]; 

		$c = '{'; 
		foreach($j[$id]["regions"] as $r => $d) {
			$c .= '"'.$r.'": {'; 
			foreach($d as $k => $v) {

				if($k=='name'||$k=='initials')
					$c .= '"'.$k.'" : "'.$v.'", '; 
				elseif($k=='scores') {

					$s = '"'.$k.'":{'; 
					foreach($v as $scoretype => $scorevalue) {
						$s .= '"'.$scoretype.'" : ['; 
						foreach($scorevalue as $svi => $svc) {
							$s .= $svc.','; 
						} 
						$s = rtrim($s,',').'],'; 
					}
					$s = rtrim($s,',').'},'; 
					$c .= $s; 

				} elseif($k=='bases') {

					$b = '"'.$k.'" : ['; 
					foreach($v as $baseproperty => $baseinfo) {
						$b .= '{'; 
						foreach($baseinfo as $baseinfoproperty => $baseinfovalue) {
							if($baseinfoproperty=="upgrades") {
								$b .= '"'.$baseinfoproperty.'":['; 
								foreach($baseinfovalue as $upgradeindex => $upgradevalue) {
									$b .= '{'; 
									foreach($upgradevalue as $ui => $uv) {
										if($ui=="active") {
											$b .= '"'.$ui.'" : '.((!$uv) ? 'false' : 'true').','; 
										} else {
											$b .= '"'.$ui.'" : "'.$uv.'",'; 
										}
									}
									$b = rtrim($b,',').'},'; 
								}
								$b = rtrim($b,',').'],'; 
							} elseif($baseinfoproperty=='active') {
								$b .= '"'.$baseinfoproperty.'" : '.((!$baseinfovalue) ? '0' : '1').','; 
							} else {
								$b .= '"'.$baseinfoproperty.'" : "'.$baseinfovalue.'",'; 
							}
						}
						$b = rtrim($b,',').'},'; 
					}
					$b = rtrim($b,',').']'; 
					$c .= $b; 

				} else 
					$c .= '"'.$k.'" : '.$v.', '; 

			}
			$c .= '},'; 
		}
		$this->region_data = rtrim($c,',').'}'; 

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
			$this->ipaddress 	= $row['ipaddress']; 
			$this->citystate 	= $row['citystate']; 
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

	public function save() {
		return false; 
	}
} 