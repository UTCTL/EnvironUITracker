$(document).ready(function() {
	$('.errormsg').hide(); 

	checkSession(); 

	$('input').on('keydown', function(e) { 
		if(e.which==13) 
			$(this).parent().find('input[type="button"]').click(); 
	}); 

	$('#login').on('click', function() {
		var uname = $('input#uname').val(), 
			pword = $('input#pword').val(); 

		$.ajax({
			type: 'POST', 
			url: 'server/controllers/operator.php', 
			data: {
				action: 'login', 
				uname: uname, 
				pword: pword 
			}, 
			success: function(data) {
				if(data=="success") 
					window.location.href = window.location.origin+window.location.pathname+'#dashboard'; 
				else {
					$('.errormsg').show(); 
					$('.errortitle').text('Error loging in'); 
					$('.errortext').text('Please make sure your credentials are correct.'); 

					setTimeout(function() {
						$('.errormsg').fadeOut(); 
					}, 1000); 
				}
			}
		})
	}); 
}); 