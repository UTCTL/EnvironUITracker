<?

if(isset($_SESSION['DESlogged'])) {
	if($_SESSION['DESutype']==2) header('Location: '.$_SESSION["DESpath"].'report'); 
	elseif($_SESSION['DESutype']>=3) header('Location: '.$_SESSION["DESpath"].'play'); 
}

$page = 'home';  
HTMLhead($page); 
HTMLnav($page); 
?>

<? if(!isset($_SESSION['DESlogged'])) { ?>
<section class="player"> 
	<video autoplay loop preload="auto" poster="<? echo $_SESSION['DESpath']; ?>static/imgs/bg.png" class="bgvideo">
		<source src="<? echo $_SESSION['DESpath']; ?>static/vids/intro.mp4" type='video/mp4; codecs="avc1.42E01E, mp4a.40.2"'>
		<!-- <source src="http://careers.tableausoftware.com/desktop/video/intro.mp4" type='video/mp4; codecs="avc1.42E01E, mp4a.40.2"'>
		<source src="http://careers.tableausoftware.com/desktop/video/intro.webm" type='video/webm; codecs="vp8, vorbis"'>
		<source src="http://careers.tableausoftware.com/desktop/video/intro.ogv" type='video/ogg; codecs="theora, vorbis"'> -->
		<img src="<? echo $_SESSION['DESpath']; ?>static/imgs/bg.png" alt="">
	</video>
</section> 
<div class="lightbox">
	<div class="home_description"> 
		<div class="logo"></div> 
		<p>Environ is an educational game that puts students in the role of a decision maker. The game is set in near-future Earth, and the player must take a series of actions to help improve the Earths environment without destroying economic output. Resources are limited, so the player must deeply analyze different courses of actions to make difficult and complex decisions.</p> 
		<br>
		<a href="<? echo $_SESSION['DESpath']; ?>about" class="button">Learn More</a>
		<a href="<? echo $_SESSION['DESpath']; ?>login" class="button">Play</a>
	</div> 
</div> 
<? } else { ?>

<section> 
<? /* admin */ ?>
<? if($_SESSION['DESutype']==1) { ?>

Admin home (CP) 

<? /* educator */ ?>
<? } elseif($_SESSION['DESutype']==2) { ?>

Educator home (CP) 

<? /* student */ ?>
<? } else { ?>

Student Home (Play?) 

<? } ?>
<? } ?>
</section> 



<?
HTMLfoot($page); 
?>