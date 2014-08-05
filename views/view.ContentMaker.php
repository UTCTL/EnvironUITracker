<?
if(!isset($_SESSION['DESlogged']) || $_SESSION['DESlogged']!=1) 
  header("Location: login"); 

$page = 'contentmaker'; 
HTMLhead($page); 
HTMLnav($page); 
?>

<section> 
 Content Maker
</section> 

<?
HTMLfoot($page); 
?>