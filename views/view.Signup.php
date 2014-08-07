<?
if(isset($_SESSION['DESlogged']) || $_SESSION['DESlogged']==1) 
  header("Location: home"); 

$page = 'signup'; 
HTMLhead($page); 
HTMLnav($page); 
?>

<section class="signup">
 <div class="signupbox">
  <form method="post">
  <input type="text" name="uname" id="uname" placeholder="Username"><br>
  <input type="text" name="email" id="email" placeholder="School/University e-mail"><br>
  <input type="password" name="pword" id="pword" placeholder="Password"><br>
  <input type="password" name="cword" id="cword" placeholder="Confirm Password"><br>
  <input type="text" name="uname" id="uname" placeholder="Class Code"><br><br>
  <input type="submit" class="submit" id="signup" value="Sign up">
  </form>
 </div> 
</section>

<?
HTMLfoot($page); 
?>