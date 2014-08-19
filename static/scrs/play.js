var CCHECK; 
var RED = '#ffe2de'; 
var GRB = '#e2ffff'; 

$(document).ready(function() {
	// play_for_credit(); 
}); 

function end_screen(uname) {
	console.log("ended game by "+uname); 
}

function play_for_credit() { 
	$('.curtain').fadeIn(); 
	var str = '<h2>Play for credit</h2>'; 
	str += '<table cellpadding="0" cellspacing="0" width="100%">'; 
	str += '<tr><td align="center"><input type="text" name="coursecode" class="value" id="coursecode" placeholder="Class Code"><br>'; 
	str += '<input type="text" name="uname" class="value" id="idnum" placeholder="Student id number"></td></tr></table>'; 
	str += '<input type="button" class="button" value="Play">'; 
	str += '<input type="button" class="ibutton" value="Play"> '; 
	$('.curtain .box').html(str); //.css({'height':'250px'}); 

	$('body').on('keyup','.box input', function() {
		var ccode = $('.value#coursecode').val(); 
		var idnum = $('.value#idnum').val(); 
		validate_ccode(ccode,idnum); 
	}).on('click','.box input.button', function() {
		var ccode = $('.value#coursecode').val(); 
		var idnum = $('.value#idnum').val(); 
		
		if(validate_ccode(ccode,idnum)) { 
			u.getUnity().SendMessage("globe","PlayForCredit",ccode,idnum); 
		} 
	});
} 

function validate_ccode(ccode,idnum) {
	$('.curtain input.ibutton').css({'visibility':'visible'}); 
	var icheck; 

	if(/([^\s])/.test(ccode)) {
		$.ajax({
			type: 'POST', 
			url: 'controllers/operator.php', 
			data: {
				action: 'confirm_coursecodecode', 
				ccode: ccode, 
				uid: 0
			}, 
			success: function(data) { 
				if(data==1) { 
					$('.curtain input.value#coursecode').css({ 'background-color':GRB }); 
					CCHECK = true; 
				} else { 
					$('.curtain input.value#coursecode').css({ 'background-color':RED }); 
					CCHECK = false; 
				} 
			} 
		}); 
	} else {
		$('.curtain input.value#coursecode').css({ 'background-color':RED }); 
		CCHECK = false; 
	} 

	if(/^[A-Za-z0-9,]/.test(idnum)) {
		$('.curtain input.value#idnum').css({ 'background-color':GRB }); 
		icheck = true; 
	} else {
		$('.curtain input.value#idnum').css({ 'background-color':RED }); 
		icheck = false; 
	}

	if(CCHECK && icheck) {
		$('.curtain input.button').show(); 
		$('.curtain input.ibutton').hide(); 
		return true; 
	} else {
		$('.curtain input.button').hide(); 
		$('.curtain input.ibutton').show(); 
		return false; 
	}
}