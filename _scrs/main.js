$(document).ready(function() {
	resize_user_activity_section(); 
}); 

$(window).resize(function() {
	resize_user_activity_section(); 
}); 

function resize_user_activity_section() {
	$('.useractivity').css({
		'width':(window.innerWidth-250)+'px' 
	}); 
} 