$(document).ready(function() {
	play_for_credit(); 
}); 

function end_screen(uname) {
	console.log("ended game by "+uname); 
}

function play_for_credit() { 
	$('.curtain').fadeIn(); 
	var str = '<h2>Play for credit</h2>'; 
	str += '<table cellpadding="0" cellspacing="0">'; 
	str += '<tr><td align="center"><input type="text" name="coursecode" class="value" id="coursecode" placeholder="Class Code"></td></tr>'; 
	str += '<tr><td align="center"><input type="text" name="uname" class="value" id="uname" placeholder="Student id number"></td></tr>'; 
	str += '<tr><td align="right"><input type="button" class="button" value="Play for Credit!">'; 
	str += '<input type="button" class="ibutton" value="Play for Credit!"></td></tr></table> '; 
	$('.curtain .box').html(str).css({'height':'250px'}); 
} 