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
require_once('model.User.php'); 

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
		foreach($j as $a => $b) { $id = $a; }

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

	public function toJson() {
		return '{"error":506,"message":"Data not found."}'; 
	}

	private function load($b) {
		if($b=='ses') {
			$s = $this->session_id; 
			$result = mysqli_query($this->dblink,"SELECT * FROM session WHERE session_id='$session_id'"); 
		} else {
			$id = $this->id; 
			$result = mysqli_query($this->dblink,"SELECT * FROM session WHERE id='$id'"); 
		}

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



class Classcode {

	private $dblink; 
	private $id; 
	private $classcode; 
	private $user; 
	private $cname; 

	public function __construct($dblink) {
		$this->dblink = $dblink; 
	} 

	public function instantiate($id) {
		$id = clean($id); 
		$result = mysqli_query($this->dblink,"SELECT * FROM classcodes WHERE id='$id' LIMIT 1"); 
		while($row = mysqli_fetch_array($result)) {
			$this->id = $row['id']; 
			$this->setClassCode($row['classcode']); 
			$u = new User($this->dblink); 
			$u->instantiateById($row['users_id']); 
			$this->setUser($u); 
			$this->setName($row['cname']); 
			return true; 
		}

		return false; 
	} 

	public function instantiateByCode($code) {
		$c = clean($code); 
		$result = mysqli_query($this->dblink,"SELECT id FROM classcodes WHERE classcode='$c'"); 
		while($row = mysqli_fetch_array($result)) 
			return instantiate($row['id']); 
	}

	public function getId() { return $this->id; } 
	public function getClassCode() { return $this->classcode; } 
	public function getUser() { return $this->user; }
	public function getName() { return $this->cname; } 

	public function setClassCode($code) { $this->classcode = encode_crc($code); } 
	public function setName($name) { $this->cname = clean($name); } 
	public function setUser($uid) { 
		$this->user = new User($this->dblink); 
		$this->user->instantiateById(clean($uid)); 
	}

	public function getSessions() { 
		$id = $this->id; 
		$list = array(); 
		$result = mysqli_query($this->dblink,"SELECT id FROM sessions WHERE classcodes_id='$id' AND active_state=true"); 
		while($row = mysqli_fetch_array($result)) {
			$s = new Session($this->dblink); 
			$s->instantiateById($row['id']); 
			array_push($list,$s); 
		}

		return $list; 
	} 

	public function getCodesForUser($uid) {
		$uid = clean($uid); 
		$list = array(); 
		$result = mysqli_query($this->dblink,"SELECT * FROM classcodes WHERE users_id='$uid' AND active_state=true"); 
		while($row = mysqli_fetch_array($result)) {
			$c = new Classcode($this->dblink); 
			$c->instantiate($row['id']); 
			array_push($list, $c); 
		}

		return $list; 
	}

	public function getCodeToEdit($uid) {
		$str = '<h2>Add Course Code</h2>'; 
		$str .= '<input type="text" class="value" id="cname" placeholder="Class Name"><br>'; 
		$str .= '<input type="hidden" class="value" id="uid" value="'.clean($uid).'">'; 
		$str .= '<input type="button" class="button" id="createclasscode" value="Add">'; 
		$str .= '<input type="button" class="ibutton" id="createclasscode" value="Add">'; 
		return $str; 
	}

	public function getCodeToDelete() {
		$str = '<h2>Delete course</h2>'; 
		$str .= '<p>Are you sure you want to delete this course?</p>'; 
		$str .= '<input type="hidden" class="value" id="id" value="'.$this->id.'">'; 

		$str .= '<input type="button" class="button" id="createdeletecourse" value="Delete">'; 

		return $str; 
	}

	public function save() {
		$id = $this->id; 
		$cc = $this->classcode; 
		$user = $this->user->getId(); 
		$name = $this->cname; 

		// error_log($id.' '.$cc.' '.$user.' '.$name); 

		if($id==0) {
			try { 
				mysqli_query($this->dblink,"INSERT INTO classcodes (classcode,users_id,cname,active_state) VALUES ('$cc','$user','$name',true)"); 
			} catch(mysqli_sql_exception $e) {
				return false; 
			}

			return true; 
		}

		return false; 
	}

	public function delete() { 
		$id = $this->id; 
		mysqli_query($this->dblink,"UPDATE classcodes SET active_state=false WHERE id='$id'"); 
	} 
} 

