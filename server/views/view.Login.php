<?
if(array_key_exists('DESlogged', $_SESSION))
	if(isset($_SESSION['DESlogged']) || $_SESSION['DESlogged']==1) 
  		header("Location: home"); 

$page = 'login'; 
HTMLhead($page); 
HTMLnav($page); 
?>

<section class="login"> 
 <div class="loginbox"> 
	<form method="post"> 
	 <input type="text" name="uname" id="uname" placeholder="Username"><br> 
	 <input type="password" name="pword" id="pword" placeholder="Password"><br> 
	 <input type="submit" class="submit" id="login" value="Log in"> 
	</form> 
 </div> 
</section> 

<?
HTMLfoot($page); 
?> 