<?
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
		<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam blandit tempus auctor. 
Sed tempor in arcu congue vehicula. Vestibulum scelerisque, mauris in volutpat vulputate, 
erat dolor feugiat nisi, sed scelerisque nisl arcu ut nibh. Duis in arcu facilisis, tempus arcu aliquam, 
facilisis ante. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. 
Praesent vitae tortor interdum risus egestas viverra. Class aptent taciti sociosqu ad litora torquent 
per conubia nostra, per inceptos himenaeos. Donec vehicula felis ut diam 
semper volutpat. Donec quis est semper, viverra orci sit amet, sollicitudin metus.</p> 
		<br><br>
		<a href="<? echo $_SESSION['DESpath']; ?>about" class="button">Learn More</a>
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