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
 <? if($page=="home" && !isset($_SESSION['DESlogged'])) { ?>
 <link rel="stylesheet" type="text/css" href="static/stys/home.css">
 <script src="static/scrs/home.js"></script> 
 <? } ?>
 <? if($page=="login") { ?>
 <link rel="stylesheet" type="text/css" href="static/stys/login.css">
 <? } ?>
 <? if($page=="signup") { ?>
 <link rel="stylesheet" type="text/css" href="static/stys/signup.css">
 <? } ?>
 <? if($page=="play") { ?>
 <link rel="stylesheet" type="text/css" href="static/stys/play.css">
 <? } ?>
</head><body>
	<?
}

function HTMLnav($page) {
	$des = $_SESSION['DESpath'];

	?>
<nav> 
<content>
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
 <a href="<? echo $des; ?>about">About</a>
 <a href="<? echo $des; ?>login">Log in</a>
 <a href="<? echo $des; ?>signup">Sign up</a>
		<?
	}

	?>
</content> 
</nav> 
	<?
}

function HTMLfoot($page) {
	?>

<section class="footer">
 <content> 
 	<div class="fourth">
	 	<ul>
	 		<li><a href="<? echo $_SESSION['DESpath']; ?>">Home</a></li> 
	 		<li><a href="<? echo $_SESSION['DESpath']; ?>about">About</a></li> 
	 		<li><a href="<? echo $_SESSION['DESpath']; ?>login">Log in</a></li> 
	 		<li><a href="<? echo $_SESSION['DESpath']; ?>signup">Sign up</a></li> 
	 	</ul>
	 </div> 
 	<div class="fourth">
	 	<ul> 
	 		<li><a href="http://dmi.utexas.org">Digital Media Institute</a></li> 
	 		<li><a href="http://www.utexas.edu/cio/policies/web-privacy">UT Web Privacy</a></li> 
	 		<li><a href="http://www.utexas.edu/cio/policies/web-accessibility">UT Web Accessibility</a></li> 
	 	</ul>
	 </div> 
	 <div class="fourth">
 		<a class="logo" id="ctl" href="http://ctl.utexas.edu" target="_blank"></a>
	 </div> 
	 <div class="fourth">
 		<a class="logo" id="ut2" href="http://www.utexas.edu" target="_blank"></a> 
	 </div> 
 </content> 
</section> 

</body></html>
	<?
}

?>