<?
$page = 'home';  
HTMLhead($page); 
HTMLnav($page); 
?>

<div class="curtain"></div> 
<video autoplay loop preload="auto" poster="http://careers.tableausoftware.com/desktop/video/intro.jpg" class="bgvideo">
	<source src="http://careers.tableausoftware.com/desktop/video/intro.mp4" type='video/mp4; codecs="avc1.42E01E, mp4a.40.2"'>
	<source src="http://careers.tableausoftware.com/desktop/video/intro.webm" type='video/webm; codecs="vp8, vorbis"'>
	<source src="http://careers.tableausoftware.com/desktop/video/intro.ogv" type='video/ogg; codecs="theora, vorbis"'>
	<img src="http://careers.tableausoftware.com/desktop/video/intro.jpg" alt="">
</video>


<?
HTMLfoot($page); 
?>