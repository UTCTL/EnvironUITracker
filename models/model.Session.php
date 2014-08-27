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
	public $bases_data; 
	public $upgrades_data; 
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
	public function getStudent() { return $this->name; } 
	public function getSessionId() { return $this->session_id; } 

	private function setId($int) { $this->id = clean($int); }

	public function toJson() {

		if($this->id==0 || !isset($this->session_id))
			return '{"error":506,"message":"Data not found."}'; 

		$str = '{"success":"success","message":"Entry found","data":{'; 
			$str .= '"'.$this->session_id.'":{';
				$str .= '"details":{'; 
					$str .= '"completed":'.$this->completed.','; 
					$str .= '"level":'.$this->last_level.','; 
					$str .= '"playtime":"'.$this->playtime.'",'; 
					$str .= '"spent_funds":'.$this->spent_funds.','; 
					$str .= '"spent_polcap":'.$this->spent_polcap.','; 
					$str .= '"status":"'.$this->status.'"'; 
					$str .= '},'; // details 

				$str .= '"meta":{'; 
					$str .= '"session":"'.(($this->name!='') ? $this->name : $this->session_id).'",'; 
					$str .= '"datetime":"'.$this->accessdatetime.'",'; 
					$str .= '"student_id":"'.$this->name.'",'; 
					$str .= '"classcode":"'.$this->classcode->getClassCode().'",'; 
					$str .= '"ipaddr":"'.$this->ipaddress.'"'; 
					$str .= '},'; // meta 

				$str .= '"input":{'; 
					$str .= '"camera_movement":{'; 
						$str .= '"arrows":'.$this->arrows.','; 
						$str .= '"drag":'.$this->drag.','; 
						$str .= '"keys":'.$this->wasdkeys; 
						$str .= '},'; // camera_movement 
					$str .= '"navigation":{'; 
						$str .= '"keys":'.$this->numkeys.','; 
						$str .= '"minimap":'.$this->minimap; 
						$str .= '},'; // navigation 
					$str .= '"panel_time":{'; 
						$str .= '"closed":'.$this->panel_time_close.','; 
						$str .= '"open":'.$this->panel_time_open.','; 
						$str .= '"click_close":'.$this->panel_clicks_close.','; 
						$str .= '"click_open":'.$this->panel_clicks_open; 
						$str .= '},'; // panel_time
					$str .= '"help_time":{'; 
						$str .= '"closed":'.$this->help_time_close.','; 
						$str .= '"open":'.$this->help_time_open; 
						$str .= '},'; // help_time
					$str .= '"screen_usage":{'; 
						$str .= '"clicks":'; 
							$str .= $this->clicks; // clicks
						$str .= '}'; // screen_usage 
					$str .= '},'; // input


				$str .= '"regions":{'; 
					
					$regions = json_decode($this->region_data,true); 
					$bases = json_decode($this->bases_data,true); 
					$upgrades = json_decode($this->upgrades_data,true); 
					$i = 0; 

					foreach($regions as $r) {
						$j = 0; 
						$i++; 

						$str .= '"'.$r["initials"].'":{';
							$str .= '"initials":"'.$r["initials"].'",'; 
							$str .= '"name":"'.$r["name"].'",'; 
							$str .= '"order":'.$r["order"].','; 
							$str .= '"spent_funds":'.$r["spent_funds"].','; 
							$str .= '"spent_polcap":'.$r["spent_polcap"].','; 

							$str .= '"scores": {';

								$scores = ["environ","economy"]; 
								foreach($scores as $x) {
									$j++; 

									$str .= '"'.$x.'":['; 

									$temp = array(); 
									ksort($r[$x."_scores"]); 
									foreach($r[$x."_scores"] as $score) {
										foreach ($score as $e) 
											array_push($temp, $e); 
									}

									$str .= implode(',',$temp); 
									$str .= ']'; 

									if($j<count($scores)) $str .= ','; 

								}

								$str .= '},'; // scores

							$str .= '"bases": [';

								$temp = array(); 
								$inits = $r["initials"]; 

								if($inits!='W') {
									foreach($bases[$inits] as $b) {
										$k = 0; 
										$name = $b["name"]; 

										$block = '{'; 
										$block .= '"active":'.(($b["active"]) ? 'true' : 'false').','; 
										$block .= '"name":"'.$name.'",'; 
										$block .= '"upgrades":['; 

										// $nodes = array(); 
										foreach($upgrades[$inits][$name] as $u) {
											$k++; 

											$block .= '{'; 
											$block .= '"name":"'.$u["name"].'",'; 
											$block .= '"active":'.(($u["active"]) ? 'true' : 'false'); 
											$block .= '}'; 

											if($k<count($upgrades[$inits][$name])) $block .= ','; 
										}

										// $block .= implode(',',$nodes); 
										$block .= ']'; 
										$block .= '}'; 
										array_push($temp, $block); 
									}

									$str .= implode(',',$temp); 
								}	
								$str .= ']'; // bases

							$str .= '}'; // particular region

						if($i<count($regions)) $str .= ','; 
					}


					$str .= '}'; // regions


				$str .= '}'; // session_id

			$str .= '}'; // data 
		$str .= '}'; // json 

		return $str; 
	}

	public function find($uid,$cc) { 
		$out = array(); 

		$result = mysqli_query($this->dblink,"SELECT id FROM sessions WHERE coursecodes_id='$cc'"); 
		while($row = mysqli_fetch_array($result)) {
			$s = new Session($this->dblink); 
			$s->instantiateById($row["id"]); 
			array_push($out,$s); 
		}

		return $out; 
	}

	private function load($b) {
		
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
				$this->help_time_open = $row['help_time_open']; 
				$this->help_time_close = $row['help_time_close']; 
				$this->region_data 	= $row['region_data']; 
				$this->bases_data  	= $row['bases_data']; 
				$this->upgrades_data = $row['upgrades_data']; 
				$this->classcode 	= new Classcode($this->dblink); 
				$this->classcode->instantiateByCode($row['coursecodes_id']); 
			}
		} catch (mysqli_sql_exception $e) {
			
			return false; 
		}

		
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
						$this->session_id = $data["session"]; 
						$this->classcode = $data["coursecode"]; 
						$this->name = $data["studentid"]; 
						$this->ipaddress = $data["ipaddr"]; 
						$this->accessdatetime = now(); 
						break; 
					case 'details':
						$this->spent_funds = $data["spent_funds"]; 
						$this->spent_polcap = $data["spent_polcap"]; 
						$this->last_level = $data["level"]; 
						$this->playtime = $data["playtime"]; 
						$this->status = $data["status"]; 
						$this->completed = $data["completed"]; 
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
						break; 
					case 'clicks':
						
						if(empty($this->clicks)) $temp = array(); 
						else $temp = json_decode($this->clicks,true); 
						
						foreach($data["clicks"] as $click)
							array_push($temp,$click); 

						$this->clicks = json_encode($temp); 

						break; 
					case 'region':
						if(empty($this->region_data)) $temp = array(); 
						else $temp = json_decode($this->region_data,true); 

						$r = $data["initials"]; 
						$temp[$r] = $data; 

						$this->region_data = json_encode($temp); 
						break; 
					case 'environ_scores':
						// assumes previous transactions were successful and 
						// region entry already exists 
						$r = $data["region"]; 
						$temp = json_decode($this->region_data,true); 
						$temp[$r]["environ_scores"] = array(); 
						$temp[$r]["environ_scores"][$data["index"]] = $data["environ_scores"]; 

						$this->region_data = json_encode($temp); 
						break; 
					case 'economy_scores':
						// assumes previous transactions were successful and 
						// region entry already exists 
						$r = $data["region"]; 
						$temp = json_decode($this->region_data,true); 
						$temp[$r]["economy_scores"] = array(); 
						$temp[$r]["economy_scores"][$data["index"]] = $data["economy_scores"]; 

						$this->region_data = json_encode($temp); 
						break; 
					case 'bases':
						// assumes previous transactions were successful and 
						// region entry already exists 
						$r = $data["region"]; 

						// bases 
						// -----
						if(empty($this->bases_data)) $b = array(); 
						else $b = json_decode($this->bases_data,true); 

						$temp = array(); 
						foreach($data["upgrades"] as $x) 
							array_push($temp,$x); 
						
						unset($data["upgrades"]); 

						if(!isset($b[$r])) $b[$r] = array(); 

						unset($data["region"]); 
						array_push($b[$r],$data); 

						$this->bases_data = json_encode($b); 

						// upgrades 
						// --------
						if(empty($this->upgrades_data)) $u = array(); 
						else $u = json_decode($this->upgrades_data,true); 

						if(!isset($u[$r])) $u[$r] = array(); 
						if(!isset($u[$r][$data["name"]])) $u[$r][$data["name"]] = array(); 

						foreach($temp as $x) {
							
							if(!in_array($x,$u[$r][$data["name"]]))
								array_push($u[$r][$data["name"]],$x); 
						}

						$this->upgrades_data = json_encode($u);  

						break; 
					case 'blank':
					default: 
						break; 
				} 

				$this->save(); 
				// if($this->save()) {
				// 	echo '<span id="saving">====&nbsp;&nbsp;saving</span>'; 
				// 	echo '<br>'; 
				// 	echo '<br>'; 
				// } else {
				// 	echo '<span id="error">====&nbsp;&nbsp;failed saving</span>'; 
				// 	echo '<br>'; 
				// 	echo '<br>'; 
				// }

			}
		} catch (mysqli_sql_exception $e) {
			
			return false; 
		}

		$this->remove_temp_data(); 

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
		$reg = $this->region_data; 
		$bas = $this->bases_data; 
		$ups = $this->upgrades_data; 

		if($id==0) {
			try {
				
				$query = "INSERT INTO sessions 
					(sessionid,accessdatetime,name,ipaddress,citystate,spent_funds,spent_polcap,last_level,playtime,status,completed,clicks,drag,arrows,wasdkeys,minimap,numkeys,panel_time_open,panel_time_close,panel_clicks_open,panel_clicks_close,help_time_open,help_time_close,region_data,bases_data,upgrades_data,coursecodes_id) VALUES 
					('$sid',	'$dt',		'$name',	'$ip','',		'$funds',	'$polcap',	'$level',	'$play','$status','$comp','',	'$drag','$arrows','$wasd','$mini','$numk','$pto',	'$ptc',				'$pco',				'$pcc',			'$hto',			'$htc',			'$reg',		'$bas',		'$ups',		'$cc')"; 
				mysqli_query($this->dblink,$query); 
			} catch(mysqli_sql_exception $e) {
				
				return false; 
			}

			$count = 0; 
			$result = mysqli_query($this->dblink,"SELECT * FROM sessions WHERE sessionid='$sid'"); 
			while($row=mysqli_fetch_array($result)) {
				
				$this->load('ses'); 
				$count++; 
			}
			
		} else {
			try {
				
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
				 	region_data='$reg', 
				 	bases_data='$bas', 
				 	upgrades_data='$ups', 
				 	coursecodes_id='$cc'

					WHERE id='$id'
					"); 

				
				return ($result) ? true : false; 
			} catch(mysqli_sql_exception $e) {
				
				return false; 
			}
		}

		
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

