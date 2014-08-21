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
	public $help_time_open; 
	public $help_time_close; 
	public $region_data; 
	public $classcode; 

	public function __construct($dblink) {
		$this->dblink = $dblink; 
	} 

	public function instantiate($sessionid) {
		error_log("instantiating"); 
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
		error_log("loading"); 
		$into = false; 
		try {
			if($b=='ses') {
				$s = $this->session_id; 
				$result = mysqli_query($this->dblink,"SELECT * FROM sessions WHERE sessionid='$s'"); 
			} else {
				$id = $this->id; 
				$result = mysqli_query($this->dblink,"SELECT * FROM sessions WHERE id='$id'"); 
			}

			while($row = mysqli_fetch_array($result)) {
				$this->id 			= $row['id']; 
				$this->session_id 	= $row['sessionid']; 
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
				$this->classcode 	= new Classcode($this->dblink); 
				$this->classcode->instantiateByCode($row['coursecodes_id']); 
				$into = true; 
			}

			if($into) error_log("fetch at least an entry"); 
			else {
				error_log("fetched no entries"); 
				return false; 
			}
		} catch (mysqli_sql_exception $e) {
			error_log("error caught on loading"); 
			return false; 
		}

		error_log("loaded successfully"); 
		return true; 
	} 

	public function store_temp_data($sid, $type, $data) { 
		try {
			mysqli_query($this->dblink,"INSERT INTO sessions_temp (session_id,data_type,data_data) VALUES ('$sid','$type','$data')"); 
		} catch (mysqli_sql_exception $e) {
			return false; 
		}
	}

	public function save_temp_data($sid) {
		$all = array(); 
		$this->instantiate($sid); 

		try {
			$result = mysqli_query($this->dblink, "SELECT * FROM sessions_temp WHERE session_id='$sid'"); 
			while($row = mysqli_fetch_array($result)) { 

				$type = $row['data_type']; 
				$data = json_decode(html_entity_decode($row['data_data']),true); 

				echo '<b>'.$type.'</b><br>'; 
				switch($type) {
					case 'meta':
						// error_log(html_entity_decode($row['data_data'])); 
						// foreach($data as $d) {
						// 	error_log($d); 
						// }
						$this->session_id = $data["session"]; 
						$this->classcode = $data["coursecode"]; 
						$this->name = $data["studentid"]; 
						$this->ipaddress = $data["ipaddr"]; 
						$this->accessdatetime = now(); 
						// echo '<span id="spacing">----</span>session: <span id="value">'.$data["session"].'</span><br>';
						// echo '<span id="spacing">----</span>coursecode: <span id="value">'.$data["coursecode"].'</span><br>'; 
						// echo '<span id="spacing">----</span>studentid: <span id="value">'.$data["studentid"].'</span><br>'; 
						// echo '<span id="spacing">----</span>ipaddr: <span id="value">'.$data["ipaddr"].'</span><br>';  
						break; 
					case 'details':
						$this->spent_funds = $data["spent_funds"]; 
						$this->spent_polcap = $data["spent_polcap"]; 
						$this->last_level = $data["level"]; 
						$this->playtime = $data["playtime"]; 
						$this->status = $data["status"]; 
						$this->completed = $data["completed"]; 
						// echo '<span id="spacing">----</span>spent_funds: <span id="value">'.$data["spent_funds"].'</span><br>'; 
						// echo '<span id="spacing">----</span>spent_polcap: <span id="value">'.$data["spent_polcap"].'</span><br>'; 
						// echo '<span id="spacing">----</span>level: <span id="value">'.$data["level"].'</span><br>'; 
						// echo '<span id="spacing">----</span>playtime: <span id="value">'.$data["playtime"].'</span><br>'; 
						// echo '<span id="spacing">----</span>status: <span id="value">'.$data["status"].'</span><br>'; 
						// echo '<span id="spacing">----</span>completed: <span id="value">'.$data["completed"].'</span><br>'; 
						break;
					case 'input':
						$this->drag = $data["camera_movement"]["drag"]; 
						$this->arrows = $data["camera_movement"]["arrows"]; 
						$this->wasdkeys = $data["camera_movement"]["keys"]; 
						$this->minimap = $data["navigation"]["minimap"]; 
						$this->numkeys = $data["navigation"]["keys"]; 
						$this->panel_time_open = $data["panel_time"]["open"]; 
						$this->panel_time_close = $data["panel_time"]["closed"]; 
						$this->panel_clicks_open = $data["panel_time"]["click_open"]; 
						$this->panel_clicks_close = $data["panel_time"]["click_closed"]; 
						$this->help_time_open = $data["help_time"]["open"]; 
						$this->help_time_close = $data["help_time"]["closed"]; 
						// echo '<span id="spacing">----</span>camera movement<br>'; 
						// echo '<span id="spacing">--------</span>drag: <span id="value">'.$data["camera_movement"]["drag"].'</span><br>'; 
						// echo '<span id="spacing">--------</span>arrows: <span id="value">'.$data["camera_movement"]["arrows"].'</span><br>'; 
						// echo '<span id="spacing">--------</span>wasd: <span id="value">'.$data["camera_movement"]["keys"].'</span><br>'; 

						// echo '<span id="spacing">----</span>navigation<br>'; 
						// echo '<span id="spacing">--------</span>minimap: <span id="value">'.$data["navigation"]["minimap"].'</span><br>'; 
						// echo '<span id="spacing">--------</span>1-6keys: <span id="value">'.$data["navigation"]["keys"].'</span><br>'; 

						// echo '<span id="spacing">----</span>panel time<br>'; 
						// echo '<span id="spacing">--------</span>panel time open: <span id="value">'.$data["panel_time"]["open"].'</span><br>'; 
						// echo '<span id="spacing">--------</span>panel time closed: <span id="value">'.$data["panel_time"]["closed"].'</span><br>'; 
						// echo '<span id="spacing">--------</span>panel click open: <span id="value">'.$data["panel_time"]["click_open"].'</span><br>'; 
						// echo '<span id="spacing">--------</span>panel click closed: <span id="value">'.$data["panel_time"]["click_closed"].'</span><br>'; 

						// echo '<span id="spacing">----</span>help time<br>'; 
						// echo '<span id="spacing">--------</span>help time open: <span id="value">'.$data["help_time"]["open"].'</span><br>'; 
						// echo '<span id="spacing">--------</span>help time closed: <span id="value">'.$data["help_time"]["closed"].'</span><br>'; 
						break; 
					case 'clicks':
						error_log("clicks: ".$this->clicks); 
						if(empty($this->clicks)) $temp = array(); 
						else $temp = json_decode($this->clicks,true); 
						
						foreach($data["clicks"] as $click)
							array_push($temp,$click); 

						$this->clicks = json_encode($temp); 
						
						echo '<span id="spacing">----</span>index: <span id="value">'.$data["index"].'</span><br>';  
						echo '<span id="spacing">----</span>clicks: <span id="value">'.count($data["clicks"]).' (count)</span><br>';  

						foreach($temp as $click)
							echo '<span id="spacing">--------</span><span id="value">'.$click[0].','.$click[1].' </span><br>';  

						break; 
					case 'region':
						// if(empty($this->region)) $this->region = array(); 

						// $this->region[$this->data["initials"]] = $data; 

						echo '<span id="spacing">----</span>initials: <span id="value">'.$data["initials"].'</span><br>';  
						echo '<span id="spacing">----</span>name: <span id="value">'.$data["name"].'</span><br>';  
						echo '<span id="spacing">----</span>order: <span id="value">'.$data["order"].'</span><br>';  
						echo '<span id="spacing">----</span>spent_funds: <span id="value">'.$data["spent_funds"].'</span><br>';  
						echo '<span id="spacing">----</span>spent_polcap: <span id="value">'.$data["spent_polcap"].'</span><br>';  
						break; 
					case 'environ_scores':

						// if(!isset($this->region[$this->data["region"]]["environ_scores"])) 
						// 	$this->region[$this->data["region"]]["environ_scores"] = array(); 

						// array_push($this->region[$this->data["region"]]["environ_scores"], $data["environ_scores"]); 

						echo '<span id="spacing">----</span>region: <span id="value">'.$data["region"].'</span><br>';  
						echo '<span id="spacing">----</span>index: <span id="value">'.$data["index"].'</span><br>';  
						echo '<span id="spacing">----</span>environ_scores: <span id="value">'.count($data["environ_scores"]).' (count)</span><br>';  
						break; 
					case 'economy_scores':

						// if(!isset($this->region[$this->data["region"]]["economy_scores"])) 
						// 	$this->region[$this->data["region"]]["economy_scores"] = array(); 

						// array_push($this->region[$this->data["region"]]["economy_scores"], $data["economy_scores"]); 

						echo '<span id="spacing">----</span>region: <span id="value">'.$data["region"].'</span><br>';  
						echo '<span id="spacing">----</span>index: <span id="value">'.$data["index"].'</span><br>';  
						echo '<span id="spacing">----</span>economy_scores: <span id="value">'.count($data["economy_scores"]).' (count)</span><br>';  
						break; 
					case 'bases':

						// if(!isset($this->region[$this->data["region"]]["bases"])) 
						// 	$this->region[$this->data["region"]]["bases"] = array(); 

						// $temp = array(); 
						// $temp["name"] = $data["name"]; 
						// $temp["active"] = $data["active"]; 
						// $temp["upgrades"] = $data["upgrades"]; 
						// array_push($this->region[$this->data["region"]]["bases"], $temp); 

						echo '<span id="spacing">----</span>region: <span id="value">'.$data["region"].'</span><br>';  
						echo '<span id="spacing">----</span>name: <span id="value">'.$data["name"].'</span><br>';  
						echo '<span id="spacing">----</span>active: <span id="value">'.(($data["active"]) ? 'true' : 'false').'</span><br>';  
						echo '<span id="spacing">----</span>upgrades: <span id="value">'.count($data["upgrades"]).' (count)</span><br>';  
						
						foreach($data["upgrades"] as $u) {
							echo '<span id="spacing">--------</span>name: <span id="value">'.$u["name"].'</span><br>'; 
							echo '<span id="spacing">--------</span>active: <span id="value">'.(($u["active"]) ? 'true' : 'false').'</span><br>'; 
						}

						break; 
					case 'blank':
						// echo '<span id="spacing">----</span>: <span id="value">'.$data[""].'</span><br>';  
						// break; 
					default: 
						// echo $row['data_data'].'<br>'; 
						break; 
				} 

				if($this->save()) {
					echo '<span id="saving">====&nbsp;&nbsp;saving</span>'; 
					echo '<br>'; 
					echo '<br>'; 
				} else {
					echo '<span id="error">====&nbsp;&nbsp;failed saving</span>'; 
					echo '<br>'; 
					echo '<br>'; 
				}

			}
		} catch (mysqli_sql_exception $e) {
			error_log("error saving temp data"); 
			return false; 
		}

		// $this->remove_temp_data(); 

		return false; 
	}

	private function remove_temp_data() {
		try {
			$sid = $this->session_id; 
			mysqli_query($this->dblink, "DELETE FROM sessions_temp WHERE session_id='$sid'"); 
		} catch(mysqli_sql_exception $e) {
			return false; 
		}

		return true; 
	}

	public function save() {
		$id = $this->id; 
		$sid = $this->session_id; 
		$cc = (($this->classcode!='') ? $this->classcode->getClassCode() : ''); 
		$ip = $this->ipaddress; 
		$name = $this->name; 
		$dt = $this->accessdatetime; 
		$funds = $this->spent_funds; 
		$polcap = $this->spent_polcap; 
		$level = $this->last_level; 
		$play = $this->playtime; 
		$status = $this->status; 
		$comp = $this->completed; 
		$clicks = $this->clicks; 
		$drag = $this->drag; 
		$arrows = $this->arrows; 
		$wasd = $this->wasdkeys; 
		$mini = $this->minimap; 
		$numk = $this->numkeys; 
		$pto = $this->panel_time_open; 
		$ptc = $this->panel_time_close; 
		$pco = $this->panel_clicks_open; 
		$pcc = $this->panel_clicks_close; 
		$hto = $this->help_time_open; 
		$htc = $this->help_time_close; 
		// $clicks = $this->clicks; 

		if($id==0) {
			try {
				error_log("inserting"); 
				$query = "INSERT INTO sessions 
					(sessionid,accessdatetime,name,ipaddress,citystate,spent_funds,spent_polcap,last_level,playtime,status,completed,clicks,drag,arrows,wasdkeys,minimap,numkeys,panel_time_open,panel_time_close,panel_clicks_open,panel_clicks_close,help_time_open,help_time_close,region_data,coursecodes_id) VALUES 
					('$sid',	'$dt',		'$name',	'$ip','',		'$funds',	'$polcap',	'$level',	'$play','$status','$comp','',	'$drag','$arrows','$wasd','$mini','$numk','$pto',	'$ptc',				'$pco',				'$pcc',			'$hto',			'$htc',			'',			'$cc')"; 
				// error_log($query); 
				mysqli_query($this->dblink,$query); 
			} catch(mysqli_sql_exception $e) {
				error_log("caught error on insert"); 
				return false; 
			}

			$count = 0; 
			$result = mysqli_query($this->dblink,"SELECT * FROM sessions WHERE sessionid='$sid'"); 
			while($row=mysqli_fetch_array($result)) {
				error_log("loading after save"); 
				$this->load('ses'); 
				$count++; 
			}
			error_log("count of loadable entries after save: ".$count); 
		} else {
			try {
				error_log("updating"); 
				$result = mysqli_query($this->dblink,"UPDATE sessions SET 
					sessionid='$sid', 
					name='$name', 
				 	ipaddress='$ip', 
				 	spent_funds='$funds', 
				 	spent_polcap='$polcap', 
				 	last_level='$level', 
				 	playtime='$play', 
				 	status='$status', 
				 	completed='$comp', 
				 	clicks='$clicks', 
				 	drag='$drag', 
				 	arrows='$arrows', 
				 	wasdkeys='$wasd', 
				 	minimap='$mini', 
				 	numkeys='$numk', 
				 	panel_time_open='$pto', 
				 	panel_time_close='$ptc', 
				 	panel_clicks_open='$pco', 
				 	panel_clicks_close='$pcc', 
				 	help_time_open='$hto', 
				 	help_time_close='$htc', 
				 	region_data='', 
				 	coursecodes_id='$cc'

					WHERE id='$id'
					"); 

				error_log(($result) ? "true" : "false"); 
				return ($result) ? true : false; 
			} catch(mysqli_sql_exception $e) {
				error_log("caught error on update"); 
				return false; 
			}
		}

		error_log("successful save ".$this->id); 
		return true; 
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

