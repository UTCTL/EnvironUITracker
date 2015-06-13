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
		<p>Environ is an game that puts you in the role of a decision maker. The game is set in near-future Earth, and you must take a series of actions to help improve the Earth's environment without destroying the economy. Resources are limited, so you must consider different courses of actions to make the best out of your decisions.</p> 
		<br>
		<a href="<? echo $_SESSION['DESpath']; ?>about" class="button">Learn More</a>
		<a href="<? echo $_SESSION['DESpath']; ?>play" class="button">Play</a>
	</div> 
</div> 
<? } else { ?>

<!-- <section>  -->
<? /* admin */ ?>
<? 

require_once('models/model.User.php'); 
$u = new User($dblink); 
$u->instantiateById($_SESSION['DESuid']); 

if($u->getTypeByType()==1) { ?>

<section class="admin"> 
 <content> 

	<div class="box" id="users">
		<h2>Manage Users</h2>

		<table width="100%" cellpadding="0" cellspacing="0" border="0">
			<tr>
				<th width="20%">Username</th>
				<th width="25%">E-mail</th>
				<th width="25%">School</th>
				<th width="80px">User Type</th>
				<th width="30px"></th>
				<th width="30px"></th>
			</tr>
			<? echo $u->getAllUsersToEdit(); ?>
			<tr id="create"> 
				<th colspan="6"><a href="#" class="button curtainOpen" id="create">Create User</a></th> 
			</tr>
		</table> 
	</div> 

 </content> 
</section> 

<div class="curtain">
	<a href="#" class="curtainClose"></a> 
	<div class="box"> 
		modify user content
	</div> 
</div> 
<? /* educator */ ?>
<? } elseif($_SESSION['DESutype']==2) { ?>

<section>
 <content>
	educator home (CP) 
 </content> 
</section>

<? /* student */ ?>
<? } else { ?>

404 ???

<? } ?>
<? } ?>
<!-- </section>  -->



<?
HTMLfoot($page); 
?>