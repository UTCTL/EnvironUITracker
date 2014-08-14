var ereg = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
var RED = '#ffe2de'; 
var GRB = '#e2ffff'; 

$(document).ready(function() { 
	$('body').on('click','.button',function() { 
		var type = $(this).attr('id'); 
		var msg = type; 
		var id = 0; 


		if(	type=='edit'||
			type=='delete'||
			type=='create') {
			if(type=='edit'||type=='delete') { 
				var id = $(this).attr('data').substring(1); 
				msg += ' '+id; 
			} 

			$.ajax({
				type:'POST', 
				url:'controllers/operator.php', 
				data:{
					action:type, 
					userid:id
				}, 
				success: function(data) {
					$('.curtain .box').html(data); 
				}
			}); 
		} else if(type=='createsubmit' || type=='createedit') {
			var uname = $('.curtain .value#uname').val(), 
				email = $('.curtain .value#email').val(), 
				pword = $('.curtain .value#pword').val(), 
				cword = $('.curtain .value#cword').val(), 
				school = $('.curtain .value#school').val(), 
				utype = $('.curtain .value:radio[name=usertype]:checked').val(), 
				id = $('.curtain .value#id').val(); 

			$.ajax({
				type:'POST', 
				url:'controllers/operator.php', 
				data: {
					action:type, 
					uname:uname, 
					email:email, 
					pword:pword, 
					cword:cword, 
					school:school, 
					type:utype, 
					id:id
				}, 
				success: function(data) {
					window.location.reload(); 
				}
			}); 
		}  
	}); 

	$('body').on('keyup','.curtain input.value',function() {
		var id = $('.curtain input.value#id').val(); 
		var uname = $('.curtain input.value#uname').val(); 
		var email = $('.curtain input.value#email').val(); 
		var school = $('.curtain input.value#school').val(); 
		var pword = $('.curtain input.value#pword').val(); 
		var cword = $('.curtain input.value#cword').val(); 

		validate_user(id,uname,email,school,pword,cword); 
	}); 
}); 





function validate_user(id,uname,email,school,pword,cword) {

	if(/([^\s])/.test(uname)) {
		$.ajax({
			type:'POST', 
			url:'controllers/operator.php', 
			data: {
				action:'confirm_user', 
				uname:uname, 
				id:id 
			}, 
			success: function(data) {
				if(data==1) $('.curtain input.value#uname').css({ 'background-color':RED }); 
				else { $('.curtain input.value#uname').css({ 'background-color':GRB }); 
				}
			}
		}); 
	} else $('.curtain input.value#uname').css({ 'background-color':RED }); 


	if(ereg.test(email)) {
		$.ajax({ 
			type:'POST', 
			url:'controllers/operator.php', 
			data: {
				action:'confirm_email', 
				email:email, 
				id:id 
			}, 
			success: function(data) {
				if(data==1) $('.curtain input.value#email').css({ 'background-color':RED }); 
				else $('.curtain input.value#email').css({ 'background-color':GRB }); 
			}
		}); 
	} else $('.curtain input.value#email').css({ 'background-color':RED }); 



	if(/^[A-Za-z,]/.test(school)) $('.curtain input.value#school').css({ 'background-color':GRB }); 
	else $('.curtain input.value#school').css({ 'background-color':RED }); 



	if(id>0) $('.curtain input.value#pword').css({ 'background-color':GRB }); 
	else { 
		if(/^[A-Za-z,]/.test(pword)) $('.curtain input.value#pword').css({ 'background-color':GRB }); 
		else $('.curtain input.value#pword').css({ 'background-color':RED }); 
	}



	if(pword!=cword) $('.curtain input.value#cword').css({ 'background-color':RED }); 
	else 
		if(id==0 && cword=="") $('.curtain input.value#cword').css({ 'background-color':RED }); 
		else $('.curtain input.value#cword').css({ 'background-color':GRB }); 
}