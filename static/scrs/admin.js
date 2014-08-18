var ereg = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
var RED = '#ffe2de'; 
var GRB = '#e2ffff'; 
var UCHECK = false; 
var ECHECK = false; 

$(document).ready(function() { 

	$('body').on('click','.button',function() { 
		var type = $(this).attr('id'); 
		var msg = type; 
		var id = 0; 


		if(	type=='edit'||
			type=='delete'||
			type=='create'||
			type=='addcode'||
			type=='delcode') {

			console.log(type); 

			if(type=='edit'||type=='delete'||type=='delcode') { 
				var id = $(this).attr('data').substring(1); 
				msg += ' '+id; 
			} 

			if(type=='edit'||type=='create'||type=='addcode') {
				$('.curtain .button').css({'visibility':'hidden'}); 
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

			if(!validate_user(id,uname,email,school,pword,cword)) 
				return; 

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
		} else if(type=='createdelete') { 
			var id = $('.curtain .value#id').val(); 

			$.ajax({ 
				type:'POST', 
				url:'controllers/operator.php', 
				data: { 
					action:type, 
					id:id 
				}, 
				success: function (data) { 
					window.location.reload(); 
				} 
			}); 
		} else if(type=='createclasscode') {
			var uid = $('.curtain input.value#uid').val(); 
			var cname = $('.curtain input.value#cname').val(); 

			if(!validate_cname(uid,cname)) 
				return; 

			$.ajax({ 
				type:'POST', 
				url:'controllers/operator.php', 
				data: {
					action:type, 
					uid:uid, 
					cname:cname 
				}, 
				success: function(data) { 
					var j = $.parseJSON(data); 
					if(j["status"]=="success") {
						$('.subnav .menuoptions').html(''); 
						load_menu(); 
						$('.curtain').fadeOut(); 
					} 
				} 
			}); 
		} else if(type=='createdeletecourse') {
			var id = $('.curtain .value#id').val(); 

			$.ajax({ 
				type:'POST', 
				url:'controllers/operator.php', 
				data: { 
					action:type, 
					id:id 
				}, 
				success: function (data) { 
					window.location.reload(); 
				} 
			}); 
		}
	}); 

	$('body').on('keyup','.curtain#ccreate input.value',function() {
		var id = $('.curtain input.value#id').val(); 
		var uname = $('.curtain input.value#uname').val(); 
		var email = $('.curtain input.value#email').val(); 
		var school = $('.curtain input.value#school').val();
		var pword = $('.curtain input.value#pword').val(); 
		var cword = $('.curtain input.value#cword').val(); 

		validate_user(id,uname,email,school,pword,cword); 

	}).on('keyup','.curtain#cedit input.value',function() {
		var id = $('.curtain input.value#id').val(); 
		var uname = $('.curtain input.value#uname').val(); 
		var email = $('.curtain input.value#email').val(); 
		var school = $('.curtain input.value#school').val(); 
		var pword = $('.curtain input.value#pword').val(); 
		var cword = $('.curtain input.value#cword').val(); 

		validate_user(id,uname,email,school,pword,cword); 

	}).on('keyup','.curtain#caddcode input.value',function() {
		var uid = $('.curtain input.value#uid').val(); 
		var cname = $('.curtain input.value#cname').val(); 

		validate_cname(uid,cname); 
	}); 
}); 





function validate_user(id,uname,email,school,pword,cword) {
	$('.curtain input.ibutton').css({'visibility':'visible'}); 
	var s,p,c; 

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
				if(data==1) {
					$('.curtain input.value#uname').css({ 'background-color':RED }); 
					UCHECK = false; 
				} else {
					$('.curtain input.value#uname').css({ 'background-color':GRB }); 
					UCHECK = true; 
				}
			}
		}); 
	} else {
		$('.curtain input.value#uname').css({ 'background-color':RED }); 
		UCHECK = false; 
	}


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
				if(data==1) {
					$('.curtain input.value#email').css({ 'background-color':RED }); 
					ECHECK = false; 
				} else {
					$('.curtain input.value#email').css({ 'background-color':GRB }); 
					ECHECK = true; 
				}
			}
		}); 
	} else {
		$('.curtain input.value#email').css({ 'background-color':RED }); 
		ECHECK = false; 
	} 



	if(/^[A-Za-z,]/.test(school)) {
		$('.curtain input.value#school').css({ 'background-color':GRB }); 
		s = true; 
	} else {
		$('.curtain input.value#school').css({ 'background-color':RED }); 
		s = false; 
	}



	if(id>0) {
		$('.curtain input.value#pword').css({ 'background-color':GRB }); 
		p = true; 
	} else { 
		if(/^[A-Za-z,]/.test(pword)) {
			$('.curtain input.value#pword').css({ 'background-color':GRB }); 
			p = true;  
		} else {
			$('.curtain input.value#pword').css({ 'background-color':RED }); 
			p = false; 
		}
	}



	if(pword!=cword) {
		$('.curtain input.value#cword').css({ 'background-color':RED }); 
		c = false; 
	} else {
		if(id==0 && cword=="") {
			$('.curtain input.value#cword').css({ 'background-color':RED }); 
			c = false; 
		} else {
			$('.curtain input.value#cword').css({ 'background-color':GRB }); 
			c = true; 
		}
	}

	if(UCHECK&&ECHECK&&s&&p&&c) {
		$('.curtain input.button').show(); 
		$('.curtain input.ibutton').hide(); 
		return true; 
	} else { 
		$('.curtain input.button').hide(); 
		$('.curtain input.ibutton').show(); 
		return false; 
	}
}

function validate_cname(uid,cname) { 
	$('.curtain input.ibutton').css({'visibility':'visible'}); 

	if(/([^\s])/.test(cname)) {
		$.ajax({
			type:'POST', 
			url:'controllers/operator.php', 
			data: {
				action:'confirm_coursecode',
				cname:cname, 
				uid:uid
			}, 
			success: function(data) {
				if(data==1 || uid<1) {
					$('.curtain input.value#cname').css({ 'background-color':RED }); 
					$('.curtain input.button').hide(); 
					$('.curtain input.ibutton').show(); 
					UCHECK = false; 
				} else {
					$('.curtain input.value#cname').css({ 'background-color':GRB }); 
					$('.curtain input.button').show(); 
					$('.curtain input.ibutton').hide(); 
					UCHECK = true; 
				} 
			} 
		}); 

		return UCHECK; 
	} else {
		$('.curtain input.value#cname').css({ 'background-color':RED }); 
		$('.curtain input.button').hide(); 
		$('.curtain input.ibutton').show(); 
		return false; 
	}
}