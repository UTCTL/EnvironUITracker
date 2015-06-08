// $('video.bgvideo').ready(function() {
// 	// $('nav, .curtain').hide().show(); 
// 	// $('nav a').mouseover(); 

// 	console.log('moding the css '+window.innerHeight+': '+(window.innerHeight-$('nav').height())); 
// 	$('video.bgvideo').css({
// 		'margin-top':($('nav').height()+20)+'px' 
// 	}); 
// }); 



var windowH = $(window).height();
$('.curtain').height(windowH);
$(window).resize(function () {
    var windowH = $(window).height();
    $('.curtain').height(windowH);
});