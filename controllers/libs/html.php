<?

function HTMLhead($page) {
	if($page=='admin') $_SESSION['DESlastadmin'] = ''; 
	?>
<!DOCTYPE html>
<html><head>
 <title></title>
 <link rel="stylesheet" type="text/css" href="static/stys/global.css">
 <script src="static/scrs/jquery.min.js"></script>
 <script src="static/scrs/jquery.mixitup.min.js"></script>
 <script src="static/scrs/main.js"></script>
 <? if($page=="home") { ?>
 <link rel="stylesheet" type="text/css" href="static/stys/home.css">
 <script src="static/scrs/home.js"></script> <? } ?>
</head><body>
	<?
}

function HTMLnav($page) {
	$des = $_SESSION['DESpath'];

	?>
<nav> 
	<?

	if(isset($_SESSION['DESlogged']) && $_SESSION['DESlogged']==1) {

		$type = $_SESSION['DESutype']; 

		if($type == 1) {
			// admin
			?>
 <a href="<? echo $des; ?>">Home</a>
 <a href="<? echo $des; ?>contentmaker">Content Maker</a>
 <a href="<? echo $des; ?>tracker">Tracker</a>
 <a href="<? echo $des; ?>admin">Admin</a> 
			<?
		} elseif($type == 2) {
			// educator 
			?>
 <a href="<? echo $des; ?>">Home</a>
 <a href="<? echo $des; ?>contentmaker">Content Maker</a>
 <a href="<? echo $des; ?>report">Report</a>
			<?
		} else {
			// student 
			?>
 <a href="<? echo $des; ?>">Home</a>
 <a href="<? echo $des; ?>play">Play</a>
			<?
		}

		?><a href="#" id="logout">Log out</a><?
	} else {
		?>
 <a href="<? echo $des; ?>">Home</a>
 <a href="<? echo $des; ?>login">Log in</a>
 <a href="<? echo $des; ?>signup">Sign up</a>
		<?
	}

	?>
	</nav> 
	<?
}

function HTMLfoot($page) {
	?>
</body></html>
	<?
}

?>