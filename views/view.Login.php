<?
if(isset($_SESSION['DESlogged']) || $_SESSION['DESlogged']==1) 
  header("Location: home"); 

$page = 'login'; 
HTMLhead($page); 
HTMLnav($page); 
?>

<section class="login">
 <div class="loginbox">
  <div class="half">
	 <form method="post">
	  <input type="text" name="uname" id="uname" placeholder="Username"><br>
	  <input type="password" name="pword" id="pword" placeholder="Password"><br>
	  <input type="submit" class="submit" id="login" value="Log in">
	 </form>
  </div> 
  <div class="half">
	 <form method="post">
	  <input type="text" name="nuname" id="nuname" placeholder="Username"><br>
	  <input type="text" name="nemail" id="nemail" placeholder="School E-mail"><br>
	  <input type="password" name="npword" id="npword" placeholder="Password"><br>
	  <input type="password" name="ncword" id="ncword" placeholder="Confirm Password"><br>
	  <input type="text" name="nccode" id="nccode" placeholder="Class Code"><br><br>
	  <input type="submit" class="submit" id="signup" value="Sign up">
	 </form>
  </div> 
 </div> 
</section>

<?
HTMLfoot($page); 
?>