$(document).ready(function() {

	checkSession(); 

	$('#logout').on('click', function(e) { 
		$.ajax({
			type: 'POST', 
			url: 'server/controllers/operator.php', 
			data: {
				action: 'logout' 
			}, 
			success: function(data) {
				checkSession(); 
			}
		})
	}); 

}); 

function checkSession() {
	$.ajax({
		type: 'POST', 
		url: 'server/controllers/operator.php', 
		data: {
			action: 'isloggedin' 
		}, 
		success: function(data) {
			if(data=="success") 
				window.location.href = window.location.origin+window.location.pathname+'#dashboard'; 
			else 
				window.location.href = window.location.origin+window.location.pathname+'#login'; 
		}
	})
}