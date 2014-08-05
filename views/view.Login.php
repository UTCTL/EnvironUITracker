<?
if(isset($_SESSION['DESlogged']) || $_SESSION['DESlogged']==1) 
  header("Location: home"); 

$page = 'login'; 
HTMLhead($page); 
HTMLnav($page); 
?>

<section>
 <form method="post">
  <input type="text" name="uname" id="uname">
  <input type="password" name="pword" id="pword">
  <input type="submit" class="submit" id="login" value="Log in">
 </form>
</section>

<?
HTMLfoot($page); 
?>