<?

function HTMLhead($page) {
	if($page=='admin') $_SESSION['DESlastadmin'] = ''; 

	$title = 'Environ'; 
	if(isset($_SESSION['DESlogged'])) {
		$title .= ' Control Panel Interface'; }
	if($page!='home')
		$title .= ' : '.ucfirst($page); 

	?>
<!DOCTYPE html>
<html><head>
 <title><? echo $title; ?></title>
 <link rel="stylesheet" type="text/css" href="static/stys/global.css">
 <script src="static/scrs/jquery.min.js"></script>
 <script src="static/scrs/jquery.mixitup.min.js"></script>
 <script src="static/scrs/main.js"></script>
 <? if($page=="home" && !isset($_SESSION['DESlogged'])) { ?>
 <link rel="stylesheet" type="text/css" href="static/stys/home.css">
 <script src="static/scrs/home.js"></script> 
 <? 
} ?>
 <? if($page=="home" && isset($_SESSION['DESlogged'])) { ?>
 <link rel="stylesheet" type="text/css" href="static/stys/admin.css">
 <script src="static/scrs/admin.js"></script> 
 <? 
} ?>
 <? if($page=="login") { ?>
 <link rel="stylesheet" type="text/css" href="static/stys/login.css">
 <? 
} ?>
 <? if($page=="signup") { ?>
 <link rel="stylesheet" type="text/css" href="static/stys/signup.css">
 <? 
} ?>
 <? if($page=="about") { ?>
 <link rel="stylesheet" type="text/css" href="static/stys/about.css">
 <? 
} ?>
 <? if($page=="play") { ?>
 <link rel="stylesheet" type="text/css" href="static/stys/play.css">
 <? 
} ?>
 <? if($page=="tracker") { ?>
 <link rel="stylesheet" type="text/css" href="static/stys/visualization/visualization.css">
 <script src="static/scrs/d3.v3.min.js"></script> 
 <script src="static/scrs/visualization/classes.js"></script> 
 <script src="static/scrs/visualization/constants.js"></script> 
 <script src="static/scrs/visualization/display_data.js"></script> 
 <script src="static/scrs/visualization/driver.js"></script> 
 <link rel="stylesheet" type="text/css" href="static/stys/admin.css">
 <script src="static/scrs/admin.js"></script> 
 <? 
} ?>
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
 <a href="<? echo $des; ?>">Admin</a>
 <a href="<? echo $des; ?>tracker">Tracker</a>
			<?
		} elseif($type == 2) {
			// educator 
			?>
 <a href="<? echo $des; ?>">Class Report</a>
			<?
		} else {
			// student 
			?>
 <a href="<? echo $des; ?>play">Play</a>
			<?
		}

		?><a href="#" id="logout">Log out</a><?
	} else {
		?>
 <a href="<? echo $des; ?>">Home</a>
 <a href="<? echo $des; ?>about">About</a>
 <a href="<? echo $des; ?>play">Play</a>
 <a href="<? echo $des; ?>login" id="navlogin">Log in</a>
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
	 		<li><a href="http://dmi.utexas.org" target="_blank">Digital Media Institute</a></li> 
	 		<li><a href="http://www.utexas.edu/cio/policies/web-privacy" target="_blank">UT Web Privacy</a></li> 
	 		<li><a href="http://www.utexas.edu/cio/policies/web-accessibility" target="_blank">UT Web Accessibility</a></li> 
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