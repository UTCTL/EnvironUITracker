var w = 1200, 
	h = 700; 

$(document).ready(function() {
	resize_user_activity_section(); 

	var svg = d3.select(".game").append("svg")
			.attr('width',w)
			.attr('height',h); 

	$.getJSON("data.json", function(data) {
		console.log(data); 
	}); 
}); 

$(window).resize(function() {
	resize_user_activity_section(); 
}); 

function resize_user_activity_section() {
	$('.useractivity').css({
		'width':(window.innerWidth-290)+'px' 
	}); 
} 