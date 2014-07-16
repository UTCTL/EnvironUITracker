<?

class View {
	
	public function login() {
		?>
<input type="text" name="uname" id="uname" placeholder="Username"><br>
<input type="password" name="pword" id="pword" placeholder="Password"><br>
<input type="button" id="login" value="Log in">
		<?
		$_SESSION['ENVlogged'] = false; 
	} 

	public function viewstats() {
		?>
<a href="/EnvironUITracker/stats">View stats!</a>
		<?
	}

}

?>