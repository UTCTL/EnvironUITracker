<?
if(!isset($_SESSION['DESlogged']) || $_SESSION['DESlogged']!=1) 
  header("Location: login"); 

$page = 'tracker'; 
HTMLhead($page); 
HTMLnav($page); 
?>

<section> 
 Tracker
</section> 

<?
HTMLfoot($page); 
?>