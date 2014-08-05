<?
if(isset($_SESSION['DESlogged']) || $_SESSION['DESlogged']==1) 
  header("Location: home"); 

$page = 'signup'; 
HTMLhead($page); 
HTMLnav($page); 
?>

<section>
 <form method="post">
  <input type="text" name="uname" id="uname" placeholder="Username"><br>
  <input type="password" name="pword" id="pword" placeholder="Password"><br>
  <select name="age" id="age">
  	<option selected="selected" value="<16">Younger than 16</option> 
	<?
	for($v=16; $v<=25; $v++) {
		?>
	<option value="<? echo $v; ?>"><? echo $v; ?> 
		<?	
	}
	?>
	<option value=">25">Older than 25</option> 
  </select> <br>
  <select name="gamer" id="gamer">
  	<option selected="selected" value="true">I'm a gamer</option> 
  	<option value="false">I'm not a gamer</option> 
  </select> <br> 
  <input type="submit" class="submit" id="signup" value="Sign up">
 </form>
</section>

<?
HTMLfoot($page); 
?>